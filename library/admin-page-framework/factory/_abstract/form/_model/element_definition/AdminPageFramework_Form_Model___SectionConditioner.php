<?php
class AdminPageFramework_Form_Model___SectionConditioner extends AdminPageFramework_WPUtility {
    public $aSectionsets = array();
    public function __construct() {
        $_aParameters = func_get_args() + array($this->aSectionsets,);
        $this->aSectionsets = $_aParameters[0];
    }
    public function get() {
        return $this->_getSectionsConditioned($this->aSectionsets);
    }
    private function _getSectionsConditioned(array $aSections = array()) {
        $_aNewSections = array();
        foreach ($aSections as $_sSectionID => $_aSection) {
            if (!$this->_isAllowed($_aSection)) {
                continue;
            }
            $_aNewSections[$_sSectionID] = $_aSection;
        }
        return $_aNewSections;
    }
    protected function _isAllowed(array $aDefinition) {
        if (!current_user_can($aDefinition['capability'])) {
            return return false;
        }
        return ( boolean )$aDefinition['if'];
    }
}