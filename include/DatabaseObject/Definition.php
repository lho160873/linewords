<?php
    class DatabaseObject_Definition extends DatabaseObject
    {
        
        public function __construct($db)
        {
            $logger = Zend_Registry::get('logger');
			      $logger->notice('DatabaseObject_Definition::__construct');
            parent::__construct($db, 'definition', 'idn');
            $this->add('name');
            $this->add('descript');
        }
        
        public function doDelete( $id )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Definition::doDelete');
          $logger->notice('$id = '.$id);
          $this->setId( $id );
          if (!$this->delete()) return array();  //Ð² ÑÐ»ÑƒÑ‡Ð°Ðµ Ð½Ðµ ÑƒÑÐ¿ÐµÑ…Ð° Ð²Ð¾Ð·Ð²Ñ€Ð°Ñ‰Ð°ÐµÐ¼ Ð¿ÑƒÑÑ‚Ð¾Ð¹ Ð¼Ð°ÑÑÐ¸Ð²
               
          $res = array();
          $res['idn'] = $id;
          return $res;        
        }
        
        
        public function doSave( $params, $isNew )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Definition::doSave');
          $logger->notice('param = '.print_r(	array_keys($params),true ));
          $logger->notice('param = '.print_r(	$params,true ));
          if ( !$isNew )
            $this->load($params['idn']);  
          foreach ( array_keys($params) as $col ) 
          {  
             $logger->notice('$col = '.$col);
            if ( $col != 'idn' )
              $this->$col=$params[$col];
          }     
          if (!$this->save()) return array();  //Ð² ÑÐ»ÑƒÑ‡Ð°Ðµ Ð½Ðµ ÑƒÑÐ¿ÐµÑ…Ð° Ð²Ð¾Ð·Ð²Ñ€Ð°Ñ‰Ð°ÐµÐ¼ Ð¿ÑƒÑÑ‚Ð¾Ð¹ Ð¼Ð°ÑÑÐ¸Ð²
     
          $res = array();
          $res['idn']=$this->getId();
          $res['name']=$this->name;
          $res['descript']=$this->descript;

          return $res;
        }
        
        public function getCount( )
        {
            $logger = Zend_Registry::get('logger');
            $query = $this->_db->select();
            $query->from($this->_table,'count(*)');
            $logger->notice('getCount $query = '.$query->__toString());
            return $this->_db->fetchOne($query);
        }
        
        public function find( $str_find, $limit, $offset )
        {
          $logger = Zend_Registry::get('logger');
          $logger->notice('find $str_find = '.$str_find.' $offset = '.$offset);
        
          $num_page = 1;
          $num_row = -1;//$offset; çàïèñü íå íàøëè
          $res = array();
          $res['num_page']=$num_page;
          $res['num_row']=$num_row;
        
        
          $count = $this->getCount( ); 
          if ( $count == 0 ) return  $res;
          if ($offset == 0)
          {
            $recordset = $this->loadLimit(0);
          }
          else
          {
            $offset = $offset + 1;
            $limit_for_rec = $count - $offset;
            $recordset = $this->loadLimit( $limit_for_rec, $offset );
          }
          $logger->notice('find $count = '.$count);
                   
          $curr_row = 0;
          $num_find = 0;
         
          foreach ( $recordset as $row )
          {
            //if ( $curr_row <= $offset) {$curr_row++; continue;}
            $t1 = strtolower(iconv('UTF-8','WINDOWS-1251',$row["name"]));
            $t2 = strtolower(iconv('UTF-8','WINDOWS-1251',$str_find));
            //$t1 = $row["name"];
            //$t2 = $str_find;
            $len = 0;
            if ( strlen($t2) <= strlen($t1) )
              $len = strlen($t2);
            
            if ($len == 0) 
            {
              $curr_row++;
              continue;
            }
           if ( strncasecmp($t1, $t2, $len) == 0 )
            //if (   substr_count(  $t1,  $t2 ) != 0 ) //ñðàâíåíèå íà âõîæäåíèå ïîäñòîðêè â ñòðîêå
            {
              $num_find = $curr_row+$offset;
              break;
            }
            $curr_row++;
          }
        
          $logger->notice('$num_find = '.$num_find);       

          if ( $num_find == 0 ) return  $res;
        
          $num_page = ceil( ($num_find+1) / $limit );
          $num_row =  $num_find - floor( ($num_find) / $limit )*$limit ;
          $logger->notice('$num_page = '.$num_page);
          $logger->notice('$num_row = '.$num_row);
          
          $res['num_page']=$num_page;
          $res['num_row']=$num_row;
          $logger->notice('!!!!!!!!!!!!!!!!!!!!!');
          return  $res;
        }
        
        public function loadLimit( $limit, $offset = 0 )
        {           
            $logger = Zend_Registry::get('logger');
            
            $query = $this->_db->select();
            $query->from($this->_table,$this->getSelectFields());
            //$select->where('p.idn=?',1);
            //$select->where('p.name=?','rav');
            //$select->joinInner('');
            //$select->group('');
            //$select->distinct();
            $query->order('name');
            $query->order('idn');
            if ( $limit > 0 )
            $query->limit( $limit, $offset );
           
            $logger->notice('loadLimit $query = '.$query->__toString());
            
            $rec = $this->_db->fetchAll($query);
            return $rec;
        }
        
               
      }
?>
