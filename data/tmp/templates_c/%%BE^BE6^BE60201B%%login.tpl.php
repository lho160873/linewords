<?php /* Smarty version 2.6.18, created on 2011-04-11 12:56:56
         compiled from account/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'geturl', 'account/login.tpl', 3, false),array('modifier', 'escape', 'account/login.tpl', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array('section' => 'login')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form method="post" action="<?php echo smarty_function_geturl(array('action' => 'login'), $this);?>
">
    <fieldset>
        <input type="hidden" name="redirect" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['redirect'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />

        <legend>Авторизация</legend>

        <div class="row" id="form_username_container">
            <label for="form_username">ФИО:</label>
            <input type="text" id="form_username"
                   name="fio" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['fio'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'lib/error.tpl', 'smarty_include_vars' => array('error' => $this->_tpl_vars['errors']['fio'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>

        

        <div class="submit">
            <input type="submit" value="Войти" />
        </div>

    </fieldset>
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>