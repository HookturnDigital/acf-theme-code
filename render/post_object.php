<?php
// Post Object field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$multiple_values = isset( $this->settings['multiple'] ) ? $this->settings['multiple'] : ''; // Note value is a string
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

if( $return_format == 'id' && $multiple_values == '0' ) { 
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. ' = ' . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";

	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo get_permalink( \$".$this->var_name." ); ?>\"><?php echo get_the_title( \$".$this->var_name." ); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}

if ( $return_format == 'id' && $multiple_values == '1' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$post_ids ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php echo get_permalink( \$post_ids ); ?>\"><?php echo get_the_title( \$post_ids ); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}

if ( $return_format == 'object' && $multiple_values == '0' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php \$post = \$".$this->var_name."; ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php setup_postdata( \$post ); ?> ")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php wp_reset_postdata(); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}

if ( $return_format == 'object' && $multiple_values == '1' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$post ) :  ?>")."\n";
	echo $this->indent . htmlspecialchars("		<?php setup_postdata( \$post ); ?>")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php wp_reset_postdata(); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}
