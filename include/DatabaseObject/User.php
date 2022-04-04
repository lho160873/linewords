<?php
    class DatabaseObject_User extends DatabaseObject
    {
        static $userTypes = array('member'        => 'Member',
                                  'administrator' => 'Administrator');

        //public $profile = null;

        public function __construct($db)
        {
            parent::__construct($db, 'developer', 'developer_id');

            $this->add('fio');

           // $this->profile = new Profile_User($db);
        }

        protected function preInsert()
        {
             return true;
        }

        protected function postLoad()
        {
            //$this->profile->setUserId($this->getId());
            //$this->profile->load();
        }

        protected function postInsert()
        {
            //$this->profile->setUserId($this->getId());
            //$this->profile->save(false);

            //$this->sendEmail('user-register.tpl');
            return true;
        }

        protected function postUpdate()
        {
            //$this->profile->save(false);
            return true;
        }

        protected function preDelete()
        {
            //$this->profile->delete();
            return true;
        }


        public function createAuthIdentity()
        {
            $identity = new stdClass;
            $identity->developer_id = $this->getId();
            
            $identity->fio = iconv('UTF-8', 'WINDOWS-1251', $this->fio);
            //$identity->fio =  $this->fio;
            $identity->user_type = 'member';//$this->user_type;

            return $identity;
        }

        public function loginSuccess()
        {
            $this->ts_last_login = time();
            $this->save();

            $message = sprintf('Successful login attempt from %s fio %s',
                               $_SERVER['REMOTE_ADDR'],
                               $this->fio);

            $logger = Zend_Registry::get('logger');
            $logger->notice($message);
        }
        static public function LoginFailure($fio, $code = '')
        {
            switch ($code) {
                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    $reason = 'Unknown fio';
                    break;
                case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
                    $reason = 'Multiple developer found with this fio';
                    break;
                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    $reason = 'Invalid password';
                    break;
                default:
                    $reason = '';
            }

            $message = sprintf('Failed login attempt from %s user %s',
                               $_SERVER['REMOTE_ADDR'],
                               $fio);

            if (strlen($reason) > 0)
                $message .= sprintf(' (%s)', $reason);

            $logger = Zend_Registry::get('logger');
            $logger->warn($message);
        }

       
        public function confirmNewPassword($key)
        {
           /*
            // check that valid password reset data is set
            if (!isset($this->profile->new_password)
                || !isset($this->profile->new_password_ts)
                || !isset($this->profile->new_password_key)) {

                return false;
            }

            // check if the password is being confirm within a day
            if (time() - $this->profile->new_password_ts > 86400)
                return false;

            // check that the key is correct
            if ($this->profile->new_password_key != $key)
                return false;

            // everything is valid, now update the account to use the new password

            // bypass the local setter as new_password is already an md5
            parent::__set('password', $this->profile->new_password);

            unset($this->profile->new_password);
            unset($this->profile->new_password_ts);
            unset($this->profile->new_password_key);

            // finally, save the updated user record and the updated profile
            */
            return $this->save();
        }

        public function usernameExists($fio)
        {
            $query = sprintf('select count(*) from %s where fio = ?',
                             $this->_table);

            $result = $this->_db->fetchOne($query, $fio);

            return $result > 0;
        }

        static public function IsValidUsername($fio)
        {
            //$validator = new Zend_Validate_Alnum();
            //return $validator->isValid($fio);
            return true;
        }

        public function __set($name, $value)
        {
            switch ($name) {
                case 'password':
                    $value = md5($value);
                    break;

                case 'user_type':
                    if (!array_key_exists($value, self::$userTypes))
                        $value = 'member';
                    break;
                    
               // case 'fio':
               //     $value = iconv('WINDOWS-1251', 'UTF-8', $value );
               //     break;    
            }

            return parent::__set($name, $value);
        }
    }
?>

