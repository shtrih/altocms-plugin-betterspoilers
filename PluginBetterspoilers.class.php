<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginBetterspoilers extends Plugin {

    // Объявление делегирований (нужны для того, чтобы назначить свои экшны и шаблоны)
    public $aDelegates = array(
        /**
         * 'action' => array('ActionIndex'=>'_ActionSomepage'),
         * Замена экшна ActionIndex на ActionSomepage из папки плагина
         *
         * 'template' => array('index.tpl'=>'_my_plugin_index.tpl'),
         * Замена index.tpl из корня скина файлом /common/plugins/abcplugin/templates/skin/default/my_plugin_index.tpl
         *
         * 'template'=>array('actions/ActionIndex/index.tpl'=>'_actions/ActionTest/index.tpl'),
         * Замена index.tpl из скина из папки actions/ActionIndex/ файлом /common/plugins/abcplugin/templates/skin/default/actions/ActionTest/index.tpl
         */
        'template' => array(
            'editors/editor.markitup.tpl' => '_editors/editor.markitup.tpl',
            'editors/editor.tinymce.tpl' => '_editors/editor.tinymce.tpl',
            'tpls/snippets/snippet.spoiler.tpl' => '_tpls/snippets/snippet.spoiler.tpl',
        )
    );

    protected $aInherits = array(
        'modules' => array(
            'ModuleText',
            'ModuleViewer',
        ),
        'actions' => array(
        ),
    );

    // Активация плагина
    public function Activate() {
        return true;
    }

    // Деактивация плагина
    public function Deactivate() {
        return true;
    }

    // Инициализация плагина
    public function Init() {
        $sTemplateDir = Plugin::GetTemplateDir(__CLASS__);
        E::ModuleViewer()->AppendScript($sTemplateDir . "assets/js/betterspoilers.js");
        E::ModuleViewer()->AppendStyle($sTemplateDir . "assets/css/betterspoilers.css");
    }
}
