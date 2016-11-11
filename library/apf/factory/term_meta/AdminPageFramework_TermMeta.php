<?php 
/**
	Admin Page Framework v3.8.11b01 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class AdminPageFramework_TermMeta_Router extends AdminPageFramework_TaxonomyField_Controller {
}
abstract class AdminPageFramework_TermMeta_Model extends AdminPageFramework_TermMeta_Router {
    public function _replyToGetSavedFormData() {
        return array();
    }
    protected function _setOptionArray($iTermID = null, $_deprecated = null) {
        $this->oForm->aSavedData = $this->oUtil->addAndApplyFilter($this, 'options_' . $this->oProp->sClassName, $this->_getSavedTermMetas($iTermID, $this->oForm->aFieldsets));
    }
    private function _getSavedTermMetas($iTermID, array $aFieldsets) {
        $_oMetaData = new AdminPageFramework_TermMeta_Model___TermMeta($iTermID, $this->oForm->aFieldsets);
        return $_oMetaData->get();
    }
    public function _replyToValidateOptions($iTermID) {
        if (!$this->_shouldProceedValidation()) {
            return;
        }
        $_aSavedFormData = $this->_getSavedTermMetas($iTermID, $this->oForm->aFieldsets);
        $_aSubmittedFormData = $this->oForm->getSubmittedData($_POST);
        $_aSubmittedFormData = $this->oUtil->addAndApplyFilters($this, 'validation_' . $this->oProp->sClassName, call_user_func_array(array($this, 'validate'), array($_aSubmittedFormData, $_aSavedFormData, $this)), $_aSavedFormData, $this);
        $this->oForm->updateMetaDataByType($iTermID, $_aSubmittedFormData, $this->oForm->dropRepeatableElements($_aSavedFormData), $this->oForm->sStructureType);
    }
}
abstract class AdminPageFramework_TermMeta_View extends AdminPageFramework_TermMeta_Model {
    public function _replyToGetInputNameAttribute() {
        $_aParams = func_get_args() + array(null, null, null);
        return $_aParams[0];
    }
    public function _replyToGetFlatInputName() {
        $_aParams = func_get_args() + array(null, null, null);
        return $_aParams[0];
    }
}
abstract class AdminPageFramework_TermMeta_Controller extends AdminPageFramework_TermMeta_View {
}
abstract class AdminPageFramework_TermMeta extends AdminPageFramework_TermMeta_Controller {
    protected $_sStructureType = 'term_meta';
    public function __construct($asTaxonomySlug, $sCapability = 'manage_options', $sTextDomain = 'admin-page-framework') {
        if (empty($asTaxonomySlug)) {
            return;
        }
        $_sProprtyClassName = isset($this->aSubClassNames['oProp']) ? $this->aSubClassNames['oProp'] : 'AdminPageFramework_Property_' . $this->_sStructureType;
        $this->oProp = new $_sProprtyClassName($this, get_class($this), $sCapability, $sTextDomain, $this->_sStructureType);
        $this->oProp->aTaxonomySlugs = ( array )$asTaxonomySlug;
        parent::__construct($this->oProp);
    }
}
