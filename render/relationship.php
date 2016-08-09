<?php
// Relationship field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Get return format
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

// If returned as object
if ( $return_format == 'object' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->name." = " . $this->get_field_method . "( '" . $this->name . "' ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name." ): ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->name." as \$post ):  ?>")."\n";
	echo $this->indent . htmlspecialchars("		<?php setup_postdata (\$post); ?>")."\n";
	echo $this->indent . htmlspecialchars("			<a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php wp_reset_postdata(); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}

// IF returned as ID
if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->name." = " . $this->get_field_method . "( '" . $this->name . "' ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name." ): ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->name." as \$p ): ?>")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php echo get_permalink( \$p ); ?>\"><?php echo get_the_title( \$p ); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}
