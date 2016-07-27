<?php
// Page link field

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
	echo $this->indent . htmlspecialchars("<?php \$page_link_array =  " . $this->get_field_method . "('" . $this->name . "'); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php foreach (\$page_link_array as \$page_link_value) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php echo \$page_link_value;?> ")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>")."\n";
}
