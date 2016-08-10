<?php
// Checkbox field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

// echo htmlspecialchars('<h3>'.$return_format.'</h3>')."\n";

// if return format is value or label (single array)
if($return_format == 'value' || $return_format == 'label') {

    // Return the code to ouput the vlaue from an array
    echo $this->indent . htmlspecialchars("<?php // ".$this->name." ( ".$return_format." )")."\n";
    echo $this->indent . htmlspecialchars("\$".$this->name."_array = ". $this->get_field_method . "( '" . $this->name . "' );")."\n";
    echo $this->indent . htmlspecialchars("if ( \$".$this->name."_array ):")."\n";
    echo $this->indent . htmlspecialchars("	foreach ( \$".$this->name."_array as \$".$this->name."_item ):")."\n";
    echo $this->indent . htmlspecialchars("	 	echo \$".$this->name."_item;")."\n";
    echo $this->indent . htmlspecialchars("	endforeach;")."\n";
    echo $this->indent . htmlspecialchars("endif; ?>"."\n");

} elseif($return_format == 'array') {

    // Return the code to output the value from a multi dimensional array
    echo $this->indent . htmlspecialchars("<?php // ".$this->name." ( ".$return_format." )")."\n";
    echo $this->indent . htmlspecialchars("\$".$this->name."_array = ". $this->get_field_method . "( '" . $this->name . "' );")."\n";
    echo $this->indent . htmlspecialchars("if ( \$".$this->name."_array ):")."\n";
    echo $this->indent . htmlspecialchars("	foreach ( \$".$this->name."_array as \$".$this->name."_item ):")."\n";
    echo $this->indent . htmlspecialchars("	 	echo \$".$this->name."_item['value'];")."\n";
    echo $this->indent . htmlspecialchars("	endforeach;")."\n";
    echo $this->indent . htmlspecialchars("endif; ?>"."\n");

} else {

    // Fallback to acf 5.3 style output
    echo $this->indent . htmlspecialchars("<?php // ".$this->name)."\n";
    echo $this->indent . htmlspecialchars("\$field = ". $this->get_field_object_method ."( '" . $this->name . "' );")."\n";
    echo $this->indent . htmlspecialchars("\$value = \$field['value'];")."\n";
    echo $this->indent . htmlspecialchars("\$choices = \$field['choices'];")."\n";
    echo $this->indent . htmlspecialchars("if ( \$value ):")."\n";
    echo $this->indent . htmlspecialchars("	foreach ( \$value as \$v ):")."\n";
    echo $this->indent . htmlspecialchars("		echo \$choices[ \$v ];")."\n";
    echo $this->indent . htmlspecialchars("	endforeach;")."\n";
    echo $this->indent . htmlspecialchars("endif; ?>")."\n";

}
