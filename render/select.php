<?php
// Select field

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit; 

// Check if single or multiple values can be stored
$multiple_values = $this->settings['multiple'];

// If single
if($multiple_values == 0) {
	echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "('" . $this->name . "'); ?>")."\n";
}

// If multiple
if($multiple_values == 1) {
	echo $this->indent . htmlspecialchars("<?php \$select_object = " . $this->get_field_object_method . "('" . $this->name . "'); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php \$select_array = \$select_object[value]; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php foreach (\$select_array as \$select_value) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php echo \$select_value;?>")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>")."\n";
}
