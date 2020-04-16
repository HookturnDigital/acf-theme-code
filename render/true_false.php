<?php
// True False field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

echo $this->indent . htmlspecialchars("<?php if ( " . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ) == 1 ) : ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php // echo 'true'; ?>")."\n";
echo $this->indent . htmlspecialchars("<?php else : ?>")."\n";
echo $this->indent . htmlspecialchars("	<?php // echo 'false'; ?>")."\n";
echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
