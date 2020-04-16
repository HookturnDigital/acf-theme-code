<?php
// Taxonomy field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Get return format and type
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';
$taxonomy_field_type = isset( $this->settings['field_type'] ) ? $this->settings['field_type'] : '';
$taxonomy = isset( $this->settings['taxonomy'] ) ? $this->settings['taxonomy'] : '';

if ( $return_format == 'object' ) {

	// Single values
	if ( $taxonomy_field_type == 'select' || $taxonomy_field_type == 'radio' ) {
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	<a href=\"<?php echo esc_url( get_term_link( \$".$this->var_name." ) ); ?>\"><?php echo esc_html( \$".$this->var_name."->name ); ?></a>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
	}

	// Multiple values
	if ( $taxonomy_field_type == 'multi_select' || $taxonomy_field_type == 'checkbox' ) {
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>" )."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$term ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("		<a href=\"<?php echo esc_url( get_term_link( \$term ) ); ?>\"><?php echo esc_html( \$term->name ); ?></a>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
	}

}

if ( $return_format == 'id' ) {

	// Single values
	if ( $taxonomy_field_type == 'select' || $taxonomy_field_type == 'radio' ) {
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php \$term = get_term_by( 'id', \$".$this->var_name.", '".$taxonomy."' ); ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$term ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	<a href=\"<?php echo esc_url( get_term_link( \$term ) ); ?>\"><?php echo esc_html( \$term->name ); ?></a>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
	}

	// Multiple values
	if ( $taxonomy_field_type == 'multi_select' || $taxonomy_field_type == 'checkbox' ) {
		echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>" )."\n";
		echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php \$get_terms_args = array(")."\n";
		echo $this->indent . htmlspecialchars("		'taxonomy' => '".$taxonomy."',")."\n";
		echo $this->indent . htmlspecialchars("		'hide_empty' => 0,")."\n";
		echo $this->indent . htmlspecialchars("		'include' => \$".$this->var_name.",")."\n";
		echo $this->indent . htmlspecialchars("	); ?>" )."\n";
		echo $this->indent . htmlspecialchars("	<?php \$terms = get_terms( \$get_terms_args ); ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php if ( \$terms ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("		<?php foreach ( \$terms as \$term ) : ?>")."\n";
		echo $this->indent . htmlspecialchars("			<a href=\"<?php echo esc_url( get_term_link( \$term ) ); ?>\"><?php echo esc_html( \$term->name ); ?></a>")."\n";
		echo $this->indent . htmlspecialchars("		<?php endforeach; ?>")."\n";
		echo $this->indent . htmlspecialchars("	<?php endif; ?>")."\n";
		echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
	}

}
