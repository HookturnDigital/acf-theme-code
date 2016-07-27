<?php
// File field

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit; 


if ( "postmeta" == ACFTC_Core::$db_table ) { // ACF
	$return_format = $this->settings['save_format'];
} elseif ( "posts" == ACFTC_Core::$db_table ) { // ACF PRO
	$return_format = $this->settings['return_format'];
}

// If image is returned as an array
if ( $return_format == 'array' ) {
	echo $this->indent . htmlspecialchars("<?php \$file = " . $this->get_field_method . "('".$this->name."'); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if( \$file ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$file['url']; ?>\" /><?php echo \$file['filename']; ?></a>"."\n");
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}

// If image is returned as an object (v4)
if ( $return_format == 'object') {
	echo $this->indent . htmlspecialchars("<?php \$file = " . $this->get_field_method . "('".$this->name."'); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if( \$file ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$file['url']; ?>\" /><?php echo \$file['title']; ?></a>"."\n");
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}


// If file is returned as a ID
if ( $return_format == 'id' ) {
	echo $this->indent . htmlspecialchars("<?php \$file = " . $this->get_field_method . "('".$this->name."'); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if( \$file ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php \$url = wp_get_attachment_url( \$file ); ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$url; ?>\">Download File</a>")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}

// If file is returned as a URL
if ( $return_format == 'url' ) {
	echo $this->indent . htmlspecialchars("<?php if( ".$this->get_field_method . "('".$this->name."') ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php the_field('".$this->name."'); ?>\">Download File</a>")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>"."\n");
}
