<?php
// User field

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit; 

echo $this->indent . htmlspecialchars("<?php \$user_array = " . $this->get_field_method . "('".$this->name."'); "."\n");
echo $this->indent . htmlspecialchars("// var_dump(\$user_array); ?>")."\n";
