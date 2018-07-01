<?php // Button Group field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Get return format
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

// If return format is value or label
if ( $return_format == 'value' || $return_format == 'label' ) {

	echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name ."'". $this->location . " ); ?>")."\n";

} elseif( $return_format == 'array' ) {

	// Return the code to output the value from a multi dimensional array
	echo $this->indent . htmlspecialchars("<?php // ".$this->name." ( ".$return_format." )")."\n";
	echo $this->indent . htmlspecialchars("\$".$this->var_name."_array = ". $this->get_field_method . "( '" . $this->name ."'". $this->location . " );")."\n";
	echo $this->indent . htmlspecialchars("echo \$".$this->var_name."_array['value'];")."\n";
	echo $this->indent . htmlspecialchars("echo \$".$this->var_name."_array['label']; ?>")."\n";

}
