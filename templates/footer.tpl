            </div>
        </div>

        

        <div id="left-container" class="column">
            <div class="box">
                {if $authenticated}
                    �� ����� ��� ������
                    <font color=#1F4D8B><b>
                    {$test}{$identity->fio|escape}</b></font>
                    <br><a href="{geturl controller='account' action='logout'}">�����</a>
                {else}
                    <font color=red>�� �� �����.</font>
                    <!--<a href="{geturl controller='account' action='login'}">�����</a> -->
  
                    <form method="post" action="{geturl controller='account' action='login'}">
    <!--<fieldset>-->
        <input type="hidden" name="redirect" value="{$redirect|escape}" />

       <!-- <legend>�����������</legend>-->

        <div class="row_l" id="form_username_container">
            <label for="form_username">���:</label>
            <input type="text" id="form_username"
                   name="fio" value="{$fio|escape}" />
            <!--{include file='lib/error.tpl' error=$errors.fio}-->
        </div>


        <div class="submit_l">
            <input type="submit" value="�����" />
        </div>

    <!--</fieldset>-->
</form>

                    
                    <a href="{geturl controller='account' action='register'}">�����������</a>
                {/if}
            </div>
        </div>
        
        <div id="right-container" class="column">
            <div class="box">
                
            </div>
        </div>

        <div id="footer">
            "���� ������" - ��� "����� ������" 2011 �.
        </div>
    </body>
</html>
