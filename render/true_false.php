<?php
// True False field

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit; 


echo $this->indent . htmlspecialchars("<?php if( " . $this->get_field_method . "('" . $this->name . "') == 1 ) { ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php // true ?>")."\n";
echo $this->indent . htmlspecialchars("<?php } else { ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php // false ?>")."\n";
echo $this->indent . htmlspecialchars("<?php } ?>")."\n";
