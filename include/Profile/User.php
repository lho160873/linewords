<?php
    class Profile_User extends Profile
    {
        public function __construct($db, $developer_id = null)
        {
            parent::__construct($db, 'users_profile');

            if ($developer_id > 0)
                $this->setUserId($developer_id);
        }

        public function setUserId($developer_id)
        {
            $filters = array('developer_id' => (int) $developer_id);
            $this->_filters = $filters;
        }
    }
?>
