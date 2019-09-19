<?php
// Taxonomy field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Get return format and type
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';
$taxonomy_field_type = isset( $this->settings['field_type'] ) ? $this->settings['field_type'] : '';

if ( $return_format == 'object' ) {

	// If field type is a single type
	if($taxonomy_field_type == 'select' || $taxonomy_field_type == 'radio') {
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_term = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_term ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."_term->name; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
	}

	// If field type is a multiple type
	if($taxonomy_field_type == 'multi_select' || $taxonomy_field_type == 'checkbox') {
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_terms = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>" )."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_terms ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_terms as \$".$this->var_name."_term ): ?>")."\n";
		echo $this->indent . htmlspecialchars("		<?php echo \$".$this->var_name."_term->name; ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
	}
}

// if return format is an array of ids
if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_ids = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php // var_dump( \$".$this->var_name."_ids ); ?>")."\n";
}
