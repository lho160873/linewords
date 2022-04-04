<?php

class DefinitionController extends CustomControllerAction {
	
	public function indexAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'DefinitionController::indexAction' );
		//echo 'indexAction';
		$this->breadcrumbs->addStep ( 'Понятия', $this->getUrl ( null, 'definition' ) );
	}
	
	public function viewAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'DefinitionController::viewAction' );
		$this->_helper->viewRenderer->setNoRender ();
		
			$request = $this->getRequest ();
		
		//$logger->notice ( 'updateAction $_params = '.print_r($request->getParams(),true));
		if ($request->isPost ()) {
		$logger->notice ('1');
			$start = Zend_Json::decode ( $request->getPost ( 'start' ) );
			$end = Zend_Json::decode ( $request->getPost ( 'limit' ) );
			$logger->notice (' $start = '.$start.' $end = '.$end);
			}
			
    $db_obj = new DatabaseObject_Definition ( $this->db );
		//$rows = $db_obj->loadAll ();
		$rows = $db_obj->loadLimit( $end, $start );
		$total = $db_obj->getCount( );
		$logger->notice ('$total='.$total);
		$res = array ('success' => true, 'message' => "Loaded data", 'total' => $total, 'data' => $rows );
		
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'DefinitionController::viewAction' );
		
		$this->sendJson ( $res );
		
	/* этот вариант тоже работает, но как описано выше наверно правильнее
      $res = new Response(); 
	    $res->success = true;
      $res->message = "Loaded data";      
      
      $res->data = $rows;
      echo $res->to_json(); */
	}
	
	public function insertAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'insertAction' );
		$this->_helper->viewRenderer->setNoRender ();
		$this->save ( true ); //запсь новая
	}
	
	public function updateAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'updateAction' );
		$this->_helper->viewRenderer->setNoRender ();
		$this->save ( false ); //обновить существующие записи
	}
	
	public function save($isNew) {
		$isSuccess = true;
		$request = $this->getRequest ();
		$logger = Zend_Registry::get ( 'logger' );
		//$logger->notice ( 'updateAction $_params = '.print_r($request->getParams(),true));
		if ($request->isPost ()) {
			$params = Zend_Json::decode ( $request->getPost ( 'data' ) );
			//$logger->notice ( '!!!!!!!!!!!!!!!!! $params = '.print_r($params,true));
			

			if (count ( $params ) <= 0)
				return;
				//$db_obj = new DatabaseObject_Developer($this->db);
			$res_data = array ();
			//входной массив содержит несколько строк с данными (для нескольких записей)
			//if ( count($params[0])>1 ) 
			if (is_array ( $params [0] )) {
				foreach ( $params as $data ) {
					$db_obj = new DatabaseObject_Definition ( $this->db );
					$row_data = $db_obj->doSave ( $data, $isNew );
					if (count ( $row_data ) <= 0) {
						$isSuccess = false;
						break;
					}
					array_push ( $res_data, $row_data );
				}
			} else //входной массив содержит данные для одной записи
{
				$db_obj = new DatabaseObject_Definition ( $this->db );
				$res_data = $db_obj->doSave ( $params, $isNew );
				$isSuccess = (count ( $res_data ) > 0);
			}
			//посылаем результат выполнения обновляения данных
			$res = array ('success' => $isSuccess, 'message' => "update/insert", 'data' => $res_data );
			$this->sendJson ( $res );
		}
	}
	
	public function deleteAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'deleteAction' );
		$this->_helper->viewRenderer->setNoRender ();
		
		$isSuccess = true;
		$request = $this->getRequest ();
		
		if ($request->isPost ()) {
			$params = Zend_Json::decode ( $request->getPost ( 'data' ) );
			if (count ( $params ) <= 0)
				return;
			$db_obj = new DatabaseObject_Definition ( $this->db );
			$res_data = array ();
			//входной массив содержит несколько строк с данными (для нескольких записей)
			if (count ( $params ) > 1) {
				foreach ( $params as $id ) {
					$row_data = $db_obj->doDelete ( $id );
					if (count ( $row_data ) <= 0) {
						$isSuccess = false;
						break;
					}
					array_push ( $res_data, $row_data );
				}
			} else //входной массив содержит данные для одной записи
{
				$res_data = $db_obj->doDelete ( $params );
				$isSuccess = (count ( $res_data ) > 0);
			}
			//посылаем результат выполнения обновляения данных
			$res = array ('success' => $isSuccess, 'message' => "delete", 'data' => $res_data );
			$this->sendJson ( $res );
		}
	}
		public function findAction()
		{
      $logger = Zend_Registry::get( 'logger' );
      $logger->notice( 'findAction');
    	$db_obj = new DatabaseObject_Definition ( $this->db );
    	$request = $this->getRequest ();
		  $str_find = '';
		  $limit = 0;
		  $offset = 0;
		  if ($request->isPost ()) 
      {
		     $logger->notice('isPost OK');
			   $str_find = $request->getPost('strfind');//iconv('WINDOWS-1251','UTF-8', $request->getPost('strfind')) ;//Zend_Json::decode( $request->getPost('strfind') );
			   $limit = $request->getPost('limit');
			   $offset = $request->getPost('offset');
      }
			$logger->notice('$str_find = '.$str_find);
    	$res = $db_obj->find($str_find,$limit,$offset);
    	$this->sendJson ( $res );
    }
}
?>
