<?php
// Link field added in ACF 5.6
// We no longer support the third party link field with the same name

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

if( $return_format == 'array' ) {

	echo $this->indent . htmlspecialchars("<?php \$".$this->name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name." ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$".$this->name."['url']; ?>\" target=\"<?php echo \$".$this->name."['target']; ?>\"><?php echo \$".$this->name."['title']; ?></a>")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>\n");
}

if( $return_format == 'url' ) {

	echo $this->indent . htmlspecialchars("<?php \$".$this->name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->name." ) { ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo \$" . $this->name . "; ?>\"><?php echo \$" . $this->name . "; ?></a>")."\n";
	echo $this->indent . htmlspecialchars("<?php } ?>\n");

}
