<?php
    /**
     * DatabaseObject
     *
     * Abstract class used to easily manipulate data in a database table
     * via simple load/save/delete methods
     */
    abstract class DatabaseObject
    {
        const TYPE_TIMESTAMP = 1;
        const TYPE_BOOLEAN   = 2;

        protected static $types = array(self::TYPE_TIMESTAMP, self::TYPE_BOOLEAN);

        private $_id = null;
        private $_properties = array(); 


        protected $_db = null;
        protected $_table = '';
        protected $_idField = '';

        public function __construct(Zend_Db_Adapter_Abstract $db, $table, $idField)
        {
            $this->_db = $db;
            $this->_table = $table;
            $this->_idField = $idField;
        }

        public function load($id, $field = null)
        {
            $logger = Zend_Registry::get('logger');  
            if (strlen($field) == 0)
                $field = $this->_idField;

            if ($field == $this->_idField) {
                $id = (int) $id;
                if ($id <= 0)
                    return false;
            }

            $query = sprintf('select %s from %s where %s = ?',
                             join(', ', $this->getSelectFields()),
                             $this->_table,
                             $field);          
            
            $logger->notice('$query = '.$query);
            $query = $this->_db->quoteInto($query, $id);

            return $this->_load($query); //возвращает признак удачности выполнения sql запроса (true или  false)
        }
        
        //add hlv вывод всех записей из заданной таблицы
        public function loadAll()
        {
            $logger = Zend_Registry::get('logger');

            $query = sprintf(
                'select %s from %s',
                join(', ', $this->getSelectFields()),
                $this->_table                
            );
			      $logger->notice('loadAll $query = '.$query);
        
            $rec = $this->_db->fetchAll($query);
			      //������ �� ������� ����������� �� ��������� WINDOWS-1251 � ��������� UTF-8
            /*$arr_for_row=array();
			      $arr_for_data=array(); 
            foreach ( $rec as $row )
            {
              foreach ( $row as $key => $val )
              {
                //$arr_for_row[$key] = iconv('WINDOWS-1251','UTF-8',  $val );
                $arr_for_row[$key] =   $val ;
			        }      
			          $arr_for_data[] = $arr_for_row;  
            }     
            return $arr_for_data;*/
            return $rec;
        }
        

        
        protected function getSelectFields($prefix = '')
        {
            $fields = array($prefix . $this->_idField);
            foreach ($this->_properties as $k => $v)
                $fields[] = $prefix . $k;

            return $fields;
        }

        protected function _load($query)
        {
            $result = $this->_db->query($query);
            $row = $result->fetch();
            if (!$row)
                return false;

            $this->_init($row);

            $this->postLoad();

            return true;
        }

        public function _init($row)
        {
            foreach ($this->_properties as $k => $v) {
                $val = $row[$k];

                switch ($v['type']) {
                    case self::TYPE_TIMESTAMP:
                        if (!is_null($val))
                            $val = strtotime($val);
                        break;
                    case self::TYPE_BOOLEAN:
                        $val = (bool) $val;
                        break;
                }

                $this->_properties[$k]['value'] = $val;
            }
            $this->_id = (int) $row[$this->_idField];
        }


        public function save($useTransactions = true)
        {
           $logger = Zend_Registry::get('logger');
			      $logger->notice('save');
            $update = $this->isSaved();

            if ($useTransactions)
                $this->_db->beginTransaction();

            if ($update)
                $commit = $this->preUpdate();
            else
                $commit = $this->preInsert();

            if (!$commit) {
                if ($useTransactions)
                    $this->_db->rollback();
                return false;
            }

            $row = array();

            foreach ($this->_properties as $k => $v) {
                if ($update && !$v['updated'])
                    continue;

                switch ($v['type']) {
                    case self::TYPE_TIMESTAMP:
                        if (!is_null($v['value'])) {
                            if ($this->_db instanceof Zend_Db_Adapter_Pdo_Pgsql)
                                $v['value'] = date('Y-m-d H:i:sO', $v['value']);
                            else
                                $v['value'] = date('Y-m-d H:i:s', $v['value']);
                        }
                        break;

                    case self::TYPE_BOOLEAN:
                        $v['value'] = (int) ((bool) $v['value']);
                        break;
                }

               // $row[$k] = iconv('UTF-8', 'WINDOWS-1251', $v['value'] );
                $row[$k] =  $v['value'] ;
            }

            if (count($row) > 0) {
                // perform insert/update
                if ($update) {
                    $this->_db->update($this->_table, $row, sprintf('%s = %d', $this->_idField, $this->getId()));
                }
                else {
                 $logger->notice('BEFOR INSERT');
                    $this->_db->insert($this->_table, $row);
                    $this->_id = $this->_db->lastInsertId($this->_table, $this->_idField);
                    $logger->notice('AFTOR INSERT');
                }
            }

            // update internal id

            if ($commit) {
                if ($update)
                    $commit = $this->postUpdate();
                else
                    $commit = $this->postInsert();
            }

            if ($useTransactions) {
                if ($commit)
                    $this->_db->commit();
                else
                    $this->_db->rollback();
            }

            return $commit;
        }

        public function delete($useTransactions = true)
        {
            if (!$this->isSaved())
                return false;

            if ($useTransactions)
                $this->_db->beginTransaction();

            $commit = $this->preDelete();

            if ($commit) {
                $this->_db->delete($this->_table, sprintf('%s = %d', $this->_idField, $this->getId()));
            }
            else {
                if ($useTransactions)
                    $this->_db->rollback();
                return false;
            }

            $commit = $this->postDelete();

            $this->_id = null;

            if ($useTransactions) {
                if ($commit)
                    $this->_db->commit();
                else
                    $this->_db->rollback();
            }

            return $commit;
        }

        public function isSaved()
        {
            return $this->getId() > 0;
        }

        public function getId()
        {
            return (int) $this->_id;
        }
        // add hlv, используется для удаления записи, что бы не вызывать лишний раз load
        public function setId( $id )
        {
            $this->_id = $id;
        }

        public function getDb()
        {
            return $this->_db;
        }

        public function __set($name, $value)
        {
            $logger = Zend_Registry::get ( 'logger' );
            $logger->notice('__set  $name = '.$name);
         
            if (array_key_exists($name, $this->_properties)) {
                //$this->_properties[$name]['value'] = iconv('UTF-8', 'WINDOWS-1251', $value );
                $this->_properties[$name]['value'] =  $value ;
                $this->_properties[$name]['updated'] = true;
                return true;
            }

            return false;
        }

        public function __get($name)
        {
           $logger = Zend_Registry::get ( 'logger' );
            $logger->notice('__get  $name = '.$name);
            //return array_key_exists($name, $this->_properties) ? iconv('WINDOWS-1251','UTF-8',$this->_properties[$name]['value']) : null;
            return array_key_exists($name, $this->_properties) ? $this->_properties[$name]['value'] : null;
        }

        protected function add($field, $default = null, $type = null)
        {
            $this->_properties[$field] = array('value'   => $default,
                                               'type'    => in_array($type, self::$types) ? $type : null,
                                               'updated' => false);
        }

        protected function preInsert()
        {
            return true;
        }

        protected function postInsert()
        {
            return true;
        }

        protected function preUpdate()
        {
            return true;
        }

        protected function postUpdate()
        {
            return true;
        }

        protected function preDelete()
        {
            return true;
        }

        protected function postDelete()
        {
            return true;
        }

        protected function postLoad()
        {
            return true;
        }

        public static function BuildMultiple($db, $class, $data)
        {
            $ret = array();

            if (!class_exists($class))
                throw new Exception('Undefined class specified: ' . $class);

            $testObj = new $class($db);

            if (!$testObj instanceof DatabaseObject)
                throw new Exception('Class does not extend from DatabaseObject');

            foreach ($data as $row) {
                $obj = new $class($db);
                $obj->_init($row);

                $ret[$obj->getId()] = $obj;
            }

            return $ret;
        }
    }
?>
