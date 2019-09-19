<?php
// Checkbox field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

// if return format is value or label (single array)
if($return_format == 'value' || $return_format == 'label') {

    // Return the code to ouput the value from an array
    echo $this->indent . htmlspecialchars("<?php // ".$this->name." ( ".$return_format." )")."\n";
    echo $this->indent . htmlspecialchars("\$".$this->var_name."_array = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " );")."\n";
    echo $this->indent . htmlspecialchars("if ( \$".$this->var_name."_array ):")."\n";
    echo $this->indent . htmlspecialchars("	foreach ( \$".$this->var_name."_array as \$".$this->var_name."_item ):")."\n";
    echo $this->indent . htmlspecialchars("	 	echo \$".$this->var_name."_item;")."\n";
    echo $this->indent . htmlspecialchars("	endforeach;")."\n";
    echo $this->indent . htmlspecialchars("endif; ?>"."\n");

} elseif($return_format == 'array') {

    // Return the code to output the value from a multi dimensional array
    echo $this->indent . htmlspecialchars("<?php // ".$this->name." ( ".$return_format." )")."\n";
    echo $this->indent . htmlspecialchars("\$".$this->var_name."_array = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " );")."\n";
    echo $this->indent . htmlspecialchars("if ( \$".$this->var_name."_array ):")."\n";
    echo $this->indent . htmlspecialchars("	foreach ( \$".$this->var_name."_array as \$".$this->var_name."_item ):")."\n";
    echo $this->indent . htmlspecialchars("	 	echo \$".$this->var_name."_item['value'];")."\n";
    echo $this->indent . htmlspecialchars("	endforeach;")."\n";
    echo $this->indent . htmlspecialchars("endif; ?>"."\n");

} else {

    // Fallback to acf 5.3 style output
    echo $this->indent . htmlspecialchars("<?php // ".$this->name)."\n";
    echo $this->indent . htmlspecialchars("\$field = ". $this->get_field_object_method ."( '" . $this->name ."'". $this->location_rendered_param . " );")."\n";
    echo $this->indent . htmlspecialchars("\$value = \$field['value'];")."\n";
    echo $this->indent . htmlspecialchars("\$choices = \$field['choices'];")."\n";
    echo $this->indent . htmlspecialchars("if ( \$value ):")."\n";
    echo $this->indent . htmlspecialchars("	foreach ( \$value as \$v ):")."\n";
    echo $this->indent . htmlspecialchars("		echo \$choices[ \$v ];")."\n";
    echo $this->indent . htmlspecialchars("	endforeach;")."\n";
    echo $this->indent . htmlspecialchars("endif; ?>")."\n";

}
