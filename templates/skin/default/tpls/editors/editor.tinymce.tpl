{if !$sSettingsTinymce}
    {assign var="sSettingsTinymce" value="ls.settings.getTinymce()"}
{/if}
<script type="text/javascript">
    $(function(){
        ls.lang.load({lang_load name="panel_spoiler_title,panel_spoiler_text,panel_user,panel_user_login,panel_user_promt,panel_photoset,panel_spoiler,panel_photoset_from,panel_photoset_to,panel_photoset_align,panel_photoset_align_left,panel_photoset_align_right,panel_photoset_align_both,panel_photoset_topic"});
    });
    ls.lang.load({lang_load name="panel_title_h4,panel_title_h5,panel_title_h6"});

    var settings = {$sSettingsTinymce};

    {if false === strrpos($sSettingsTinymce, 'Comment')}
        {$bCommentSettings = false}
    {else}
        {$bCommentSettings = true}
    {/if}
    //{hook run="tinymce_before_init" bCommentSettings=$bCommentSettings}


    if (!tinymce) {
        ls.loadAssetScript('tinymce_4', function(){
            $(function(){
                tinymce.init(settings);
            });
        });
    } else {
        $(function(){
            tinymce.init(settings);
        });
    }

    {if Config::Get('view.float_editor')}
    $(function(){
        ls.editor.float({
            topStep: {if Config::Get('view.fix_menu')}60{else}0{/if},
            dif: 0,
            textareaClass: '.js-editor-wysiwyg',
            editorClass: '.mce-tinymce',
            headerClass: '.mce-toolbar-grp'
        });
    });
    {/if}
</script>
