<?php // Select field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if single or multiple values can be stored
// NOTE: Value is a string
$multiple_values = isset( $this->settings['multiple'] ) ? $this->settings['multiple'] : '';

// Check for return format ACF 5.4 feature
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

// If single
if($multiple_values == '0') {

	// if this a single option returned as an array
	if($return_format == 'array') {

		// return a get field with the var name
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_array = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_array ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_array as \$".$this->var_name."_item ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	 	<?php echo \$".$this->var_name."_item; ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

	} else {

		// else retun the field
		echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";

	}
}

// If multiple
if($multiple_values == '1') {

	if($return_format == 'array') {

		// multi dimensional array
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_array = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_array ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_array as \$".$this->var_name."_sub_array ): ?>")."\n";
		echo $this->indent . htmlspecialchars("		<?php foreach ( \$".$this->var_name."_sub_array as \$".$this->var_name."_sub_array_item ): ?>")."\n";
		echo $this->indent . htmlspecialchars("			<?php echo \$".$this->var_name."_sub_array_item; ?>")."\n";
		echo $this->indent . htmlspecialchars("		<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

	} else {

		// loop over the array
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_array = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_array ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_array as \$".$this->var_name."_item ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	 	<?php echo \$".$this->var_name."_item; ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

	}

}
