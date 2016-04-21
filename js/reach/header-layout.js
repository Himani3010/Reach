/*--------------------------------------------------------
 * Header Layout
---------------------------------------------------------*/
REACH.HeaderLayout = ( function( $ ) {

    return {
        init : function() {
            if ( REACH_VARS.primary_navigation_width.length ) {            
                return;
            }

            // We can't calculate the navigation width when on a small screen
            if ( $( window ).width() < 800 ) {
                return;
            }

            var width = $( '.site-navigation' ).width(), 
                stylesheet = ( function(){
                    var style = document.createElement("style");
                    style.appendChild(document.createTextNode(""));
                    document.head.appendChild(style);
                    return style.sheet;
                })();

            stylesheet.insertRule('@media screen and (min-width: 50em) { .site-branding { margin-right:' + width + 'px; } }', 0);

            $.ajax({
                type: "POST",
                data: {
                    action : 'set_primary_navigation_width', 
                    width : $( '.site-navigation' ).width()
                },
                dataType: "json",
                url: REACH_VARS.ajaxurl,
                xhrFields: {
                    withCredentials: true
                },
                success: function ( response ) {
                    console.log( response );
                },
                error: function( error ) {
                    console.log( error );
                }
            }).fail(function ( response ) {
                if ( window.console && window.console.log ) {
                    console.log( response );
                }
            });
        }
    }
})( jQuery );