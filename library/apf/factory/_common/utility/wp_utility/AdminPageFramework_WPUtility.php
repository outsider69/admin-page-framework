<?php 
/**
	Admin Page Framework v3.8.16 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AdminPageFramework_WPUtility_URL extends AdminPageFramework_Utility {
    static public function getCurrentAdminURL() {
        $sRequestURI = $GLOBALS['is_IIS'] ? $_SERVER['PATH_INFO'] : $_SERVER["REQUEST_URI"];
        $sPageURL = 'on' == @$_SERVER["HTTPS"] ? "https://" : "http://";
        if ("80" != $_SERVER["SERVER_PORT"]) {
            $sPageURL.= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $sRequestURI;
        } else {
            $sPageURL.= $_SERVER["SERVER_NAME"] . $sRequestURI;
        }
        return $sPageURL;
    }
    static public function getQueryAdminURL($aAddingQueries = array(), $aRemovingQueryKeys = array(), $sSubjectURL = '') {
        $_sAdminURL = is_network_admin() ? network_admin_url(AdminPageFramework_WPUtility_Page::getPageNow()) : admin_url(AdminPageFramework_WPUtility_Page::getPageNow());
        $sSubjectURL = $sSubjectURL ? $sSubjectURL : add_query_arg($_GET, $_sAdminURL);
        return self::getQueryURL($aAddingQueries, $aRemovingQueryKeys, $sSubjectURL);
    }
    static public function getQueryURL($aAddingQueries, $aRemovingQueryKeys, $sSubjectURL) {
        $sSubjectURL = empty($aRemovingQueryKeys) ? $sSubjectURL : remove_query_arg(( array )$aRemovingQueryKeys, $sSubjectURL);
        $sSubjectURL = add_query_arg($aAddingQueries, $sSubjectURL);
        return $sSubjectURL;
    }
    static public function getSRCFromPath($sFilePath) {
        $_oWPStyles = new WP_Styles();
        $_sRelativePath = AdminPageFramework_Utility::getRelativePath(ABSPATH, $sFilePath);
        $_sRelativePath = preg_replace("/^\.[\/\\\]/", '', $_sRelativePath, 1);
        $_sHref = trailingslashit($_oWPStyles->base_url) . $_sRelativePath;
        unset($_oWPStyles);
        return $_sHref;
    }
    static public function getResolvedSRC($sSRC, $bReturnNullIfNotExist = false) {
        if (!self::isResourcePath($sSRC)) {
            return $bReturnNullIfNotExist ? null : $sSRC;
        }
        if (filter_var($sSRC, FILTER_VALIDATE_URL)) {
            return $sSRC;
        }
        if (file_exists(realpath($sSRC))) {
            return self::getSRCFromPath($sSRC);
        }
        if ($bReturnNullIfNotExist) {
            return null;
        }
        return $sSRC;
    }
    static public function resolveSRC($sSRC, $bReturnNullIfNotExist = false) {
        return self::getResolvedSRC($sSRC, $bReturnNullIfNotExist);
    }
}
class AdminPageFramework_WPUtility_HTML extends AdminPageFramework_WPUtility_URL {
    static public function getAttributes(array $aAttributes) {
        $_sQuoteCharactor = "'";
        $_aOutput = array();
        foreach ($aAttributes as $_sAttribute => $_mProperty) {
            if (is_scalar($_mProperty)) {
                $_aOutput[] = "{$_sAttribute}={$_sQuoteCharactor}" . esc_attr($_mProperty) . "{$_sQuoteCharactor}";
            }
        }
        return implode(' ', $_aOutput);
    }
    static public function generateAttributes(array $aAttributes) {
        return self::getAttributes($aAttributes);
    }
    static public function getDataAttributes(array $aArray) {
        return self::getAttributes(self::getDataAttributeArray($aArray));
    }
    static public function generateDataAttributes(array $aArray) {
        return self::getDataAttributes($aArray);
    }
    static public function getHTMLTag($sTagName, array $aAttributes, $sValue = null) {
        $_sTag = tag_escape($sTagName);
        return null === $sValue ? "<" . $_sTag . " " . self::getAttributes($aAttributes) . " />" : "<" . $_sTag . " " . self::getAttributes($aAttributes) . ">" . $sValue . "</{$_sTag}>";
    }
    static public function generateHTMLTag($sTagName, array $aAttributes, $sValue = null) {
        return self::getHTMLTag($sTagName, $aAttributes, $sValue);
    }
}
class AdminPageFramework_WPUtility_Page extends AdminPageFramework_WPUtility_HTML {
    static public function getCurrentPostType() {
        if (isset(self::$_sCurrentPostType)) {
            return self::$_sCurrentPostType;
        }
        self::$_sCurrentPostType = self::_getCurrentPostType();
        return self::$_sCurrentPostType;
    }
    static private $_sCurrentPostType;
    static private function _getCurrentPostType() {
        $_aMethodsToTry = array('getPostTypeByTypeNow', 'getPostTypeByScreenObject', 'getPostTypeByREQUEST', 'getPostTypeByPostObject',);
        foreach ($_aMethodsToTry as $_sMethodName) {
            $_sPostType = call_user_func(array(__CLASS__, $_sMethodName));
            if ($_sPostType) {
                return $_sPostType;
            }
        }
        return null;
    }
    static public function getPostTypeByTypeNow() {
        if (isset($GLOBALS['typenow']) && $GLOBALS['typenow']) {
            return $GLOBALS['typenow'];
        }
    }
    static public function getPostTypeByScreenObject() {
        if (isset($GLOBALS['current_screen']->post_type) && $GLOBALS['current_screen']->post_type) {
            return $GLOBALS['current_screen']->post_type;
        }
    }
    static public function getPostTypeByREQUEST() {
        if (isset($_REQUEST['post_type'])) {
            return sanitize_key($_REQUEST['post_type']);
        }
        if (isset($_GET['post']) && $_GET['post']) {
            return get_post_type($_GET['post']);
        }
    }
    static public function getPostTypeByPostObject() {
        if (isset($GLOBALS['post']->post_type) && $GLOBALS['post']->post_type) {
            return $GLOBALS['post']->post_type;
        }
    }
    static public function isCustomTaxonomyPage($asPostTypes = array()) {
        if (!in_array(self::getPageNow(), array('tags.php', 'edit-tags.php', 'term.php'))) {
            return false;
        }
        return self::isCurrentPostTypeIn($asPostTypes);
    }
    static public function isPostDefinitionPage($asPostTypes = array()) {
        if (!in_array(self::getPageNow(), array('post.php', 'post-new.php',))) {
            return false;
        }
        return self::isCurrentPostTypeIn($asPostTypes);
    }
    static public function isCurrentPostTypeIn($asPostTypes) {
        $_aPostTypes = self::getAsArray($asPostTypes);
        if (empty($_aPostTypes)) {
            return true;
        }
        return in_array(self::getCurrentPostType(), $_aPostTypes);
    }
    static public function isPostListingPage($asPostTypes = array()) {
        if ('edit.php' != self::getPageNow()) {
            return false;
        }
        $_aPostTypes = self::getAsArray($asPostTypes);
        if (!isset($_GET['post_type'])) {
            return in_array('post', $_aPostTypes);
        }
        return in_array($_GET['post_type'], $_aPostTypes);
    }
    static private $_sPageNow;
    static public function getPageNow() {
        if (isset(self::$_sPageNow)) {
            return self::$_sPageNow;
        }
        if (isset($GLOBALS['pagenow'])) {
            self::$_sPageNow = $GLOBALS['pagenow'];
            return self::$_sPageNow;
        }
        $_aMethodNames = array(0 => '_getPageNow_FrontEnd', 1 => '_getPageNow_BackEnd',);
        $_sMethodName = $_aMethodNames[( integer )is_admin() ];
        self::$_sPageNow = self::$_sMethodName();
        return self::$_sPageNow;
    }
    static private function _getPageNow_FrontEnd() {
        if (preg_match('#([^/]+\.php)([?/].*?)?$#i', $_SERVER['PHP_SELF'], $_aMatches)) {
            return strtolower($_aMatches[1]);
        }
        return 'index.php';
    }
    static private function _getPageNow_BackEnd() {
        $_sPageNow = self::_getPageNowAdminURLBasePath();
        if (self::_isInAdminIndex($_sPageNow)) {
            return 'index.php';
        }
        preg_match('#(.*?)(/|$)#', $_sPageNow, $_aMatches);
        $_sPageNow = strtolower($_aMatches[1]);
        if ('.php' !== substr($_sPageNow, -4, 4)) {
            $_sPageNow.= '.php';
        }
        return $_sPageNow;
    }
    static private function _getPageNowAdminURLBasePath() {
        if (is_network_admin()) {
            $_sNeedle = '#/wp-admin/network/?(.*?)$#i';
        } else if (is_user_admin()) {
            $_sNeedle = '#/wp-admin/user/?(.*?)$#i';
        } else {
            $_sNeedle = '#/wp-admin/?(.*?)$#i';
        }
        preg_match($_sNeedle, $_SERVER['PHP_SELF'], $_aMatches);
        return preg_replace('#\?.*?$#', '', trim($_aMatches[1], '/'));
    }
    static private function _isInAdminIndex($sPageNow) {
        return in_array($sPageNow, array('', 'index', 'index.php'));
    }
    static public function getCurrentScreenID() {
        $_oScreen = get_current_screen();
        if (is_string($_oScreen)) {
            $_oScreen = convert_to_screen($_oScreen);
        }
        if (isset($_oScreen->id)) {
            return $_oScreen->id;
        }
        if (isset($GLBOALS['page_hook'])) {
            return is_network_admin() ? $GLBOALS['page_hook'] . '-network' : $GLBOALS['page_hook'];
        }
        return '';
    }
    static public function doesMetaBoxExist($sContext = '') {
        $_aDimensions = array('wp_meta_boxes', $GLOBALS['page_hook']);
        if ($sContext) {
            $_aDimensions[] = $sContext;
        }
        $_aSideMetaBoxes = self::getElementAsArray($GLOBALS, $_aDimensions);
        return count($_aSideMetaBoxes) > 0;
    }
    static public function getNumberOfScreenColumns() {
        return get_current_screen()->get_columns();
    }
}
class AdminPageFramework_WPUtility_Hook extends AdminPageFramework_WPUtility_Page {
    static public function registerAction($sActionHook, $oCallable, $iPriority = 10) {
        if (did_action($sActionHook)) {
            return call_user_func_array($oCallable, array());
        }
        add_action($sActionHook, $oCallable, $iPriority);
    }
    static public function doActions($aActionHooks, $vArgs1 = null, $vArgs2 = null, $_and_more = null) {
        $aArgs = func_get_args();
        $aActionHooks = $aArgs[0];
        foreach (( array )$aActionHooks as $sActionHook) {
            $aArgs[0] = $sActionHook;
            call_user_func_array('do_action', $aArgs);
        }
    }
    static public function addAndDoActions() {
        $aArgs = func_get_args();
        $oCallerObject = $aArgs[0];
        $aActionHooks = $aArgs[1];
        foreach (( array )$aActionHooks as $sActionHook) {
            if (!$sActionHook) {
                continue;
            }
            $aArgs[1] = $sActionHook;
            call_user_func_array(array(get_class(), 'addAndDoAction'), $aArgs);
        }
    }
    static public function addAndDoAction() {
        $_iArgs = func_num_args();
        $_aArgs = func_get_args();
        $_oCallerObject = $_aArgs[0];
        $_sActionHook = $_aArgs[1];
        if (!$_sActionHook) {
            return;
        }
        $_sAutoCallbackMethodName = str_replace('\\', '_', $_sActionHook);
        if (method_exists($_oCallerObject, $_sAutoCallbackMethodName)) {
            add_action($_sActionHook, array($_oCallerObject, $_sAutoCallbackMethodName), 10, $_iArgs - 2);
        }
        array_shift($_aArgs);
        call_user_func_array('do_action', $_aArgs);
    }
    static public function addAndApplyFilters() {
        $_aArgs = func_get_args();
        $_aFilters = $_aArgs[1];
        $_vInput = $_aArgs[2];
        foreach (( array )$_aFilters as $_sFilter) {
            if (!$_sFilter) {
                continue;
            }
            $_aArgs[1] = $_sFilter;
            $_aArgs[2] = $_vInput;
            $_vInput = call_user_func_array(array(get_class(), 'addAndApplyFilter'), $_aArgs);
        }
        return $_vInput;
    }
    static public function addAndApplyFilter() {
        $_iArgs = func_num_args();
        $_aArgs = func_get_args();
        $_oCallerObject = $_aArgs[0];
        $_sFilter = $_aArgs[1];
        if (!$_sFilter) {
            return $_aArgs[2];
        }
        $_sAutoCallbackMethodName = str_replace('\\', '_', $_sFilter);
        if (method_exists($_oCallerObject, $_sAutoCallbackMethodName)) {
            add_filter($_sFilter, array($_oCallerObject, $_sAutoCallbackMethodName), 10, $_iArgs - 2);
        }
        array_shift($_aArgs);
        return call_user_func_array('apply_filters', $_aArgs);
    }
    static public function getFilterArrayByPrefix($sPrefix, $sClassName, $sPageSlug, $sTabSlug, $bReverse = false) {
        $_aFilters = array();
        if ($sTabSlug && $sPageSlug) {
            $_aFilters[] = "{$sPrefix}{$sPageSlug}_{$sTabSlug}";
        }
        if ($sPageSlug) {
            $_aFilters[] = "{$sPrefix}{$sPageSlug}";
        }
        if ($sClassName) {
            $_aFilters[] = "{$sPrefix}{$sClassName}";
        }
        return $bReverse ? array_reverse($_aFilters) : $_aFilters;
    }
}
class AdminPageFramework_WPUtility_File extends AdminPageFramework_WPUtility_Hook {
    static public function getScriptData($sPathOrContent, $sType = 'plugin', $aDefaultHeaderKeys = array()) {
        $_aHeaderKeys = $aDefaultHeaderKeys + array('sName' => 'Name', 'sURI' => 'URI', 'sScriptName' => 'Script Name', 'sLibraryName' => 'Library Name', 'sLibraryURI' => 'Library URI', 'sPluginName' => 'Plugin Name', 'sPluginURI' => 'Plugin URI', 'sThemeName' => 'Theme Name', 'sThemeURI' => 'Theme URI', 'sVersion' => 'Version', 'sDescription' => 'Description', 'sAuthor' => 'Author', 'sAuthorURI' => 'Author URI', 'sTextDomain' => 'Text Domain', 'sDomainPath' => 'Domain Path', 'sNetwork' => 'Network', '_sitewide' => 'Site Wide Only',);
        $aData = file_exists($sPathOrContent) ? get_file_data($sPathOrContent, $_aHeaderKeys, $sType) : self::getScriptDataFromContents($sPathOrContent, $sType, $_aHeaderKeys);
        switch (trim($sType)) {
            case 'theme':
                $aData['sName'] = $aData['sThemeName'];
                $aData['sURI'] = $aData['sThemeURI'];
            break;
            case 'library':
                $aData['sName'] = $aData['sLibraryName'];
                $aData['sURI'] = $aData['sLibraryURI'];
            break;
            case 'script':
                $aData['sName'] = $aData['sScriptName'];
            break;
            case 'plugin':
                $aData['sName'] = $aData['sPluginName'];
                $aData['sURI'] = $aData['sPluginURI'];
            break;
            default:
            break;
        }
        return $aData;
    }
    static public function getScriptDataFromContents($sContent, $sType = 'plugin', $aDefaultHeaderKeys = array()) {
        $sContent = str_replace("\r", "\n", $sContent);
        $_aHeaders = $aDefaultHeaderKeys;
        if ($sType) {
            $_aExtraHeaders = apply_filters("extra_{$sType}_headers", array());
            if (!empty($_aExtraHeaders)) {
                $_aExtraHeaders = array_combine($_aExtraHeaders, $_aExtraHeaders);
                $_aHeaders = array_merge($_aExtraHeaders, ( array )$aDefaultHeaderKeys);
            }
        }
        foreach ($_aHeaders as $_sHeaderKey => $_sRegex) {
            $_bFound = preg_match('/^[ \t\/*#@]*' . preg_quote($_sRegex, '/') . ':(.*)$/mi', $sContent, $_aMatch);
            $_aHeaders[$_sHeaderKey] = $_bFound && $_aMatch[1] ? _cleanup_header_comment($_aMatch[1]) : '';
        }
        return $_aHeaders;
    }
    static public function download($sURL, $iTimeOut = 300) {
        if (false === filter_var($sURL, FILTER_VALIDATE_URL)) {
            return false;
        }
        $_sTmpFileName = self::setTempPath(self::getBaseNameOfURL($sURL));
        if (!$_sTmpFileName) {
            return false;
        }
        $_aoResponse = wp_safe_remote_get($sURL, array('timeout' => $iTimeOut, 'stream' => true, 'filename' => $_sTmpFileName));
        if (is_wp_error($_aoResponse)) {
            unlink($_sTmpFileName);
            return false;
        }
        if (200 != wp_remote_retrieve_response_code($_aoResponse)) {
            unlink($_sTmpFileName);
            return false;
        }
        $_sContent_md5 = wp_remote_retrieve_header($_aoResponse, 'content-md5');
        if ($_sContent_md5) {
            $_boIsMD5 = verify_file_md5($_sTmpFileName, $_sContent_md5);
            if (is_wp_error($_boIsMD5)) {
                unlink($_sTmpFileName);
                return false;
            }
        }
        return $_sTmpFileName;
    }
    static public function setTempPath($sFilePath = '') {
        $_sDir = get_temp_dir();
        $sFilePath = basename($sFilePath);
        if (empty($sFilePath)) {
            $sFilePath = time() . '.tmp';
        }
        $sFilePath = $_sDir . wp_unique_filename($_sDir, $sFilePath);
        touch($sFilePath);
        return $sFilePath;
    }
    static public function getBaseNameOfURL($sURL) {
        $_sPath = parse_url($sURL, PHP_URL_PATH);
        $_sFileBaseName = basename($_sPath);
        return $_sFileBaseName;
    }
}
class AdminPageFramework_WPUtility_Option extends AdminPageFramework_WPUtility_File {
    static private $_bIsNetworkAdmin;
    static public function deleteTransient($sTransientKey) {
        global $_wp_using_ext_object_cache;
        $_bWpUsingExtObjectCacheTemp = $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;
        self::$_bIsNetworkAdmin = isset(self::$_bIsNetworkAdmin) ? self::$_bIsNetworkAdmin : is_network_admin();
        $sTransientKey = self::_getCompatibleTransientKey($sTransientKey, self::$_bIsNetworkAdmin ? 40 : 45);
        $_aFunctionNames = array(0 => 'delete_transient', 1 => 'delete_site_transient',);
        $_vTransient = $_aFunctionNames[( integer )self::$_bIsNetworkAdmin]($sTransientKey);
        $_wp_using_ext_object_cache = $_bWpUsingExtObjectCacheTemp;
        return $_vTransient;
    }
    static public function getTransient($sTransientKey, $vDefault = null) {
        global $_wp_using_ext_object_cache;
        $_bWpUsingExtObjectCacheTemp = $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;
        self::$_bIsNetworkAdmin = isset(self::$_bIsNetworkAdmin) ? self::$_bIsNetworkAdmin : is_network_admin();
        $sTransientKey = self::_getCompatibleTransientKey($sTransientKey, self::$_bIsNetworkAdmin ? 40 : 45);
        $_aFunctionNames = array(0 => 'get_transient', 1 => 'get_site_transient',);
        $_vTransient = $_aFunctionNames[( integer )self::$_bIsNetworkAdmin]($sTransientKey);
        $_wp_using_ext_object_cache = $_bWpUsingExtObjectCacheTemp;
        return null === $vDefault ? $_vTransient : (false === $_vTransient ? $vDefault : $_vTransient);
    }
    static public function setTransient($sTransientKey, $vValue, $iExpiration = 0) {
        global $_wp_using_ext_object_cache;
        $_bWpUsingExtObjectCacheTemp = $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;
        self::$_bIsNetworkAdmin = isset(self::$_bIsNetworkAdmin) ? self::$_bIsNetworkAdmin : is_network_admin();
        $sTransientKey = self::_getCompatibleTransientKey($sTransientKey, self::$_bIsNetworkAdmin ? 40 : 45);
        $_aFunctionNames = array(0 => 'set_transient', 1 => 'set_site_transient',);
        $_bIsSet = $_aFunctionNames[( integer )self::$_bIsNetworkAdmin]($sTransientKey, $vValue, $iExpiration);
        $_wp_using_ext_object_cache = $_bWpUsingExtObjectCacheTemp;
        return $_bIsSet;
    }
    static public function _getCompatibleTransientKey($sSubject, $iAllowedCharacterLength = 45) {
        if (strlen($sSubject) <= $iAllowedCharacterLength) {
            return $sSubject;
        }
        $_iPrefixLengthToKeep = $iAllowedCharacterLength - 33;
        $_sPrefixToKeep = substr($sSubject, 0, $_iPrefixLengthToKeep - 1);
        return $_sPrefixToKeep . '_' . md5($sSubject);
    }
    static public function getOption($sOptionKey, $asKey = null, $vDefault = null, array $aAdditionalOptions = array()) {
        return self::_getOptionByFunctionName($sOptionKey, $asKey, $vDefault, $aAdditionalOptions);
    }
    static public function getSiteOption($sOptionKey, $asKey = null, $vDefault = null, array $aAdditionalOptions = array()) {
        return self::_getOptionByFunctionName($sOptionKey, $asKey, $vDefault, $aAdditionalOptions, 'get_site_option');
    }
    static private function _getOptionByFunctionName($sOptionKey, $asKey = null, $vDefault = null, array $aAdditionalOptions = array(), $sFunctionName = 'get_option') {
        if (!isset($asKey)) {
            $_aOptions = $sFunctionName($sOptionKey, isset($vDefault) ? $vDefault : array());;
            return empty($aAdditionalOptions) ? $_aOptions : self::uniteArrays($_aOptions, $aAdditionalOptions);
        }
        return self::getArrayValueByArrayKeys(self::uniteArrays(self::getAsArray($sFunctionName($sOptionKey, array()), true), $aAdditionalOptions), self::getAsArray($asKey, true), $vDefault);
    }
}
class AdminPageFramework_WPUtility_Meta extends AdminPageFramework_WPUtility_Option {
    static public function getSavedPostMetaArray($iPostID, array $aKeys) {
        return self::getMetaDataByKeys($iPostID, $aKeys);
    }
    static public function getSavedUserMetaArray($iUserID, array $aKeys) {
        return self::getMetaDataByKeys($iUserID, $aKeys, 'user');
    }
    static public function getSavedTermMetaArray($iTermID, array $aKeys) {
        return self::getMetaDataByKeys($iUserID, $aKeys, 'term');
    }
    static public function getMetaDataByKeys($iObjectID, $aKeys, $sMetaType = 'post') {
        $_aSavedMeta = array();
        if (!$iObjectID) {
            return $_aSavedMeta;
        }
        $_aFunctionNames = array('post' => 'get_post_meta', 'user' => 'get_user_meta', 'term' => 'get_term_meta',);
        $_sFunctionName = self::getElement($_aFunctionNames, $sMetaType, 'get_post_meta');
        foreach ($aKeys as $_sKey) {
            $_aSavedMeta[$_sKey] = call_user_func_array($_sFunctionName, array($iObjectID, $_sKey, true));
        }
        return $_aSavedMeta;
    }
}
class AdminPageFramework_WPUtility_SiteInformation extends AdminPageFramework_WPUtility_Meta {
    static public function isDebugModeEnabled() {
        return ( bool )defined('WP_DEBUG') && WP_DEBUG;
    }
    static public function isDebugLogEnabled() {
        return ( bool )defined('WP_DEBUG_LOG') && WP_DEBUG_LOG;
    }
    static public function isDebugDisplayEnabled() {
        return ( bool )defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY;
    }
    static public function getSiteLanguage($sDefault = 'en_US') {
        return defined('WPLANG') && WPLANG ? WPLANG : $sDefault;
    }
}
class AdminPageFramework_WPUtility_SystemInformation extends AdminPageFramework_WPUtility_SiteInformation {
    static private $_aMySQLInfo;
    static public function getMySQLInfo() {
        if (isset(self::$_aMySQLInfo)) {
            return self::$_aMySQLInfo;
        }
        global $wpdb;
        $_aOutput = array('Version' => isset($wpdb->use_mysqli) && $wpdb->use_mysqli ? @mysqli_get_server_info($wpdb->dbh) : @mysql_get_server_info(),);
        foreach (( array )$wpdb->get_results("SHOW VARIABLES", ARRAY_A) as $_iIndex => $_aItem) {
            $_aItem = array_values($_aItem);
            $_sKey = array_shift($_aItem);
            $_sValue = array_shift($_aItem);
            $_aOutput[$_sKey] = $_sValue;
        }
        self::$_aMySQLInfo = $_aOutput;
        return self::$_aMySQLInfo;
    }
    static public function getMySQLErrorLogPath() {
        $_aMySQLInfo = self::getMySQLInfo();
        return isset($_aMySQLInfo['log_error']) ? $_aMySQLInfo['log_error'] : '';
    }
    static public function getMySQLErrorLog($iLines = 1) {
        $_sLog = self::getFileTailContents(self::getMySQLErrorLogPath(), $iLines);
        return $_sLog ? $_sLog : '';
    }
}
class AdminPageFramework_WPUtility extends AdminPageFramework_WPUtility_SystemInformation {
    static public function getPostTypeSubMenuSlug($sPostTypeSlug, $aPostTypeArguments) {
        $_sCustomMenuSlug = self::getShowInMenuPostTypeArgument($aPostTypeArguments);
        if (is_string($_sCustomMenuSlug)) {
            return $_sCustomMenuSlug;
        }
        return 'edit.php?post_type=' . $sPostTypeSlug;
    }
    static public function getShowInMenuPostTypeArgument($aPostTypeArguments) {
        return self::getElement($aPostTypeArguments, 'show_in_menu', self::getElement($aPostTypeArguments, 'show_ui', self::getElement($aPostTypeArguments, 'public', false)));
    }
    static public function getWPAdminDirPath() {
        $_sWPAdminPath = str_replace(get_bloginfo('url') . '/', ABSPATH, get_admin_url());
        return rtrim($_sWPAdminPath, '/');
    }
    static public function goToLocalURL($sURL, $oCallbackOnError = null) {
        self::redirectByType($sURL, 1, $oCallbackOnError);
    }
    static public function goToURL($sURL, $oCallbackOnError = null) {
        self::redirectByType($sURL, 0, $oCallbackOnError);
    }
    static public function redirectByType($sURL, $iType = 0, $oCallbackOnError = null) {
        $_iRedirectError = self::getRedirectPreError($sURL, $iType);
        if ($_iRedirectError && is_callable($oCallbackOnError)) {
            call_user_func_array($oCallbackOnError, array($_iRedirectError, $sURL,));
            return;
        }
        $_sFunctionName = array(0 => 'wp_redirect', 1 => 'wp_safe_redirect',);
        exit($_sFunctionName[( integer )$iType]($sURL));
    }
    static public function getRedirectPreError($sURL, $iType) {
        if (!$iType && filter_var($sURL, FILTER_VALIDATE_URL) === false) {
            return 1;
        }
        if (headers_sent()) {
            return 2;
        }
        return 0;
    }
    static public function isDebugMode() {
        return ( boolean )defined('WP_DEBUG') && WP_DEBUG;
    }
    static public function isDoingAjax() {
        return defined('DOING_AJAX') && DOING_AJAX;
    }
    static public function flushRewriteRules() {
        if (self::$_bIsFlushed) {
            return;
        }
        flush_rewrite_rules();
        self::$_bIsFlushed = true;
    }
    static private $_bIsFlushed = false;
}
