    </head>
    <body>
         <div id="header">
            <img src="/images/logo-print.gif" alt="" />
        </div>
 
        <div id="nav">
        <ul>
               <li{if $section == 'home'} class="active"{/if}>
                    <a href="{geturl controller='index'}">�������</a>
                </li>
                <li{if $section == 'developer'} class="active"{/if}>
                    <a href="{geturl controller='developer'}">������������</a>
                </li>
                <li{if $section == 'definition'} class="active"{/if}>
                    <a href="{geturl controller='definition'}">�������</a>
                </li>
                 <li{if $section == 'document'} class="active"{/if}>
                    <a href="{geturl controller='document'}">������ � ����������</a>
                </li>
                 <li{if $section == 'subj'} class="active"{/if}>
                    <a href="{geturl controller='subj'}">���������� �������</a>
                </li>
                 <li{if $section == 'graph'} class="active"{/if}>
                    <a href="{geturl controller='graph'}">����</a>
                </li>
                <!--
                {if $authenticated}
                    <li{if $section == 'account'} class="active"{/if}>
                        <a href="{geturl controller='account'}">�����������</a>
                    </li>
                    <li><a href="{geturl controller='account' action='logout'}">�����</a></li>
                {else}
                    <li{if $section == 'register'} class="active"{/if}>
                        <a href="{geturl controller='account' action='register'}">�����������</a>
                    </li>
                    <li{if $section == 'login'} class="active"{/if}>
                        <a href="{geturl controller='account' action='login'}">�����</a>
                    </li>
                {/if}-->
            </ul>
        </div>

        <div id="content-container" class="column">
            <div id="content">
                <div id="breadcrumbs">
                    {breadcrumbs trail=$breadcrumbs->getTrail() separator=' &raquo; '}
                </div>

                
