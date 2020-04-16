<?php
// Group field (based on the repeater field)

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// ACFTC_Group arguments
$field_group_id = $this->id;
$fields = NULL;
$nesting_arg = 0;
$sub_field_indent_count = $this->indent_count + ACFTC_Core::$indent_repeater;

$args = array(
	'field_group_id' => $field_group_id,
	'fields' => $fields,
	'nesting_level' => $nesting_arg + 1,
	'indent_count' => $sub_field_indent_count,
	'exclude_html_wrappers' => $this->exclude_html_wrappers
);

$group_field_group = new ACFTC_Group( $args );

// If group field has sub fields
if ( !empty( $group_field_group->fields ) ) {

	echo $this->indent . htmlspecialchars("<?php if ( have_rows( '" . $this->name ."'". $this->location_rendered_param . " ) ) : ?>")."\n";
	echo $this->indent . htmlspecialchars("	<?php while ( have_rows( '" . $this->name ."'". $this->location_rendered_param . " ) ) : the_row(); ?>")."\n";

	echo $group_field_group->get_field_group_html();

	echo $this->indent . htmlspecialchars("	<?php endwhile; ?>")."\n";
	echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";

}
// Group field has no sub fields
else {

	echo $this->indent . htmlspecialchars("<?php // warning: group field '" . $this->name . "' has no sub fields ?>")."\n";

}
