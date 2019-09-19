<?php
// User field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_array = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?> "."\n");
echo $this->indent . htmlspecialchars("<?php // var_dump( \$".$this->var_name."_array ); ?>")."\n";
