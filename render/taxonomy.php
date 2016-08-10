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
		echo $this->indent . htmlspecialchars("<?php \$".$this->name."_term = " . $this->get_field_method . "( '" . $this->name . "' ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name."_term ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php echo \$".$this->name."_term->name; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";

	}

	// If field type is a multiple type
	if($taxonomy_field_type == 'multi_select' || $taxonomy_field_type == 'checkbox') {
		echo $this->indent . htmlspecialchars("<?php \$".$this->name."_terms = " . $this->get_field_method . "( '" . $this->name . "' ); ?>" )."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name."_terms ): ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->name."_terms as \$".$this->name."_term ): ?>")."\n";
		echo $this->indent . htmlspecialchars("		<?php echo \$".$this->name."_term->name; ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
	}
}


// if return format is an array of ids
if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->name."_ids = " . $this->get_field_method . "( '" . $this->name . "' ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php // var_dump( \$".$this->name."_ids ); ?>")."\n";
}
