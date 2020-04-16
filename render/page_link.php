<?php
// Page link field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$multiple_values = isset( $this->settings['multiple'] ) ? $this->settings['multiple'] : ''; // Note: value is a string

if ($multiple_values == '0') {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name." = " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<a href=\"<?php echo esc_url( \$".$this->var_name."); ?>\"><?php echo esc_html( \$".$this->var_name." ); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}

if ($multiple_values == '1') {
	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_urls =  " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_urls ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_urls as \$".$this->var_name."_url ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("		<a href=\"<?php echo esc_url( \$".$this->var_name."_url ); ?>\"><?php echo esc_html( \$".$this->var_name."_url ); ?></a>")."\n";
	echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
}
