<?php
// Group field (based on the repeater field)

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// ACFTC_Group arguments
$field_group_id = $this->id;
$fields = NULL;
$nesting_arg = 0;
// $sub_field_indent_count = $this->indent_count + ACFTC_Core::$indent_repeater;
$sub_field_indent_count = ACFTC_Core::$indent_repeater;
$field_location = '';

$group_field_group = new ACFTC_Group( $field_group_id, $fields, $nesting_arg + 1, $sub_field_indent_count, $field_location );

// If group field has sub fields
if ( !empty( $group_field_group->fields ) ) {

	echo $this->indent . htmlspecialchars("<?php if ( have_rows( '" . $this->name ."'". $this->location . " ) ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php while ( have_rows( '" . $this->name ."'". $this->location . " ) ) : the_row(); ?>")."\n";

	$group_field_group->render_field_group();

	echo $this->indent . htmlspecialchars("	<?php endwhile; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";

}
// Group field has no sub fields
else {

	echo $this->indent . htmlspecialchars("<?php // warning: group '" . $this->name . "' has no sub fields ?>")."\n";

}
