<?php

class SubjController extends CustomControllerAction {
	
	public function indexAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'subjAction' );
		$this->breadcrumbs->addStep ( 'Предметные области', $this->getUrl ( null, 'subj' ) );
	
	}
	
	public function viewAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'viewAction' );
		$this->_helper->viewRenderer->setNoRender ();
		
		$db_subj = new DatabaseObject_Subj ( $this->db );
		$tree = $db_subj->loadTreeRoot ();
		$this->sendJson ( $tree );
	
	}
	
	public function insertAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'inserAction' );
		$this->_helper->viewRenderer->setNoRender ();
		
		$request = $this->getRequest ();
		$root_id = $request->getPost ( 'root_id' );
		$name = $request->getPost ( 'name' );
		//$oldvalue= $request->getPost('oldvalue');
		$logger->notice ( ' root_id = ' . $root_id . ' $name = ' . $name );
		
		$params = array ();
		$params ['name'] = $name;
		$params ['root_id'] = $root_id;
		
		$db_obj = new DatabaseObject_Subj ( $this->db );
		$res_data = $db_obj->doInsert ( $params );
		
		$this->sendJson ( $res_data );
		/* $isSuccess = ( count( $res_data )>0 );
       
        $res = array(
            'success'   => $isSuccess,
            'message'   => "update/insert",
            'data'      => $res_data
                  );
       $this->sendJson($res);    
       	
        //$this->save( true ); //Р·Р°РїСЃСЊ РЅРѕРІР°СЏ        */
	}
	
	public function updateAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'updateAction' );
		$this->_helper->viewRenderer->setNoRender ();
		
		$request = $this->getRequest ();
		$id = $request->getPost ( 'id' );
		$newvalue = $request->getPost ( 'newvalue' );
		$oldvalue = $request->getPost ( 'oldvalue' );
		$logger->notice ( ' id = ' . $id . ' newvalue = ' . $newvalue . ' oldvalue = ' . $oldvalue );
		
		$params = array ();
		$params ['name'] = $newvalue;
		$params ['subj_area_id'] = $id;
		
		$db_obj = new DatabaseObject_Subj ( $this->db );
		$res_data = $db_obj->doUpdate ( $params, false );
		$isSuccess = (count ( $res_data ) > 0);
		
		$res = array ('success' => $isSuccess, 'message' => "update/insert", 'data' => $res_data );
		$this->sendJson ( $res );
	}
	
	public function deleteAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'deleteAction' );
		$this->_helper->viewRenderer->setNoRender ();
		$db_obj = new DatabaseObject_Subj ( $this->db );
		
		$request = $this->getRequest ();
		$id = $request->getPost ( 'id' );
		$logger->notice ( ' id = ' . $id );
		
		$row_data = $db_obj->doDelete ( $id );
		if (count ( $row_data ) <= 0) {
			$isSuccess = false;
			break;
		}
		array_push ( $res_data, $row_data );
	
	}
	
	public function moveAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'moveAction' );
		$request = $this->getRequest ();
		$id = $request->getPost ( 'nodeid' );
		$newparentid = $request->getPost ( 'newparentid' );
		$oldparentid = $request->getPost ( 'oldparentid' );
		
		//$id = $_POST['nodeid'];
		$logger->notice ( ' id = ' . $id . ' newparentid = ' . $newparentid . ' oldparentid = ' . $oldparentid );
	
	}

}

?>
