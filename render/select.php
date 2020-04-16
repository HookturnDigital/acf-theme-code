<?php // Select field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if single or multiple values can be stored
// NOTE: Value is a string
$multiple_values = isset( $this->settings['multiple'] ) ? $this->settings['multiple'] : '';

// Check for return format ACF 5.4 feature
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

// If single
if ($multiple_values == '0') {

	// if this a single option returned as an array
	if($return_format == 'array') {

		// return a get field with the var name
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_selected_option = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_selected_option ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php echo esc_html( \$".$this->var_name."_selected_option['label'] ); ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php echo esc_html( \$".$this->var_name."_selected_option['value'] ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

	} else {

		// else retun the field
		echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";

	}
}

// If multiple
if ($multiple_values == '1') {

	if ($return_format == 'value' || $return_format == 'label') {

		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_".$return_format."s = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_".$return_format."s ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_".$return_format."s as \$".$this->var_name."_".$return_format." ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	 	<?php echo esc_html( \$".$this->var_name."_".$return_format." ); ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

	} elseif ($return_format == 'array') {

		// multi dimensional array
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_selected_options = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_selected_options ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_selected_options as \$".$this->var_name."_selected_option ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("		<?php echo esc_html( \$".$this->var_name."_selected_option['label'] ); ?>")."\n";
		echo $this->indent . htmlspecialchars("		<?php echo esc_html( \$".$this->var_name."_selected_option['value'] ); ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

	}


}
