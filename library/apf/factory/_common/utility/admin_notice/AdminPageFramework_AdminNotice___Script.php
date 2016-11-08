<?php 
/**
	Admin Page Framework v3.8.10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AdminPageFramework_AdminNotice___Script extends AdminPageFramework_Factory___Script_Base {
    public function load() {
        wp_enqueue_script('jquery');
    }
    static public function getScript() {
        return <<<JAVASCRIPTS
( function( $ ) {
    jQuery( document ).ready( function() {         

        var _oAdminNotices = jQuery( '.admin-page-framework-settings-notice-message' );
        if ( _oAdminNotices.length ) {
                    
            // Animation of the `slideDown()` method does not work well when the target element has a margin
            // so enclose the elemnet in a new container and apply new margins to it.
            var _oContainer     = jQuery( _oAdminNotices )
                .css( 'margin', '0' )   // prevents jumpy animation
                .wrap( "<div class='admin-page-framework-admin-notice-animation-container'></div>" );
            _oContainer.css( 'margin-top', '1em' );
            _oContainer.css( 'margin-bottom', '1em' );
            
            // Now animate.
            jQuery( _oAdminNotices )
                .css( 'visibility', 'hidden' )
                .slideDown( 800 )
                .css( {opacity: 0, visibility: 'visible'})
                .animate( {opacity: 1}, 400 );
                
        }

    });              

}( jQuery ));
JAVASCRIPTS;
        
    }
}
