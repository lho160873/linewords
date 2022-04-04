<?php
    class AccountController extends CustomControllerAction
    {
        public function init()
        {
            parent::init();
            $this->breadcrumbs->addStep('Авторизация', $this->getUrl(null, 'account'));
        }

        public function indexAction()
        {
            // nothing to do here, index.tpl will be displayed
        }

        public function registerAction()
        {
           $logger = Zend_Registry::get ( 'logger' );
	         $logger->notice ( 'registerAction' );
            $request = $this->getRequest();

            $fp = new FormProcessor_UserRegistration($this->db);
            $validate = $request->isXmlHttpRequest();

            if ($request->isPost()) {
                if ($validate) {
                    $fp->validateOnly(true);
                    $fp->process($request);
                }
                else if ($fp->process($request)) {
                    $session = new Zend_Session_Namespace('registration');
                    $session->developer_id = $fp->user->getId();
                    $this->_redirect($this->getUrl('registercomplete'));
                }
            }

            if ($validate) {
                $json = array(
                    'errors' => $fp->getErrors()
                );
                $this->sendJson($json);
            }
            else {
                $this->breadcrumbs->addStep('Новый');
                $this->view->fp = $fp;
            }
        }

        public function registercompleteAction()
        {
            // retrieve the same session namespace used in register
            $session = new Zend_Session_Namespace('registration');

            // load the user record based on the stored user ID
            $user = new DatabaseObject_User($this->db);
            if (!$user->load($session->developer_id)) {
                $this->_forward('register');
                return;
            }

            $this->breadcrumbs->addStep('Регистарция',
                                        $this->getUrl('register'));
            $this->breadcrumbs->addStep('Account Created');
            $this->view->user = $user;
        }

        public function loginAction()
        {

           $logger = Zend_Registry::get ( 'logger' );
	         $logger->notice ( 'loginAction' );
            // если пользователь уже вошел, отправить его на личную страницу
            $auth = Zend_Auth::getInstance();

            if ($auth->hasIdentity())
                $this->_redirect($this->getUrl());

            $request = $this->getRequest();
            // определение страницы, которую изначально запрашивал пользователь
            $redirect = $request->getPost('redirect');
            if (strlen($redirect) == 0)
                $redirect = $request->getServer('REQUEST_URI');

            if (strlen($redirect) == 0)
                $redirect = $this->getUrl();

            
            //if ($auth->hasIdentity())
            //    $this->_redirect($redirect);
                
             // инициализация сообщений об ошибках
            $errors = array();
             // обработка входа, если запрос сделан путем отправления формы
            if ($request->isPost()) {
                 // получение данных из формы и их проверка
                $fio = iconv('WINDOWS-1251','UTF-8', $request->getPost('fio'));
                //$fio =  $request->getPost('fio');
                if (strlen($fio) == 0)
                    $errors['fio'] = 'Required field must not be blank';

                if (count($errors) == 0) {

                    // настройка параметров адаптера аутентификации
                    $adapter = new Zend_Auth_Adapter_DbTable($this->db,
                                                             'developer',
                                                             'fio','fio');
                    $adapter->setIdentity($fio);
                    $adapter->setCredential($fio);
                    
                    //$adapter->setCredential($password);
                     // попытка аутентификации пользователя
                    $result = $auth->authenticate($adapter);
                    if ($result->isValid()) {
                        $user = new DatabaseObject_User($this->db);
                        $user->load($adapter->getResultRowObject()->developer_id);
                         // регистрация попытки входа в журнале
                        $user->loginSuccess();
                         // запись личных данных пользователя в объект сеанса
                        $identity = $user->createAuthIdentity();
                        $auth->getStorage()->write($identity);
                        // перенаправления пользователя на запрошенную им страницу
                        $this->_redirect($redirect);
                    }
                    // регистрация неудачной попытки входа в журнале
                    DatabaseObject_User::LoginFailure($fio,
                                                      $result->getCode());
                    $errors['fio'] = 'Ошибка';
                }
            }

            $this->breadcrumbs->addStep('Вход');
            $this->view->errors = $errors;
            $this->view->redirect = $redirect;
        } 
        


        public function logoutAction()
        {
            Zend_Auth::getInstance()->clearIdentity();
            //$this->_redirect($this->getUrl('login'));
            $this->_redirect($this->getUrl(null,'index'));
        }

       
        public function detailsAction()
        {
            $auth = Zend_Auth::getInstance();

            $fp = new FormProcessor_UserDetails($this->db,
                                                $auth->getIdentity()->developer_id);

            if ($this->getRequest()->isPost()) {
                if ($fp->process($this->getRequest())) {
                    $auth->getStorage()->write($fp->user->createAuthIdentity());
                    $this->_redirect($this->getUrl('detailscomplete'));
                }
            }

            $this->breadcrumbs->addStep('Your Account Details');
            $this->view->fp = $fp;
        }

        public function detailscompleteAction()
        {
            $user = new DatabaseObject_User($this->db);
            $user->load(Zend_Auth::getInstance()->getIdentity()->developer_id);

            $this->breadcrumbs->addStep('Your Account Details',
                                        $this->getUrl('details'));
            $this->breadcrumbs->addStep('Details Updated');
            $this->view->user = $user;
        }
    }
?>
