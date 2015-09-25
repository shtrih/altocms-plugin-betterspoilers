{if $bCommentSettings}
    {$iIndex1 = 4}
    {$iIndex2 = 5}
{else}
    {$iIndex1 = 10}
    {$iIndex2 = 11}
{/if}

settings.markupSet.splice({$iIndex1}, 0, {literal}{ name: 'Скрытый текст', className: 'editor-hide', openWith: '<alto:hide>', closeWith: '</alto:hide>' }{/literal});
settings.markupSet.splice({$iIndex2}, 0, {literal}{
    name: ls.lang.get('panel_spoiler'),
    className:'editor-hidespoiler',
    replaceWith: function(m) {
        return '<alto:spoiler title="Нажмите для просмотра содержимого">\n' +(m.selectionOuter || m.selection)+'\n</alto:spoiler>';
    }
}{/literal});
//