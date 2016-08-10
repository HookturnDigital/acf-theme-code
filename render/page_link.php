<?php
// Page link field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if single or multiple values can be stored
// NOTE: Value is a string
$multiple_values = isset( $this->settings['multiple'] ) ? $this->settings['multiple'] : '';

// If single
if($multiple_values == '0') {
	echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name . "' ); ?>")."\n";
}

// If multiple
if($multiple_values == '1') {
	echo $this->indent . htmlspecialchars("<?php \$".$this->name."_items =  " . $this->get_field_method . "( '" . $this->name . "' ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php foreach ( \$".$this->name."_items as \$".$this->name."_item ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php echo \$".$this->name."_item; ?> ")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>")."\n";
}
