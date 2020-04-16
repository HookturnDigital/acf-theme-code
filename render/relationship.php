<?php
// Relationship field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

if ( $return_format == 'object' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$post ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("		<?php setup_postdata ( \$post ); ?>")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php wp_reset_postdata(); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}

if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$post_ids ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php echo get_permalink( \$post_ids ); ?>\"><?php echo get_the_title( \$post_ids ); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}
