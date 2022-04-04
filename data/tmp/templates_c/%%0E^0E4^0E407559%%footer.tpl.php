<?php /* Smarty version 2.6.18, created on 2011-04-19 16:06:30
         compiled from footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'footer.tpl', 11, false),array('function', 'geturl', 'footer.tpl', 12, false),)), $this); ?>
            </div>
        </div>

        

        <div id="left-container" class="column">
            <div class="box">
                <?php if ($this->_tpl_vars['authenticated']): ?>
                    Вы вошли под именем
                    <font color=#1F4D8B><b>
                    <?php echo $this->_tpl_vars['test']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['identity']->fio)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</b></font>
                    <br><a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'logout'), $this);?>
">Выйти</a>
                <?php else: ?>
                    <font color=red>Вы не вошли.</font>
                    <!--<a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'login'), $this);?>
">Войти</a> -->
  
                    <form method="post" action="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'login'), $this);?>
">
    <!--<fieldset>-->
        <input type="hidden" name="redirect" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['redirect'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />

       <!-- <legend>Авторизация</legend>-->

        <div class="row_l" id="form_username_container">
            <label for="form_username">ФИО:</label>
            <input type="text" id="form_username"
                   name="fio" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['fio'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
            <!--<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'lib/error.tpl', 'smarty_include_vars' => array('error' => $this->_tpl_vars['errors']['fio'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>-->
        </div>


        <div class="submit_l">
            <input type="submit" value="Войти" />
        </div>

    <!--</fieldset>-->
</form>

                    
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'register'), $this);?>
">Регистарция</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="right-container" class="column">
            <div class="box">
                
            </div>
        </div>

        <div id="footer">
            "Сеть знаний" - ЗАО "Фирма Пассат" 2011 г.
        </div>
    </body>
</html>