<?php
// Password field

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit; 

echo $this->indent . htmlspecialchars("<?php \$password = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>"."\n");
