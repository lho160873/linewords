<?php
    class DatabaseObject_Graph
    {
        protected $_db = null;
        public function __construct($db)
        {
            $logger = Zend_Registry::get('logger');
			      $logger->notice('DatabaseObject_Graph::__construct');
            //parent::__construct($db, 'subj_area', 'subj_area_id');
            //$this->add('name');
            //$this->add('fio');
             $this->_db = $db;
        }
        
        public function loadDefinitonBad($subj_area_id)
        {
        $logger = Zend_Registry::get('logger');
			    $logger->notice('DatabaseObject_Graph::loadDefinitonBad'); 
          
          $query = 'select m.idn as id, m.name,  m.num_urov '  
            .' from definition as m where m.subj_area_id='.$subj_area_id.' and m.num_urov=0 '
            ;
        
          $logger->notice($query);        
          
          $rec = $this->_db->fetchAll($query);
        
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
            $sample_row["num_urov"]=$row["num_urov"];
            $sample_row["text"]=str_replace(" ","\n", $row["name"]);
            $sample[] = $sample_row;  
            }
            
           return  $sample;   
        }
        public function loadDefinitonGood($subj_area_id)
        {        
          $logger = Zend_Registry::get('logger');
			    $logger->notice('DatabaseObject_Graph::loadDefinitonGood'); 
          
          $query = 'select m.idn as id, m.name,  m.num_urov '  
            .' from definition as m where m.subj_area_id='.$subj_area_id.' and m.num_urov<>0 order by m.num_urov '
            ;
        
          $logger->notice($query);        
          
          $rec = $this->_db->fetchAll($query);
        
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
            $sample_row["num_urov"]=$row["num_urov"];
            $sample_row["text"]=str_replace(" ","\n", $row["name"]);
            $sample[] = $sample_row;  
            }
            
           return  $sample;   
 
        }
 
  public function loadConnections($subj_area_id)
  {
        $logger = Zend_Registry::get('logger');
			    $logger->notice('DatabaseObject_Graph::loadConnections'); 
          
          $query = 'select p.idn_old, p.idn_low'  
            .' from ref_old_low as p inner join definition as d1 on d1.idn = p.idn_old
            inner join  definition as d2  on d2.idn = p.idn_low'
            .' where d1.subj_area_id='.$subj_area_id.' and d2.subj_area_id='.$subj_area_id;
        
          $logger->notice($query);        
          
          $rec = $this->_db->fetchAll($query);
        
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
             //$logger->notice(print_r($arr_for_data, true));  
            
            
           return  $arr_for_data;   
 }
 
        
        //add hlv РІС‹РІРѕРґ РІСЃРµС… Р·Р°РїРёСЃРµР№ РёР· Р·Р°РґР°РЅРЅРѕР№ С‚Р°Р±Р»РёС†С‹
        public function loadTree( $id )
        {
          $logger = Zend_Registry::get('logger');
			    $logger->notice('DatabaseObject_Graph:loadTree $id = '.$id); 
      
      /*    $sampleArr = array( 
       array( id=>1, 'text'=>"Node 1", leaf=>false, expanded=>true,
          children=>array( array(id=>2, "text"=>"Child 1", leaf=>true, expanded=>true), array(id=>3, "text"=>"Child 2", leaf=>true, expanded=>true)))
       ,array( id=>4, 'text'=>"Node 2", leaf=>false, expanded=>true, 
          children=>array( array(id=>5, "text"=>"Child 1", leaf=>true, expanded=>true), array(id=>6, "text"=>"Child 2", leaf=>true, expanded=>true))));
        */ 
       
        $query = 'select m.idn as id, m.name, m.name as fio, p.idn_old as parent'  
        //$query = 'select m.subj_area_id as id, m.name, \' \' as fio, p.main_subj_area as parent'  
        .' from definition as m left join ref_old_low as p on m.idn = p.idn_low';
        if ( $id == -1 )
         $query = $query.' where p.idn_old is null and m.subj_area_id=20';
        else
         $query = $query.' where p.idn_old = '. $id.' and m.subj_area_id=20';
        
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

            $logger->notice('$row["id"] = '.$row["id"]);
            $logger->notice('$id = '.$id);

            //$sample_row["text"]=$row["name"];
            $sample_row["text"]=str_replace(" ","\n", $row["name"]);
            $sample_row["fio"]=$row["fio"];
            $sample_row["leaf"]=( !$this->defLeaf($row["id"]));
            $sample_row["expanded"]=false;
                      
            
            if ( $this->defLeaf($row["id"])  )
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


         public function defLeaf( $id )
        {
            $logger = Zend_Registry::get('logger');
            $logger->notice('defLeaf $id = '.$id);
            $query = sprintf('select count(*) from ref_old_low where idn_old = %d  ',
                             $id);

            $result = $this->_db->fetchOne($query);
            //$logger->notice('query = '.$query);
			      //$logger->notice('subjLeaf'.print_r($result,true)); 
			      
            return ( $result > 0 );
        }
        
       
       
       
             
      }
?>

