<?php /* Smarty version 2.6.18, created on 2011-04-12 13:45:50
         compiled from header_end_ex.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'geturl', 'header_end_ex.tpl', 10, false),array('function', 'breadcrumbs', 'header_end_ex.tpl', 47, false),)), $this); ?>
    </head>
    <body>
         <div id="header">
            <img src="/images/logo-print.gif" alt="" />
        </div>
 
        <div id="nav">
        <ul>
               <li<?php if ($this->_tpl_vars['section'] == 'home'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'index'), $this);?>
">�������</a>
                </li>
                <li<?php if ($this->_tpl_vars['section'] == 'developer'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'developer'), $this);?>
">������������</a>
                </li>
                <li<?php if ($this->_tpl_vars['section'] == 'definition'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'definition'), $this);?>
">�������</a>
                </li>
                 <li<?php if ($this->_tpl_vars['section'] == 'document'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'document'), $this);?>
">������ � ����������</a>
                </li>
                 <li<?php if ($this->_tpl_vars['section'] == 'subj'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'subj'), $this);?>
">���������� �������</a>
                </li>
                 <li<?php if ($this->_tpl_vars['section'] == 'graph'): ?> class="active"<?php endif; ?>>
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'graph'), $this);?>
">����</a>
                </li>
                <!--
                <?php if ($this->_tpl_vars['authenticated']): ?>
                    <li<?php if ($this->_tpl_vars['section'] == 'account'): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo smarty_function_geturl(array('controller' => 'account'), $this);?>
">�����������</a>
                    </li>
                    <li><a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'logout'), $this);?>
">�����</a></li>
                <?php else: ?>
                    <li<?php if ($this->_tpl_vars['section'] == 'register'): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'register'), $this);?>
">�����������</a>
                    </li>
                    <li<?php if ($this->_tpl_vars['section'] == 'login'): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'login'), $this);?>
">�����</a>
                    </li>
                <?php endif; ?>-->
            </ul>
        </div>

        <div id="content-container" class="column">
            <div id="content">
                <div id="breadcrumbs">
                    <?php echo smarty_function_breadcrumbs(array('trail' => $this->_tpl_vars['breadcrumbs']->getTrail(),'separator' => ' &raquo; '), $this);?>

                </div>

                