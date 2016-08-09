<?php
// Google map / location field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Location stored in array for now
echo $this->indent . htmlspecialchars("<?php \$location_array = " .  $this->get_field_method . "( '" . $this->name . "' );")."\n";
echo $this->indent . htmlspecialchars("// var_dump( \$location_array ); ?>")."\n";
