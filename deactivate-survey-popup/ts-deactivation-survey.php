<?php

/**
* Add survey to plugin deactivation
*
*/

function my_plugin_deactivation_survey() {
    /**
      * Your plugin basename
      */

    $plugin = 'order-delivery-date/order_delivery_date.php';
    
    
    // making sure we're running in plugins.php admin page
    if ( !isset( $GLOBALS[ 'pagenow' ] ) || 'plugins.php' != $GLOBALS[ 'pagenow' ] )
        return;
 
    // deactivations only
    if ( !isset($_REQUEST['action']) || 'deactivate' != $_REQUEST['action'] )
        return;
 
    // run on our plugin deactivation
    if ( !isset($_REQUEST['plugin']) || $plugin != $_REQUEST['plugin'] )
        return;
 
    // make sure we don't make plugin deactivations impossible
    if ( isset($_REQUEST['ignore_sv']) )
        return;
    
    if ( isset($_POST['submit_sv']) ) {
        $reason = !empty($_POST['reason']) ? ( 'other' != $_POST['reason'] ? trim($_POST['reason']) : (
            !empty($_POST['other']) ? trim($_POST['other']) : null
        ) ) : null;
 
        if ( $reason ) {
            /**
              * Here we forward an email to our inbox as plugin authors.
              * You can include more technical info about the user and the environment
              * such as PHP_VERSION, get_bloginfo('version'), is_multisite(), etc
              * but make sure you do that with user consent.
              */
            wp_mail('mokshajogani29@gmail.com', 'Deactivation Survey', sprintf(
                'Deactivation reason for "%s": %s',
                $plugin, $reason
            ));
        }
 
        // refresh the page to continue to plugin deactivation
        wp_safe_redirect( add_query_arg( 'ignore_sv', 1 ) );
        exit;
    } else {
        ob_start(); ?>
 
        <h2>Deactivation Survey</h2>
        <form method="post">
            <p>We see you are about to deactivate our plugin. Do you mind taking a quick survey?</p>
            <p>
                <a href="javascript:;" id="showhide">Sure! Show me</a> |
                <a href="<?php echo add_query_arg( 'ignore_sv', 1 ); ?>">Nah, thanks, proceed to deactivation</a>
            </p>
            <div id="sv" style="display:none">
                <p>Thank you! Why you are deactivating this plugin?</p>
                <label style="display:block"><input type="radio" name="reason" value="Reason 1"> Reason 1</label>
                <label style="display:block"><input type="radio" name="reason" value="Reason 2"> Reason 2</label>
                <label style="display:block"><input type="radio" name="reason" value="other"> Another reason</label>
                <p id="other" style="display:none">
                    <input type="text" name="other" />
                </p>
                <p><input type="submit" name="submit_sv" value="Submit Survey and Deactivate Plugin" />
            </div>
        </form>
 
        <script type="text/javascript">
        /** it's safe to override window.onload here as there's 
          * no client JS running on the errors page */
        window.onload = function()
        {
            var showhide = document.getElementById('showhide')
              , reason = document.querySelectorAll('[name="reason"]');
            if ( null != showhide ) showhide.onclick = function()
            {
                var e = document.getElementById('sv');
                e.style.display=(e.style.display?'':'none');
            }
            if ( reason.length ) {
                var other = document.getElementById('other');
                for ( var c in reason ) {
                    if ( reason.hasOwnProperty(c) ) reason.onchange = function()
                    {
                        if ( 'other' == this.value ) {
                            other.style.display = '';
                            other.children[0].focus();
                        } else {
                            other.style.display = 'none';
                        }
                    }
                }
            }
        }
        </script>
 
        <?php wp_die(ob_get_clean(), 'Deactivating plugin..' );
    }
}
 
add_action('admin_init', 'my_plugin_deactivation_survey');