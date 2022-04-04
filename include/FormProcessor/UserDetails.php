<?php
    class FormProcessor_UserDetails extends FormProcessor
    {
        protected $db = null;
        public $user = null;

        public function __construct($db, $developer_id)
        {
            parent::__construct();

            $this->db = $db;
            $this->user = new DatabaseObject_User($db);
            $this->user->load($developer_id);

           
        }

        public function process(Zend_Controller_Request_Abstract $request)
        {
            // validate the user's name

            
            // if no errors have occurred, save the user
            if (!$this->hasError()) {
                $this->user->save();
            }

            // return true if no errors have occurred
            return !$this->hasError();
        }
    }
?>
