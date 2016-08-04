<?php
// Date picker

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Date output is handled by ACF settings
echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name . "' ); ?>")."\n";
