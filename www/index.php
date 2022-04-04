<?php

    set_include_path('W:\home\linewords.pst\include;');

    require_once('Zend/Loader.php');
    Zend_Loader::registerAutoload();


    // Настройка конфигурации приложени\
    $config = new Zend_Config_Ini('../settings.ini', 'development');
    Zend_Registry::set('config', $config);


    // create the application logger
    $logger = new Zend_Log(new Zend_Log_Writer_Stream($config->logging->file));
    Zend_Registry::set('logger', $logger);


    //Установка соединения с базой данных и помещение его объекта в реестр.
    $params = array('host'     => $config->database->hostname,
                    'port'     => $config->database->port,
                    'dbname'   => $config->database->database,
                    'username'     => $config->database->username,
                    'password' => $config->database->password
                    );

    $db = Zend_Db::factory($config->database->type, $params);
    Zend_Registry::set('db', $db);


    // setup application authentication
    $auth = Zend_Auth::getInstance();
    $auth->setStorage(new Zend_Auth_Storage_Session());


    // Создание объекта controller контроллера 
    $controller = Zend_Controller_Front::getInstance();
    // Настройка controller контроллера, указание базового URL, правил маршрутизации 
    $controller->setControllerDirectory($config->paths->base .
                                        '/include/Controllers');
    $controller->registerPlugin(new CustomControllerAclManager($auth));

    
    // Инициализация объекта ViewRenderer
    // расширения view скриптов с помощью Action помошников
    $vr = new Zend_Controller_Action_Helper_ViewRenderer();
    $vr->setView(new Templater());
    // Настройка расширения макетов
    $vr->setViewSuffix('tpl');
    
    Zend_Controller_Action_HelperBroker::addHelper($vr);

    $controller->dispatch();
?>
