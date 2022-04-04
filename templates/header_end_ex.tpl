    </head>
    <body>
         <div id="header">
            <img src="/images/logo-print.gif" alt="" />
        </div>
 
        <div id="nav">
        <ul>
               <li{if $section == 'home'} class="active"{/if}>
                    <a href="{geturl controller='index'}">Главная</a>
                </li>
                <li{if $section == 'developer'} class="active"{/if}>
                    <a href="{geturl controller='developer'}">Разработчики</a>
                </li>
                <li{if $section == 'definition'} class="active"{/if}>
                    <a href="{geturl controller='definition'}">Понятия</a>
                </li>
                 <li{if $section == 'document'} class="active"{/if}>
                    <a href="{geturl controller='document'}">Работа с документом</a>
                </li>
                 <li{if $section == 'subj'} class="active"{/if}>
                    <a href="{geturl controller='subj'}">Предметные области</a>
                </li>
                 <li{if $section == 'graph'} class="active"{/if}>
                    <a href="{geturl controller='graph'}">Сеть</a>
                </li>
                <!--
                {if $authenticated}
                    <li{if $section == 'account'} class="active"{/if}>
                        <a href="{geturl controller='account'}">Авторизация</a>
                    </li>
                    <li><a href="{geturl controller='account' action='logout'}">Выйти</a></li>
                {else}
                    <li{if $section == 'register'} class="active"{/if}>
                        <a href="{geturl controller='account' action='register'}">Регистарция</a>
                    </li>
                    <li{if $section == 'login'} class="active"{/if}>
                        <a href="{geturl controller='account' action='login'}">Войти</a>
                    </li>
                {/if}-->
            </ul>
        </div>

        <div id="content-container" class="column">
            <div id="content">
                <div id="breadcrumbs">
                    {breadcrumbs trail=$breadcrumbs->getTrail() separator=' &raquo; '}
                </div>

                
