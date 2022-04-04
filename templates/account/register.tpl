{include file='header.tpl' section='register'}

<form method="post"
      action="{geturl action='register'}"
      id="registration-form">

    <fieldset>
        <legend>Регистрация</legend>
        <div class="error"{if !$fp->hasError()} style="display: none"{/if}>
            An error has occurred in the form below. Please check
            the highlighted fields and re-submit the form.
        </div>

        <div class="row" id="form_username_container">
            <label for="form_username">ФИО:</label>
            <input type="text" id="form_username"
                   name="fio" value="{$fp->fio|escape}" />
            {include file='lib/error.tpl' error=$fp->getError('fio')}
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

{include file='footer.tpl'}
