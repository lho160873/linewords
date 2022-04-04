<?php

class DocumentController extends CustomControllerAction {

 	public function indexAction() {
	 $logger = Zend_Registry::get ( 'logger' );
	 $logger->notice ( 'indexAction' );
	 $this->breadcrumbs->addStep('Работа с документом', $this->getUrl(null, 'document'));
	}
}	

?>
