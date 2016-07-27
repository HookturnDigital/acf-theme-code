<?php
// Checkbox field

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit; 

// Loop over show multiple checkboxes
echo $this->indent . htmlspecialchars("<?php \$field = ". $this->get_field_object_method ."('" . $this->name . "'); ?>")."\n";
echo $this->indent . htmlspecialchars("<?php \$value = \$field['value']; ?>")."\n";
echo $this->indent . htmlspecialchars("<?php \$choices = \$field['choices']; ?>")."\n";
echo $this->indent . htmlspecialchars("<?php if( \$value ): ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php foreach( \$value as \$v ): ?>")."\n";
echo $this->indent . htmlspecialchars("		<?php echo \$choices[ \$v ]; ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";

