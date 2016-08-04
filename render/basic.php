<?php
// Basic fields

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// This is used for multiple 'basic' fields that 'fail gracefully'
echo $this->indent . htmlspecialchars("<?php " . $this->the_field_method . "( '" . $this->name ."' ); ?>")."\n";
