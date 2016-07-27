<?php
// Post Object field

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit; 

// Check if single or multiple values can be stored
$multiple_values = $this->settings['multiple'];

// If single
if($multiple_values == 0) {
	echo $this->indent . htmlspecialchars("<?php \$post_object = " . $this->get_field_method . "('" . $this->name . "'); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if( \$post_object ): ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php \$post = \$post_object; ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php setup_postdata( \$post ); ?> ")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php wp_reset_postdata(); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}

// If multiple
if($multiple_values == 1) {
	echo $this->indent . htmlspecialchars("<?php \$post_objects = " . $this->get_field_method . "('" . $this->name . "'); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if( \$post_objects ): ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach( \$post_objects as \$post):  ?>")."\n";
	echo $this->indent . htmlspecialchars("		<?php setup_postdata(\$post); ?>")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php the_permalink(); ?>\"><?php the_title(); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php wp_reset_postdata(); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}
