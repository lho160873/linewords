<?php /* Smarty version 2.6.18, created on 2011-04-11 14:45:19
         compiled from account/register.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'geturl', 'account/register.tpl', 4, false),array('modifier', 'escape', 'account/register.tpl', 17, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array('section' => 'register')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form method="post"
      action="<?php echo smarty_function_geturl(array('action' => 'register'), $this);?>
"
      id="registration-form">

    <fieldset>
        <legend>Регистрация</legend>
        <div class="error"<?php if (! $this->_tpl_vars['fp']->hasError()): ?> style="display: none"<?php endif; ?>>
            An error has occurred in the form below. Please check
            the highlighted fields and re-submit the form.
        </div>

        <div class="row" id="form_username_container">
            <label for="form_username">ФИО:</label>
            <input type="text" id="form_username"
                   name="fio" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['fp']->fio)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'lib/error.tpl', 'smarty_include_vars' => array('error' => $this->_tpl_vars['fp']->getError('fio'))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>

       
        <div class="submit">
            <input type="submit" value="Зарегистрировать" />
        </div>
    </fieldset>
</form>

<script type="text/javascript" src="/js/UserRegistrationForm.class.js"></script>
<script type="text/javascript">
    new UserRegistrationForm('registration-form');
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>