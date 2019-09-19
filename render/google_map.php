<?php
// Google map / location field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Location - split the array
echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. " = " .  $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) { ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."['address']; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."['lat']; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php echo \$".$this->var_name."['lng']; ?>")."\n";
echo $this->indent . htmlspecialchars("<?php } ?>\n");
