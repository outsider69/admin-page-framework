<?php
abstract class AdminPageFramework_TaxonomyField_Model extends AdminPageFramework_TaxonomyField_Router {
    public function _replyToManageColumns($aColumns) {
        return $this->_getFilteredColumnsByFilterPrefix($this->oUtil->getAsArray($aColumns), 'columns_', isset($_GET['taxonomy']) ? $_GET['taxonomy'] : '');
    }
    public function _replyToSetSortableColumns($aSortableColumns) {
        return $this->_getFilteredColumnsByFilterPrefix($this->oUtil->getAsArray($aSortableColumns), 'sortable_columns_', isset($_GET['taxonomy']) ? $_GET['taxonomy'] : '');
    }
    private function _getFilteredColumnsByFilterPrefix(array $aColumns, $sFilterPrefix, $sTaxonomy) {
        if ($sTaxonomy) {
            $aColumns = $this->oUtil->addAndApplyFilter($this, "{$sFilterPrefix}{$_GET['taxonomy']}", $aColumns);
        }
        return $this->oUtil->addAndApplyFilter($this, "{$sFilterPrefix}{$this->oProp->sClassName}", $aColumns);
    }
    public function _replyToGetSavedFormData() {
        return array();
    }
    protected function _setOptionArray($iTermID = null, $sOptionKey) {
        $this->oForm->aSavedData = $this->_getSavedFormData($iTermID, $sOptionKey);
    }
    private function _getSavedFormData($iTermID, $sOptionKey) {
        return $this->oUtil->addAndApplyFilter($this, 'options_' . $this->oProp->sClassName, $this->_getSavedTermFormData($iTermID, $sOptionKey));
    }
    private function _getSavedTermFormData($iTermID, $sOptionKey) {
        $_aSavedTaxonomyFormData = $this->_getSavedTaxonomyFormData($sOptionKey);
        return $this->oUtil->getElementAsArray($_aSavedTaxonomyFormData, $iTermID);
    }
    private function _getSavedTaxonomyFormData($sOptionKey) {
        return get_option($sOptionKey, array());
    }
    public function _replyToValidateOptions($iTermID) {
        if (!$this->_shouldProceedValidation()) {
            return;
        }
        $_aTaxonomyFormData = $this->_getSavedTaxonomyFormData($this->oProp->sOptionKey);
        $_aSavedFormData = $this->_getSavedTermFormData($iTermID, $this->oProp->sOptionKey);
        $_aSubmittedFormData = $this->oForm->getSubmittedData($_POST);
        $_aSubmittedFormData = $this->oUtil->addAndApplyFilters($this, 'validation_' . $this->oProp->sClassName, call_user_func_array(array($this, 'validate'), array($_aSubmittedFormData, $_aSavedFormData, $this)), $_aSavedFormData, $this);
        $_aTaxonomyFormData[$iTermID] = $this->oUtil->uniteArrays($_aSubmittedFormData, $_aSavedFormData);
        update_option($this->oProp->sOptionKey, $_aTaxonomyFormData);
    }
    private function _shouldProceedValidation() {
        if (!isset($_POST[$this->oProp->sClassHash])) {
            return false;
        }
        if (!wp_verify_nonce($_POST[$this->oProp->sClassHash], $this->oProp->sClassHash)) {
            return false;
        }
        return true;
    }
}