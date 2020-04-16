<?php
// Google Map field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. " = " .  $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."['address']; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."['lat']; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."['lng']; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."['zoom']; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php \$optional_data_keys = array('street_number', 'street_name', 'city', 'state', 'post_code', 'country'); ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php foreach ( \$optional_data_keys as \$key ) : ?>")."\n";
echo $this->indent . htmlspecialchars("		<?php if ( isset( \$".$this->var_name."[ \$key ] ) ) : ?>")."\n";
echo $this->indent . htmlspecialchars("			<?php echo \$".$this->var_name."[ \$key ]; ?>")."\n";
echo $this->indent . htmlspecialchars("		<?php endif; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
echo $this->indent . htmlspecialchars("<?php endif; ?>\n");
