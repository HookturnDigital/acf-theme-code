<?php
// File field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( "postmeta" == ACFTC_Core::$db_table ) { // ACF v4
	$return_format = isset( $this->settings['save_format'] ) ? $this->settings['save_format'] : '';
} elseif ( "posts" == ACFTC_Core::$db_table ) { // ACF v5
	$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';
}

// If image is returned as an array
if ( $return_format == 'array' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo esc_url( \$".$this->var_name."['url'] ); ?>\"><?php echo esc_html( \$".$this->var_name."['filename'] ); ?></a>"."\n");
	echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");
}

// If file is returned as a URL
if ( $return_format == 'url' ) {

	$i18n_str_download_file = __( 'Download File', 'acf-theme-code' );

	echo $this->indent . htmlspecialchars("<?php if ( ".$this->get_field_method . "( '". $this->name ."'". $this->location_rendered_param . " ) ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php " . $this->the_field_method . "( '". $this->name ."'". $this->location_rendered_param . " ); ?>\">" . $i18n_str_download_file . "</a>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");
}

// If file is returned as a ID
if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php \$url = wp_get_attachment_url( \$".$this->var_name." ); ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo esc_url( \$url ); ?>\">Download File</a>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");
}

// If image is returned as an object (v4)
// TODO Remove this
if ( $return_format == 'object') {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo esc_url( \$".$this->var_name."['url'] ); ?>\"><?php echo\$".$this->var_name."['title']; ?></a>"."\n");
	echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");
}