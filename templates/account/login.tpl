{include file='header.tpl' section='login'}

<form method="post" action="{geturl action='login'}">
    <fieldset>
        <input type="hidden" name="redirect" value="{$redirect|escape}" />

        <legend>�����������</legend>

        <div class="row" id="form_username_container">
            <label for="form_username">���:</label>
            <input type="text" id="form_username"
                   name="fio" value="{$fio|escape}" />
            {include file='lib/error.tpl' error=$errors.fio}
        </div>

        

        <div class="submit">
            <input type="submit" value="�����" />
        </div>

    </fieldset>
</form>

{include file='footer.tpl'}
