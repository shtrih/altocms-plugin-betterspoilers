{strip}
<div class="betterspoiler">
    <input class="js-no-jq" type="checkbox" tabindex="-1" />
    <div class="btrsplr-box">
        <span class="btrsplr-trigger">
            <span>
                {if $aParams.title}
                    {$aParams.title}
                {else}
                    {$aLang.plugin.betterspoilers['trigger-text']}
                {/if}
            </span>
        </span>
        <div class="btrsplr-text">
            <span>{$aParams.snippet_text}</span>
        </div>
    </div>
    <a title="{$aLang.plugin.betterspoilers['close-link-title']}" href="#"></a>
</div>
{/strip}
