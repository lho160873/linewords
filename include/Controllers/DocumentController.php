<?php

class DocumentController extends CustomControllerAction {

 	public function indexAction() {
	 $logger = Zend_Registry::get ( 'logger' );
	 $logger->notice ( 'indexAction' );
	 $this->breadcrumbs->addStep('������ � ����������', $this->getUrl(null, 'document'));
	}
}	

?>
