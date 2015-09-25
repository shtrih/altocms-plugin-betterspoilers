<?php

class PluginBetterspoilers_HookBetterspoilers extends Hook {

    const TAG_SPOILER_OPEN = '<alto:spoiler';
    const TAG_SPOILER_CLOSE = '</alto:spoiler>';

    /**
     * Регистрация хуков
     */
    public function RegisterHook() {
        $this->AddHook('template_markitup_before_init', 'markitupBeforeInit');
        $this->AddHook('template_tinymce_before_init', 'timymceBeforeInit');
        $this->AddHook('snippet_spoiler', 'snippetSpoiler');
    }

    public function markitupBeforeInit($aParams) {
        return E::ModuleViewer()->Fetch('markitup_before_init.tpl', $aParams);
    }

    public function timymceBeforeInit($aParams) {
        return E::ModuleViewer()->Fetch('tinymce_before_init.tpl', $aParams);
    }

    public function snippetSpoiler($aParams) {
        $sText = $aParams['params']['target_text'];

        $aSpoilersPositions = $this->getSpoilersPositions($sText);
        if ($aSpoilersPositions) {
            do {
                $iEndPos = reset($aSpoilersPositions);
                $iStartPos = key($aSpoilersPositions);
                $iLength = ($iEndPos - $iStartPos + 15 /* strlen(self::TAG_SPOILER_CLOSE) */);
                preg_match(
                    '~^' . preg_quote(self::TAG_SPOILER_OPEN, '~')
                        . '(?:\s*(\w+)=(?:["]([^"]*)["]|[\']([^\']*)[\'])\s*)*>(.*?)'
                        . preg_quote(self::TAG_SPOILER_CLOSE, '~')
                    . '$~is',
                    substr($sText, $iStartPos, $iLength),
                    $aMatches
                );
                list( , $sAttr, $sValue1, $sValue2, $sContent) = $aMatches;

                $sSpoilerParsed = E::ModuleViewer()->GetLocalViewer()->Fetch(
                    'tpls/snippets/snippet.spoiler.tpl',
                    array(
                        'aParams' => array(
                            'title' => ('title' == $sAttr)
                                ? ($sValue1 ?: $sValue2)
                                : ''
                            ,
                            'snippet_text' => $sContent,
                        )
                    )
                );
                $sText = substr_replace($sText, $sSpoilerParsed, $iStartPos, $iLength);
            } while($aSpoilersPositions = $this->getSpoilersPositions($sText));
        }
        $aParams['result'] = $sText;

        return $aParams['result'];
    }

    protected function getSpoilersPositions($sText) {
        $aSpoilersPos = array(
            'start' => array(),
            'end' => array(),
        );
        $iOffsetStart = 0;
        $iOffsetEnd = 0;
        do {
            $iPosStart = strpos($sText, self::TAG_SPOILER_OPEN, $iOffsetStart);
            if (false !== $iPosStart) {
                $aSpoilersPos['start'][] = $iPosStart;
                $iOffsetStart = $iPosStart + 13 /* strlen(self::TAG_SPOILER_OPEN)*/;
            }

            $iPosEnd = strpos($sText, self::TAG_SPOILER_CLOSE, $iOffsetEnd);
            if (false !== $iPosEnd) {
                $aSpoilersPos['end'][] = $iPosEnd;
                $iOffsetEnd = $iPosEnd + 15 /* strlen(self::TAG_SPOILER_CLOSE) */;
            }
        } while(false !== $iPosStart || false !== $iPosEnd);

        $aCandidatesSimple = $aCandidatesNested = array();
        foreach ($aSpoilersPos['start'] as $iStart) {
            foreach ($aSpoilersPos['end'] as $iEnd) {
                if ($iStart < $iEnd) {
                    $iMiddle = $this->getBetween($iStart, $iEnd, $aSpoilersPos['start']);
                    if (false === $iMiddle) {
                        // var_dump('между ' . $startpos . ' и ' . $endpos . ' ничего нет');
                        $aCandidatesSimple[$iStart][$iEnd] = $iEnd;
                    }
                    elseif (!isset($aCandidatesSimple[$iStart])) {
                        $aCandidatesNested[$iStart][$iEnd] = $iEnd;
                        // var_dump($middle . ' между ' . $startpos . ' и ' . $endpos);
                    }
                }
            }
        }

        $aResultSimple = $aResultNested = array();
        foreach ($aCandidatesSimple as $iPosStart => $aCandidates) {
            $aResultSimple[$iPosStart] = min($aCandidates);

            foreach ($aCandidatesNested as &$aCNested) {
                unset($aCNested[ $aResultSimple[$iPosStart] ]);
            }
        }

        foreach ($aCandidatesNested as $iPosStart => $aCNested) {
            $aResultNested[$iPosStart] = max($aCNested);
        }

        return $aResultSimple + $aResultNested;
    }

    private function getBetween($numstart, $numend, array $array) {
        foreach($array as $v) {
            if ($v > $numstart && $v < $numend) {
                return $v;
            }
        }

        return false;
    }
}
