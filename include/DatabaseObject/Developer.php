<?php
    class DatabaseObject_Developer extends DatabaseObject
    {
        
        public function __construct($db)
        {
            $logger = Zend_Registry::get('logger');
			      $logger->notice('DatabaseObject_Developer::__construct');
            parent::__construct($db, 'developer', 'developer_id');
            $this->add('fio');
            $this->add('comment');
        }
        
        public function doDelete( $id )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Developer::doDelete');
          $logger->notice('$id = '.$id);
          $this->setId( $id );
          if (!$this->delete()) return array();  //в случае не успеха возвращаем пустой массив
               
          $res = array();
          $res['developer_id'] = $id;
          return $res;        
        }
        
        
        public function doSave( $params, $isNew )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Developer::doSave');
          $logger->notice('param = '.print_r(	array_keys($params),true ));
          $logger->notice('param = '.print_r(	$params,true ));
          if ( !$isNew )
            $this->load($params['developer_id']);  
          foreach ( array_keys($params) as $col ) 
          {  
             $logger->notice('$col = '.$col);
            if ( $col != 'developer_id' )
              $this->$col=$params[$col];
          }     
          if (!$this->save()) return array();  //в случае не успеха возвращаем пустой массив
     
          $res = array();
          $res['developer_id']=$this->getId();
          $res['fio']=$this->fio;
          $res['comment']=$this->comment;
          $logger->notice('$res = '.print_r(	$res,true ));
          return $res;
        }       
      }
?>
