<?php
// Checkbox field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

if ($return_format == 'value' || $return_format == 'label') {

    echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_checked_".$return_format."s = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
    echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_checked_".$return_format."s ) : ?>")."\n";
    echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_checked_".$return_format."s as \$".$this->var_name."_".$return_format." ): ?>")."\n";
    echo $this->indent . htmlspecialchars("		<?php echo esc_html( \$".$this->var_name."_".$return_format." ); ?>")."\n";
    echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
    echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

} elseif ($return_format == 'array') {

    echo $this->indent . htmlspecialchars("<?php \$".$this->var_name."_checked_options = ". $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
    echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name."_checked_options ): ?>")."\n";
    echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name."_checked_options as \$".$this->var_name."_checked_option ): ?>")."\n";
    echo $this->indent . htmlspecialchars("		<?php echo esc_html( \$".$this->var_name."_checked_option['label'] ); ?>")."\n";
    echo $this->indent . htmlspecialchars("		<?php echo esc_html( \$".$this->var_name."_checked_option['value'] ); ?>")."\n";
    echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
    echo $this->indent . htmlspecialchars("<?php endif; ?>"."\n");

} 
