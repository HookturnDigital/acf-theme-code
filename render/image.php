<?php
// Image field

/* 
 * This file is also included in:
 * 	pro/render/qtranslate_image.php
 * 	pro/render/image_aspect_ratio_crop.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( "postmeta" == ACFTC_Core::$db_table ) { // ACF v4
	$return_format = isset( $this->settings['save_format'] ) ? $this->settings['save_format'] : '';
} elseif ( "posts" == ACFTC_Core::$db_table ) { // ACF v5
	$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';
}

// If image is returned as an array (postmeta / v5) or an object (posts / v4)
if ( $return_format == 'array' || $return_format == 'object'  ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<img src=\"<?php echo esc_url( \$".$this->var_name."['url'] ); ?>\" alt=\"<?php echo esc_attr( \$".$this->var_name."['alt'] ); ?>\" />")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>\n");
}

// If image is returned as a URL
if ( $return_format == 'url' ) {
	echo $this->indent . htmlspecialchars("<?php if ( " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ) ) : ?>\n");
	echo $this->indent . htmlspecialchars("	<img src=\"<?php " . $this->the_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>\" />\n");
	echo $this->indent . htmlspecialchars("<?php endif ?>\n");
}

// If image is returned as an ID
if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php \$size = 'full'; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php echo wp_get_attachment_image( \$".$this->var_name.", \$size ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>\n");
}
