<?php
    class DatabaseObject_Subj extends DatabaseObject
    {
        
        public function __construct($db)
        {
            $logger = Zend_Registry::get('logger');
			      $logger->notice('DatabaseObject_Subj::__construct');
            parent::__construct($db, 'subj_area', 'subj_area_id');
            $this->add('name');
            $this->add('fio');
        }
        
        public function loadTreeRoot()
        {        
          $logger = Zend_Registry::get('logger');
			    $logger->notice('DatabaseObject_Subj::loadTreeRoot'); 
          $sample_my = array();
          $id = -1;
          $sample_my = $this->loadTree( $id );   
          $logger->notice(print_r($sample_my,true));         
          return $sample_my;
        }
        
        //add hlv вывод всех записей из заданной таблицы
        public function loadTree( $id )
        {
          $logger = Zend_Registry::get('logger');
			    $logger->notice('DatabaseObject_Subj::loadTree'); 
      
      /*    $sampleArr = array( 
       array( id=>1, 'text'=>"Node 1", leaf=>false, expanded=>true,
          children=>array( array(id=>2, "text"=>"Child 1", leaf=>true, expanded=>true), array(id=>3, "text"=>"Child 2", leaf=>true, expanded=>true)))
       ,array( id=>4, 'text'=>"Node 2", leaf=>false, expanded=>true, 
          children=>array( array(id=>5, "text"=>"Child 1", leaf=>true, expanded=>true), array(id=>6, "text"=>"Child 2", leaf=>true, expanded=>true))));
        */ 
       
        $query = 'select m.subj_area_id as id, m.name, m.fio, p.main_subj_area as parent'  
        //$query = 'select m.subj_area_id as id, m.name, \' \' as fio, p.main_subj_area as parent'  
        .' from subj_area as m left join s_a_hierarchy as p on m.subj_area_id = p.junior_subj_area';
        if ( $id == -1 )
         $query = $query.' where p.main_subj_area is null';
        else
         $query = $query.' where p.main_subj_area = '. $id;
        
        $logger->notice($query);        
        $rec = $this->_db->fetchAll($query);
        
        //?? ??? ???????? WINDOWS-1251 ??? UTF-8
            $arr_for_row=array();
			      $arr_for_data=array(); 
            foreach ( $rec as $row )
            {
              foreach ( $row as $key => $val )
              {
                //$arr_for_row[$key] = iconv('WINDOWS-1251','UTF-8',  $val );
                $arr_for_row[$key] = $val ;
			        }      
			          $arr_for_data[] = $arr_for_row;  
            } 
               
             $sample = array();
             $sample_row = array();
             
            foreach ( $arr_for_data as $row )
            {
            $sample_row["id"]=$row["id"];
            $sample_row["text"]=$row["name"];
            //$sample_row["text"]=str_replace(" ","\n", $row["name"]);
            $sample_row["fio"]=$row["fio"];
            $sample_row["leaf"]=(!$this->subjLeaf($row["id"]));
            $sample_row["expanded"]=true;
                      
            if ( $this->subjLeaf($row["id"]) )
            {
             $sample_add = array();
             $sample_add = $this->loadTree( $row["id"] );
             $sample_row["children"]=$sample_add;
             }
             
              
              $sample[] = $sample_row;  
            }
            
           // $logger->notice(print_r($sample,true));   
           return  $sample;     

        }


         public function subjLeaf( $id )
        {
            $logger = Zend_Registry::get('logger');
            $logger->notice('subjLeaf');
            $query = sprintf('select count(*) from s_a_hierarchy where main_subj_area = %d',
                             $id);

            $result = $this->_db->fetchOne($query);
            //$logger->notice('query = '.$query);
			      //$logger->notice('subjLeaf'.print_r($result,true)); 
			      
            return ( $result > 0 );
        }
        
       
       
       
        public function doDelete( $id )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Definition::doDelete');
          $logger->notice('$id = '.$id);
          $this->setId( $id );
          if (!$this->delete()) return array();  //в случае не успеха возвращаем пустой массив
               
          $res = array();
          $res['idn'] = $id;
          return $res;        
        }
        
        
        public function doSave( $params, $isNew )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Subj::doSave');
          $logger->notice('param = '.print_r(	array_keys($params),true ));
          $logger->notice('param = '.print_r(	$params,true ));
          if ( !$isNew )
            $this->load($params['subj_area_id']);  
          foreach ( array_keys($params) as $col ) 
          {  
             $logger->notice('$col = '.$col);
            if ( $col != 'subj_area_id' )
              $this->$col=$params[$col];
          }     
          if (!$this->save()) return array();  //в случае не успеха возвращаем пустой массив
     
          $res = array();
          $res['subj_area_id']=$this->getId();
          $res['name']=$this->name;
          //$res['descript']=$this->descript;

          return $res;
        }  
        
        
        public function doUpdate( $params )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Subj::doUpdate');
          $logger->notice('param = '.print_r(	array_keys($params),true ));
          $logger->notice('param = '.print_r(	$params,true ));
            $this->load($params['subj_area_id']);  
          foreach ( array_keys($params) as $col ) 
          {  
             $logger->notice('$col = '.$col);
            if ( $col != 'subj_area_id' )
              $this->$col = $params[$col];
          }     
          if (!$this->save()) return array();  //в случае не успеха возвращаем пустой массив
     
          $res = array();
          $res['subj_area_id']=$this->getId();
          $res['name']=$this->name;
          //$res['descript']=$this->descript;

          return $res;
        } 
        
        
        public function doInsert( $params )
        {
          $logger = Zend_Registry::get ( 'logger' );
          $logger->notice('DatabaseObject_Subj::doInser');
          $logger->notice('param = '.print_r(	array_keys($params),true ));
          $logger->notice('param = '.print_r(	$params,true ));
          
          //$this->load($params['subj_area_id']);  
          
          foreach ( array_keys($params) as $col ) 
          {  
             $logger->notice('$col = '.$col);
            if ( $col != 'root_id' )
              $this->$col = $params[$col];
          }     
          $logger->notice('BEFOR SAVE');
          if (!$this->save()) return array();  //в случае не успеха возвращаем пустой массив
     $logger->notice('AFTOR SAVE');
     $root_id = $params['root_id'];
     $id = $this->getId();
     if ( $root_id != -1 )
        {
        $query = 'insert into s_a_hierarchy (main_subj_area, junior_subj_area) VALUES ('.$root_id.', '.$id.')';
        $logger->notice('!!!!!!!!!!!!!!!!! $query = '.$query);
        $rec = $this->_db->query($query);
        if (!$rec ) $logger->notice('ERROR INSERT');
        //��������� ������ � ���� S_A_HIERARCHY
        }
     
     
          $res = array();
          $res['subj_area_id']=$this->getId();
          $res['name']=$this->name;
          //$res['descript']=$this->descript;

          return $res;
        } 
             
      }
?>
