<?php // Radio Button field

/* 
 * This file is also included in:
 *  render/button_group.php
 * 	pro/render/color_palette.php
 * 	pro/render/swatch.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

if ( $return_format == 'value' || $return_format == 'label' ) {

	echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";

} elseif( $return_format == 'array' ) {

	echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_selected_option = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
    echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_selected_option ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php echo esc_html( \$".$this->var_name."_selected_option['value'] ); ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php echo esc_html( \$".$this->var_name."_selected_option['label'] ); ?>")."\n";
    echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

}
