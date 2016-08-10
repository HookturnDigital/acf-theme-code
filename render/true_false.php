<?php
// True False field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

echo $this->indent . htmlspecialchars("<?php if ( " . $this->get_field_method . "( '" . $this->name . "' ) == 1 ) { ")."\n";
echo $this->indent . htmlspecialchars(" // echo 'true'; ")."\n";
echo $this->indent . htmlspecialchars("} else { ")."\n";
echo $this->indent . htmlspecialchars(" // echo 'false'; ")."\n";
echo $this->indent . htmlspecialchars("} ?>")."\n";
