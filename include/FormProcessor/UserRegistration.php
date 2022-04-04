<?php
    class FormProcessor_UserRegistration extends FormProcessor
    {
        protected $db = null;
        public $user = null;
        protected $_validateOnly = false;

        public function __construct($db)
        {
            parent::__construct();
            $this->db = $db;
            $this->user = new DatabaseObject_User($db);
            $this->user->type = 'member';
        }

        public function validateOnly($flag)
        {
            $this->_validateOnly = (bool) $flag;
        }

        public function process(Zend_Controller_Request_Abstract $request)
        {
          $logger = Zend_Registry::get ( 'logger' );
	         $logger->notice ( 'process' );
            // validate the fio
            $this->fio = trim($request->getPost('fio'));

            if (strlen($this->fio) == 0)
                $this->addError('fio', 'Please enter a fio');
            else if (!DatabaseObject_User::IsValidUsername($this->fio))
                $this->addError('fio', 'Please enter a valid fio');
            else if ($this->user->usernameExists($this->fio))
                $this->addError('fio', 'The selected fio already exists');
            else
            {
                 $this->user->fio = $this->fio;
                 $logger->notice('fio = '.$this->user->fio);
                
}

            // if no errors have occurred, save the user
            if (!$this->_validateOnly && !$this->hasError()) {
                $this->user->save();
                unset($session->phrase);
            }

            // return true if no errors have occurred
            return !$this->hasError();
        }
    }
?>
