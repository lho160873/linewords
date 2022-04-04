<?php /* Smarty version 2.6.18, created on 2011-04-12 13:57:14
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'geturl', 'header.tpl', 22, false),array('function', 'breadcrumbs', 'header.tpl', 60, false),)), $this); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Сеть знаний</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />-->
         
         <link rel="stylesheet" type="text/css" href="/js/ext-3.3.1/resources/css/ext-all.css"/>
        <link rel="stylesheet" type="text/css" href="/js/ext/shared/icons/silk.css" />
         <link rel="stylesheet" href="/css/styles.css" type="text/css" media="all" />
    </head>
    <body>
         <div id="header">
            <img src="/images/logo-print.gif" alt="" />
        </div>
 
        <div id="nav">
        <ul>
               <li<?php if ($this->_tpl_vars['section'] == 'home'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'index'), $this);?>
">Главная</a>
                </li>
                <li<?php if ($this->_tpl_vars['section'] == 'developer'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'developer'), $this);?>
">Разработчики</a>
                </li>
                <li<?php if ($this->_tpl_vars['section'] == 'definition'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'definition'), $this);?>
">Понятия</a>
                </li>
                 <li<?php if ($this->_tpl_vars['section'] == 'document'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'document'), $this);?>
">Работа с документом</a>
                </li>
                 <li<?php if ($this->_tpl_vars['section'] == 'subj'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'subj'), $this);?>
">Предметные области</a>
                </li>
                 <li<?php if ($this->_tpl_vars['section'] == 'graph'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'graph'), $this);?>
">Сеть</a>
                </li>

                <!--
                <?php if ($this->_tpl_vars['authenticated']): ?>
                    <li<?php if ($this->_tpl_vars['section'] == 'account'): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo smarty_function_geturl(array('controller' => 'account'), $this);?>
">Авторизация</a>
                    </li>
                    <li><a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'logout'), $this);?>
">Выйти</a></li>
                <?php else: ?>
                    <li<?php if ($this->_tpl_vars['section'] == 'register'): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'register'), $this);?>
">Регистарция</a>
                    </li>
                    <li<?php if ($this->_tpl_vars['section'] == 'login'): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'login'), $this);?>
">Войти</a>
                    </li>
                <?php endif; ?>-->
            </ul>
        </div>

        <div id="content-container" class="column">
            <div id="content">
                <div id="breadcrumbs">
                    <?php echo smarty_function_breadcrumbs(array('trail' => $this->_tpl_vars['breadcrumbs']->getTrail(),'separator' => ' &raquo; '), $this);?>

                </div>
