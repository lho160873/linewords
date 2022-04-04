<?php

class IndexController extends CustomControllerAction {

  
 
	public function indexAction() {
	  $logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'indexAction' );

    	//echo 'indexAction';
	  //$this->breadcrumbs->addStep('Login');
	}
	
	public function viewAction() 
  {	
      $logger = Zend_Registry::get('logger');       
      $logger->notice ( 'viewAction' );
      $this->_helper->viewRenderer->setNoRender();
      
      $db_developers = new DatabaseObject_Developer($this->db);
      $rows = $db_developers->loadAll();
      
      $res = array(
            'success'   => true,
            'message'   => "Loaded data",
            'data'      => $rows
        );
      $this->sendJson($res);

     /* этот вариант тоже работает, но как описано выше наверно правильнее
      $res = new Response(); 
	    $res->success = true;
      $res->message = "Loaded data";      
      
      $res->data = $rows;
      echo $res->to_json(); */
	}
	
	public function testAction() 
  {	
    $this->_redirect('account/login');
    /*
    $this->_helper->viewRenderer->setNoRender();
    $db_developers = new DatabaseObject_Developer($this->db);
    
    
    $rec = $db_developers->loadAll();

    echo print_r($rec,true);
    
    $logger = Zend_Registry::get('logger');       
    $logger->notice ( print_r($rec,true) );*/
  }
	
	public function insertAction()
	{
  	$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'insertAction' );
		$this->_helper->viewRenderer->setNoRender();
    $this->save( true ); //запсь новая
  }
  
	public function updateAction()
	{
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'updateAction' );
		$this->_helper->viewRenderer->setNoRender();
    $this->save( false ); //обновить существующие записи
  }
  
	public function save( $isNew ) 
  {
    $isSuccess = true;
    $request = $this->getRequest();
    $logger = Zend_Registry::get ( 'logger' );
    //$logger->notice ( 'updateAction $_params = '.print_r($request->getParams(),true));
    if ( $request->isPost() ) 
    {      
      $params = Zend_Json::decode($request->getPost('data'));
      //$logger->notice ( '!!!!!!!!!!!!!!!!! $params = '.print_r($params,true));
      
      if ( count($params)<=0 ) return; 
      //$db_developers = new DatabaseObject_Developer($this->db);
      $res_data = array();
      //входной массив содержит несколько строк с данными (для нескольких записей)
      //if ( count($params[0])>1 ) 
      if ( is_array($params[0]) )
      {
        foreach ($params as $data) 
        {    
            $db_developers = new DatabaseObject_Developer($this->db);
            $row_data = $db_developers->doSave($data,$isNew);
            if ( count( $row_data ) <= 0 )
            {
              $isSuccess = false;
              break;
            }
            array_push( $res_data, $row_data);
        }
      }
      else //входной массив содержит данные для одной записи
      {
        $db_developers = new DatabaseObject_Developer($this->db);
        $res_data = $db_developers->doSave($params,$isNew);
        $isSuccess = ( count( $res_data )>0 );
      }
      //посылаем результат выполнения обновляения данных
      $res = array(
            'success'   => $isSuccess,
            'message'   => "update/insert",
            'data'      => $res_data
                  );
       $this->sendJson($res);     
    }      
	}
	
	

  public function deleteAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'deleteAction' );
		$this->_helper->viewRenderer->setNoRender();
    
    $isSuccess = true;
    $request = $this->getRequest();
    
    if ( $request->isPost() ) 
    {      
      $params = Zend_Json::decode($request->getPost('data'));
      if ( count($params)<=0 ) return; 
      $db_developers = new DatabaseObject_Developer($this->db);
      $res_data = array();
      //входной массив содержит несколько строк с данными (для нескольких записей)
      if ( count($params)>1 ) 
      {
        foreach ($params as $id) 
        {    
            $row_data = $db_developers->doDelete($id);
            if ( count( $row_data ) <= 0 )
            {
              $isSuccess = false;
              break;
            }
            array_push( $res_data, $row_data);
        }
      }
      else //входной массив содержит данные для одной записи
      {
        $res_data = $db_developers->doDelete($params);
        $isSuccess = ( count( $res_data )>0 );
      }
      //посылаем результат выполнения обновляения данных
      $res = array(
            'success'   => $isSuccess,
            'message'   => "delete",
            'data'      => $res_data
                  );
       $this->sendJson($res);     
    }
  } 
}
?>
