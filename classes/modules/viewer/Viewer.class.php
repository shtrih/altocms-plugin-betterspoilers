<?php

class PluginBetterspoilers_ModuleViewer extends PluginBetterspoilers_Inherit_ModuleViewer {

    public function getAssignedAjax($sName) {
        return isset($this->aVarsAjax[$sName]) ? $this->aVarsAjax[$sName] : null;
    }
}