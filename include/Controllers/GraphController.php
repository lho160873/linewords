<?php

class GraphController extends CustomControllerAction {
	
	public function indexAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'indexAction' );
		$this->breadcrumbs->addStep ( 'Сеть', $this->getUrl ( null, 'graph' ) );
		$fp = 'test';
	$this->view->fp = $fp;
	}
	public function loadAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'loadAction' );
		$this->_helper->viewRenderer->setNoRender ();
		
		//$rows = ("1","2","3");
		/*$res = array(
            'success'   => true,
            'message'   => "Loaded data",
            'data'      => $rows
        );*/
		$json = array (array ('name' => "name1", 'id' => "1" ), array ('name' => "name2", 'id' => "2" ), array ('name' => "name3", 'id' => "3" ) );
		//$json = array('name' => "name2",'id' => "2");
		//echo "1";Zend_Json::encode($json);          
		// echo '({"id":"1", "name":"name1"},{"id":"2", "name":"name2"})';
		
		
		$this->sendJson ( $json );
	}
	
	public function viewAction() {
		$logger = Zend_Registry::get ( 'logger' );
		$logger->notice ( 'viewAction' );
		$this->_helper->viewRenderer->setNoRender ();
		
    $subj_area_id = 23;

		
		$db_obj = new DatabaseObject_Graph ( $this->db );
		
		$defines_good = $db_obj->loadDefinitonGood($subj_area_id);
	  $defines_bad = $db_obj->loadDefinitonBad($subj_area_id);
		$connectons = $db_obj->loadConnections($subj_area_id);
		
		$res = array(
            'success'   => true,
            'message'   => "Loaded data",
            'data'      => $defines_good,
            'data_1'      => $defines_bad,
            'connections'      => $connectons
        );
      $this->sendJson($res);
		
		//$this->sendJson ( $tree );
	
	}
}

?>
