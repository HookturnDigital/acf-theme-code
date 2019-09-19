<?php
// Page link field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if single or multiple values can be stored
// NOTE Value is a string
$multiple_values = isset( $this->settings['multiple'] ) ? $this->settings['multiple'] : '';

// If single
if($multiple_values == '0') {
	echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
}

// If multiple
if($multiple_values == '1') {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_items =  " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php foreach ( \$".$this->var_name."_items as \$".$this->var_name."_item ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."_item; ?> ")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>")."\n";
}
