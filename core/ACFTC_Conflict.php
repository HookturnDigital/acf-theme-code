<?php
/**
 * Utility for dealing with mulitple activated versions of the Theme Code plugin
 */
class ACFTC_Conflict {

    private $basenames = array(
        'acf-theme-code/acf_theme_code.php',
        'acf-theme-code-pro/acf_theme_code_pro.php'
    ); 

    public function __construct()
    {

        deactivate_plugins( $this->basenames );

        $args = array(
            'link_url' => admin_url('plugins.php'),
            'link_text' => '« Manage Plugins'
        );

        $error_message = '<p>' . __( 'It appears you have more than one version of the <strong>Advanced Custom Fields: Theme Code</strong> plugin activated. To avoid conflicts <strong>all versions</strong> of this plugin have been deactivated.', 'acf-theme-code' ) . '</p>';
        $error_message .= '<p><strong>' . __( 'Please activate your preferred version.', 'acf-theme-code' ) . '</strong></p>';

		wp_die( $error_message, '', $args );

    }

}
