<?php 
/**
	Admin Page Framework v3.8.3b01 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AdminPageFramework_Form_View___Section_Base extends AdminPageFramework_Form_Base {
    public function isSectionsetVisible($aSectionset) {
        if (empty($aSectionset)) {
            return false;
        }
        return $this->callBack($this->aCallbacks['is_sectionset_visible'], array(true, $aSectionset));
    }
    public function isFieldsetVisible($aFieldset) {
        if (empty($aFieldset)) {
            return false;
        }
        return $this->callBack($this->aCallbacks['is_fieldset_visible'], array(true, $aFieldset));
    }
    public function getFieldsetOutput($aFieldset) {
        if (!$this->isFieldsetVisible($aFieldset)) {
            return '';
        }
        $_oFieldset = new AdminPageFramework_Form_View___Fieldset($aFieldset, $this->aSavedData, $this->aFieldErrors, $this->aFieldTypeDefinitions, $this->oMsg, $this->aCallbacks);
        $_sFieldOutput = $_oFieldset->get();
        return $_sFieldOutput;
    }
}
class AdminPageFramework_Form_View___SectionTitle extends AdminPageFramework_Form_View___Section_Base {
    public $aArguments = array('title' => null, 'tag' => null, 'section_index' => null, 'sectionset' => array(),);
    public $aFieldsets = array();
    public $aSavedData = array();
    public $aFieldErrors = array();
    public $aFieldTypeDefinitions = array();
    public $oMsg;
    public $aCallbacks = array('fieldset_output', 'is_fieldset_visible' => null,);
    public function __construct() {
        $_aParameters = func_get_args() + array($this->aArguments, $this->aFieldsets, $this->aSavedData, $this->aFieldErrors, $this->aFieldTypeDefinitions, $this->oMsg, $this->aCallbacks);
        $this->aArguments = $_aParameters[0] + $this->aArguments;
        $this->aFieldsets = $_aParameters[1];
        $this->aSavedData = $_aParameters[2];
        $this->aFieldErrors = $_aParameters[3];
        $this->aFieldTypeDefinitions = $_aParameters[4];
        $this->oMsg = $_aParameters[5];
        $this->aCallbacks = $_aParameters[6];
    }
    public function get() {
        $_sTitle = $this->_getSectionTitle($this->aArguments['title'], $this->aArguments['tag'], $this->aFieldsets, $this->aArguments['section_index'], $this->aFieldTypeDefinitions);
        return $_sTitle;
    }
    private function _getToolTip() {
        $_aSectionset = $this->aArguments['sectionset'];
        $_sSectionTitleTagID = str_replace('|', '_', $_aSectionset['_section_path']) . '_' . $this->aArguments['section_index'];
        $_oToolTip = new AdminPageFramework_Form_View___ToolTip($_aSectionset['tip'], $_sSectionTitleTagID);
        return $_oToolTip->get();
    }
    protected function _getSectionTitle($sTitle, $sTag, $aFieldsets, $iSectionIndex = null, $aFieldTypeDefinitions = array(), $aCollapsible = array()) {
        $_sFieldsetsInSectionTitle = $this->_getFieldsetsOutputInSectionTitleArea($aFieldsets, $iSectionIndex, $aFieldTypeDefinitions);
        return $_sFieldsetsInSectionTitle ? $_sFieldsetsInSectionTitle : "<{$sTag}>" . $this->_getCollapseButton($aCollapsible) . $sTitle . $this->_getToolTip() . "</{$sTag}>";
    }
    private function _getCollapseButton($aCollapsible) {
        $_sExpand = esc_attr($this->oMsg->get('click_to_expand'));
        $_sCollapse = esc_attr($this->oMsg->get('click_to_collapse'));
        return $this->getAOrB('button' === $this->getElement($aCollapsible, 'type', 'box'), "<span class='admin-page-framework-collapsible-button admin-page-framework-collapsible-button-expand' title='{$_sExpand}'>&#9658;</span>" . "<span class='admin-page-framework-collapsible-button admin-page-framework-collapsible-button-collapse' title='{$_sCollapse}'>&#9660;</span>", '');
    }
    private function _getFieldsetsOutputInSectionTitleArea(array $aFieldsets, $iSectionIndex, $aFieldTypeDefinitions) {
        $_aFieldsetsInSectionTitle = array();
        $_aFieldsetsInSectionTitle[] = $this->_getSectionTitleField($aFieldsets, $iSectionIndex, $aFieldTypeDefinitions);
        $_aFieldsetsInSectionTitle = array_merge($_aFieldsetsInSectionTitle, $this->_getFieldsetsInSectionTitleArea($aFieldsets, $iSectionIndex, $aFieldTypeDefinitions));
        $_sOutput = '';
        foreach ($_aFieldsetsInSectionTitle as $_aFieldset) {
            if (empty($_aFieldset)) {
                continue;
            }
            $_sOutput.= $this->getFieldsetOutput($_aFieldset);
        }
        return $_sOutput;
    }
    private function _getFieldsetsInSectionTitleArea(array $aFieldsets, $iSectionIndex, $aFieldTypeDefinitions) {
        $_aFieldsetsInSectionTitle = array();
        foreach ($aFieldsets as $_aFieldset) {
            if ('section_title' !== $_aFieldset['placement']) {
                continue;
            }
            $_oFieldsetOutputFormatter = new AdminPageFramework_Form_Model___Format_FieldsetOutput($_aFieldset, $iSectionIndex, $aFieldTypeDefinitions);
            $_aFieldsetsInSectionTitle[] = $_oFieldsetOutputFormatter->get();
        }
        return $_aFieldsetsInSectionTitle;
    }
    private function _getSectionTitleField(array $aFieldsets, $iSectionIndex, $aFieldTypeDefinitions) {
        foreach ($aFieldsets as $_aFieldset) {
            if ('section_title' !== $_aFieldset['type']) {
                continue;
            }
            $_oFieldsetOutputFormatter = new AdminPageFramework_Form_Model___Format_FieldsetOutput($_aFieldset, $iSectionIndex, $aFieldTypeDefinitions);
            return $_oFieldsetOutputFormatter->get();
        }
    }
}
class AdminPageFramework_Form_View___CollapsibleSectionTitle extends AdminPageFramework_Form_View___SectionTitle {
    public $aArguments = array('title' => null, 'tag' => null, 'section_index' => null, 'collapsible' => array(), 'container_type' => 'section', 'sectionset' => array(),);
    public $aFieldsets = array();
    public $aSavedData = array();
    public $aFieldErrors = array();
    public $aFieldTypeDefinitions = array();
    public $oMsg;
    public $aCallbacks = array('fieldset_output', 'is_fieldset_visible' => null,);
    public function get() {
        if (empty($this->aArguments['collapsible'])) {
            return '';
        }
        return $this->_getCollapsibleSectionTitleBlock($this->aArguments['collapsible'], $this->aArguments['container_type'], $this->aArguments['section_index']);
    }
    private function _getCollapsibleSectionTitleBlock(array $aCollapsible, $sContainer = 'sections', $iSectionIndex = null) {
        if ($sContainer !== $aCollapsible['container']) {
            return '';
        }
        $_sSectionTitle = $this->_getSectionTitle($this->aArguments['title'], $this->aArguments['tag'], $this->aFieldsets, $iSectionIndex, $this->aFieldTypeDefinitions, $aCollapsible);
        $_aSectionset = $this->aArguments['sectionset'];
        $_sSectionTitleTagID = str_replace('|', '_', $_aSectionset['_section_path']) . '_' . $iSectionIndex;
        return $this->_getCollapsibleSectionsEnablerScript() . "<div " . $this->getAttributes(array('id' => $_sSectionTitleTagID, 'class' => $this->getClassAttribute('admin-page-framework-section-title', $this->getAOrB('box' === $aCollapsible['type'], 'accordion-section-title', ''), 'admin-page-framework-collapsible-title', $this->getAOrB('sections' === $aCollapsible['container'], 'admin-page-framework-collapsible-sections-title', 'admin-page-framework-collapsible-section-title'), $this->getAOrB($aCollapsible['is_collapsed'], 'collapsed', ''), 'admin-page-framework-collapsible-type-' . $aCollapsible['type']),) + $this->getDataAttributeArray($aCollapsible)) . ">" . $_sSectionTitle . "</div>";
    }
    static private $_bLoaded = false;
    protected function _getCollapsibleSectionsEnablerScript() {
        if (self::$_bLoaded) {
            return;
        }
        self::$_bLoaded = true;
        new AdminPageFramework_Form_View___Script_CollapsibleSection($this->oMsg);
    }
}
