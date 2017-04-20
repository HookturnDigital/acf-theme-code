<?php
// File field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( "postmeta" == ACFTC_Core::$db_table ) { // ACF
	$return_format = isset( $this->settings['save_format'] ) ? $this->settings['save_format'] : '';
} elseif ( "posts" == ACFTC_Core::$db_table ) { // ACF PRO
	$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';
}

// If image is returned as an array
if ( $return_format == 'array' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->name." = " . $this->get_field_method . "( '".$this->name."' ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name." ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$".$this->name."['url']; ?>\"><?php echo \$".$this->name."['filename']; ?></a>"."\n");
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}

// If image is returned as an object (v4)
if ( $return_format == 'object') {
	echo $this->indent . htmlspecialchars("<?php \$".$this->name." = " . $this->get_field_method . "( '".$this->name."' ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name." ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$".$this->name."['url']; ?>\"><?php echo \$".$this->name."['title']; ?></a>"."\n");
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}


// If file is returned as a ID
if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$".$this->name." = " . $this->get_field_method . "( '".$this->name."' ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name.") { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php \$url = wp_get_attachment_url( \$".$this->name." ); ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$url; ?>\">Download File</a>")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}

// If file is returned as a URL
if ( $return_format == 'url' ) {
	echo $this->indent . htmlspecialchars("<?php if ( ".$this->get_field_method . "( '".$this->name."' ) ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php " . $this->the_field_method . "( '".$this->name."' ); ?>\">Download File</a>")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}
