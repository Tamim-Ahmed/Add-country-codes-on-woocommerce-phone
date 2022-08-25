// Copy the below code to your themes functions.php file


wp_enqueue_style( 'int-tel-phone-style', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css' );
wp_enqueue_script('int-tel-phone-js','https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/js/intlTelInput.min.js');

add_action( 'wp_footer', 'callback_wp_footer' );
function callback_wp_footer(){
    ?>
    <script type="text/javascript">
        ( function( $ ) {
            $( document.body ).on( 'updated_checkout', function(data) {
                var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>",
                country_code = $('#billing_country').val();

                var ajax_data = {
                    action: 'append_country_prefix_in_billing_phone',
                    country_code: $('#billing_country').val()
                };

                $.post( ajax_url, ajax_data, function( response ) { 
                    $('#billing_phone').val(response);
                });
            } );
        } )( jQuery );
    </script>
    <?php
}

add_action( 'wp_ajax_nopriv_append_country_prefix_in_billing_phone', 'country_prefix_in_billing_phone' );
add_action( 'wp_ajax_append_country_prefix_in_billing_phone', 'country_prefix_in_billing_phone' );
function country_prefix_in_billing_phone() {
    $calling_code = '';
    $country_code = isset( $_POST['country_code'] ) ? $_POST['country_code'] : '';
    if( $country_code ){
        $calling_code = WC()->countries->get_country_calling_code( $country_code );
        $calling_code = is_array( $calling_code ) ? $calling_code[0] : $calling_code;

    }
    echo $calling_code;
    die();
}
