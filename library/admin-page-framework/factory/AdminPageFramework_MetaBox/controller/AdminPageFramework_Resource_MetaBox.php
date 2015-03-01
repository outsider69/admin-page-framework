<?php
/**
 Admin Page Framework v3.5.4b06 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class AdminPageFramework_Resource_MetaBox extends AdminPageFramework_Resource_Base {
    public function _enqueueStyles($aSRCs, $aPostTypes = array(), $aCustomArgs = array()) {
        $_aHandleIDs = array();
        foreach (( array )$aSRCs as $_sSRC) {
            $_aHandleIDs[] = $this->_enqueueStyle($_sSRC, $aPostTypes, $aCustomArgs);
        }
        return $_aHandleIDs;
    }
    public function _enqueueStyle($sSRC, $aPostTypes = array(), $aCustomArgs = array()) {
        $sSRC = trim($sSRC);
        if (empty($sSRC)) {
            return '';
        }
        $sSRC = $this->oUtil->resolveSRC($sSRC);
        $_sSRCHash = md5($sSRC);
        if (isset($this->oProp->aEnqueuingStyles[$_sSRCHash])) {
            return '';
        }
        $this->oProp->aEnqueuingStyles[$_sSRCHash] = $this->oUtil->uniteArrays(( array )$aCustomArgs, array('sSRC' => $sSRC, 'aPostTypes' => empty($aPostTypes) ? $this->oProp->aPostTypes : $aPostTypes, 'sType' => 'style', 'handle_id' => 'style_' . $this->oProp->sClassName . '_' . (++$this->oProp->iEnqueuedStyleIndex),), self::$_aStructure_EnqueuingResources);
        $this->oProp->aResourceAttributes[$this->oProp->aEnqueuingStyles[$_sSRCHash]['handle_id']] = $this->oProp->aEnqueuingStyles[$_sSRCHash]['attributes'];
        return $this->oProp->aEnqueuingStyles[$_sSRCHash]['handle_id'];
    }
    public function _enqueueScripts($aSRCs, $aPostTypes = array(), $aCustomArgs = array()) {
        $_aHandleIDs = array();
        foreach (( array )$aSRCs as $_sSRC) {
            $_aHandleIDs[] = $this->_enqueueScript($_sSRC, $aPostTypes, $aCustomArgs);
        }
        return $_aHandleIDs;
    }
    public function _enqueueScript($sSRC, $aPostTypes = array(), $aCustomArgs = array()) {
        $sSRC = trim($sSRC);
        if (empty($sSRC)) {
            return '';
        }
        $sSRC = $this->oUtil->resolveSRC($sSRC);
        $_sSRCHash = md5($sSRC);
        if (isset($this->oProp->aEnqueuingScripts[$_sSRCHash])) {
            return '';
        }
        $this->oProp->aEnqueuingScripts[$_sSRCHash] = $this->oUtil->uniteArrays(( array )$aCustomArgs, array('sSRC' => $sSRC, 'aPostTypes' => empty($aPostTypes) ? $this->oProp->aPostTypes : $aPostTypes, 'sType' => 'script', 'handle_id' => 'script_' . $this->oProp->sClassName . '_' . (++$this->oProp->iEnqueuedScriptIndex),), self::$_aStructure_EnqueuingResources);
        $this->oProp->aResourceAttributes[$this->oProp->aEnqueuingScripts[$_sSRCHash]['handle_id']] = $this->oProp->aEnqueuingScripts[$_sSRCHash]['attributes'];
        return $this->oProp->aEnqueuingScripts[$_sSRCHash]['handle_id'];
    }
    public function _forceToEnqueueStyle($sSRC, $aCustomArgs = array()) {
        return $this->_enqueueStyle($sSRC, array(), $aCustomArgs);
    }
    public function _forceToEnqueueScript($sSRC, $aCustomArgs = array()) {
        return $this->_enqueueScript($sSRC, array(), $aCustomArgs);
    }
    protected function _enqueueSRCByConditoin($aEnqueueItem) {
        $_sCurrentPostType = isset($_GET['post_type']) ? $_GET['post_type'] : (isset($GLOBALS['typenow']) ? $GLOBALS['typenow'] : null);
        if (in_array($_sCurrentPostType, $aEnqueueItem['aPostTypes'])) {
            return $this->_enqueueSRC($aEnqueueItem);
        }
    }
}