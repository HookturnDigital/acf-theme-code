<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Content of theme code meta box
*/
class ACFTC_Locations {

	// Data from field group post object
	protected $field_group_post_ID = null;

	// Location rules
	protected $location_rules = array();

	// Locations that are excluded because they aren't really locations
	// (they relate to the backend visiblity of the field group)
	private static $locations_excluded = array(
		// ACF v4
		'user_type', // Logged in User Type
		'ef_user' // User
	);

	private static $locations_tc_pro = array(
		'current_user',
		'current_user_role',
		'user_form',
		'user_role',
		'attachment',
		'taxonomy',
		'comment',
		'widget',
		'block',
		'options_page',
	);

	/**
	 * ACFTC_Locations constructor
	 *
	 * @param WP_Post $post Post object for ACF field group
	 */
	public function __construct( $field_group_post_obj ) {

		if ( !empty( $field_group_post_obj ) ) {

			// Save field group post ID
			$this->field_group_post_ID = $field_group_post_obj->ID;

			// Save field group location rules
			$this->location_rules = $this->get_location_rules( $field_group_post_obj );

		}

	}

	/**
	* Get field group location rules
	*
	* @param Field group post object
	* @return Array of ACF location rule arrays eg.
	* Array (
	*   [param] => post
	*   [operator] => ==
	*   [value] => 1
	* )
	*/
	private function get_location_rules( $field_group_post_obj ) {

		// ACF v5
		if ( 'posts' == ACFTC_Core::$db_table ) {
			return $this->get_location_rules_from_posts_table( $field_group_post_obj );
		}

		// ACF v4
		elseif ( 'postmeta' == ACFTC_Core::$db_table ) {
			return $this->get_location_rules_from_postmeta_table( $field_group_post_obj );
		}

	}


	/**
	 * Get field group location rules from posts table (ACF v5)
	 *
	 * @param Field group post object
	 * @return Array of location rule arrays
	 */
	private function get_location_rules_from_posts_table( $field_group_post_obj ) {

		$location_rules = array();

		// Get location rules from field group post content
		// html entity decode added to fix issue with 'Disable the visual editor when writing'
		$field_group_post_content = maybe_unserialize( html_entity_decode( $field_group_post_obj->post_content ));

		if ( $field_group_post_content ) {
			foreach ( $field_group_post_content['location'] as $location_rule_group ) {

				foreach ( $location_rule_group as $location_rule ) {

					// Only include location rules that are actual locations
					if ( $this->is_included_location_rule( $location_rule ) ) {
						$location_rules[] = $location_rule;
					}
				}
			}
		}

		return $location_rules;

	}


	/**
	* Get all location rules for field group from postmeta table (ACF v4)
	*
	* @param Field group post object
	* @return Array of location rule arrays
	*/
	private function get_location_rules_from_postmeta_table( $field_group_post_obj ) {

		$location_rules = array();

		global $wpdb;

		// Prepend table prefix
		$table = $wpdb->prefix . 'postmeta';

		// Query postmeta table for location rules associated with this field group
		$query_results = $wpdb->get_results( "SELECT * FROM " . $table . " WHERE post_id = " . $field_group_post_obj->ID . " AND meta_key LIKE 'rule'" );

		foreach ( $query_results as $query_result ) {

			// Unserialize location rule data
			$location_rule = unserialize( $query_result->meta_value );

			// If location rule is excluded, skip to next location rule
			if ( ! ($this->is_included_location_rule( $location_rule ) ) ) {
				continue;
			}

			// Change ACF v4 location slugs to match ACF v5
			switch ( $location_rule['param'] ) {
				case 'ef_media':
					$location_rule['param'] = 'attachment';
					break;

				case 'ef_taxonomy':
					$location_rule['param'] = 'taxonomy';
					break;
			}

			// Remove data that is not required (so location rule format matches location rules retrieved from posts table)
			unset( $location_rule['order_no'] );
			unset( $location_rule['group_no'] );

			// Create and array of all location rules
			$location_rules[] = $location_rule;

		}

		return $location_rules;

	}


	/**
	* Exclude location rules that aren't really locations
	* (they relate to the backend visiblity of the field group)
	*
	* @param Array $location_rule
	* @return bool 
	*
	* Requires $this->$locations_excluded
	*
	*/
	private function is_included_location_rule( $location_rule ) {

		return ( ! in_array( $location_rule['param'], self::$locations_excluded ) );

	}


	/**
	 * Get locations HTML
	 * 
	 * @return string
	 */
	public function get_locations_html() {

		$args = array(
			'field_group_id' => $this->field_group_post_ID
			// no location argument included here, only used below
		);
		$parent_field_group= new ACFTC_Group( $args );

		// If no fields in field group: display notice
		// (needs to be done at this level because ACFTC Group class is used recursively)
		if ( empty( $parent_field_group->fields ) ) { 
			
			ob_start();?>

			<div class="acftc-intro-notice">
				<p>Create some fields and publish the field group to generate theme code.</p>
			</div>
			
			<?php return ob_get_clean();
		}

		// If all locations are excluded: render fields without location ui
		// elements (eg. only the Current User location is selected)
		if ( empty( $this->location_rules ) ) {
			return $parent_field_group->get_field_group_html();
		}

		ob_start();

		// If more than one location: render location select
		if ( count( $this->location_rules) > 1 ) {
			echo $this->get_location_select_html();
		}

		// Render ALL fields for every location
		foreach ( $this->location_rules as $index => $location_rule ) :

			echo $this->get_single_location_html( $location_rule, $index );

		endforeach;

        echo $this->get_after_field_group_notice_html();

		return ob_get_clean();

	}



	/**
	 * Get location name in a clean title case format
	 * for use in location select and titles
	 * 
	 * @param Array $location_rule
	 * Array (
	 *	[param] => block
	 *	[operator] => ==
	 *	[value] => acf/example
	 * ) 
	 * @return string 
	 * 
	 */
	private function get_location_clean_text( $location_rule ) { 

		if ( !$location_rule ) {
			return;
		}

		$location_name_title_case = ucwords( str_replace('_', ' ', $location_rule['param'] ) );

		if ( empty( $location_rule['value'] ) ) {
			$location_value_no_dashes = 'unknown';
		} else {
			$location_value_no_dashes = str_replace( '-' , ' ', $location_rule['value'] );
		}
		
		$location_value_clean = str_replace( array( 'category:', 'acf/' ) , '', $location_value_no_dashes );
		$location_value_title_case = ucwords( $location_value_clean );

		$location_operator_text = ($location_rule['operator'] === '==' ) ? '' : 'Not ';

		return "{$location_name_title_case} ({$location_operator_text}{$location_value_title_case})";

	}


	/**
	 * Render header for location select
	 * 
	 * @return string 
	 */
	private function get_location_select_html() { 
		
		ob_start();
		?>
<div class="inside acf-fields -left acf-locations">
	<div class="acf-field acf-field-select" data-name="style" data-type="select">
		<div class="acf-label">
			<label for="acf_field_group-style">Location</label>
			<p class="description">Select a location to see the relevant theme code</p>
		</div>
		<div class="acf-input">
			<select id="acftc-group-option" class="" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Select" data-allow_null="0">
<?php foreach ( $this->location_rules as $key => $location_rule ) : ?>
				<option value="acftc-group-<?php echo $key; ?>"><?php echo $this->get_location_clean_text( $location_rule ); ?></option>
<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>

<?php 
		return ob_get_clean(); 
	}


	/**
	 * Get HTML for a single location
	 *
	 * @param Array $location_rule
	 * Array (
	 *	[param] => block
	 *	[operator] => ==
	 *	[value] => acf/example
	 * ) 
	 * @param int $index Identifier for the location
	 * @return string 
	**/
	protected function get_single_location_html( $location_rule, $index ) {
		
		ob_start();

		// Wrapping div used for show and hide functionality

?>
<div id="acftc-group-<?php echo $index; ?>" class="location-wrap">
<?php 

			if ( in_array( $location_rule['param'], self::$locations_tc_pro ) ) {

				echo $this->get_locations_upgrade_notice_html( $location_rule );

			} else {

				$args = array(
					'field_group_id' => $this->field_group_post_ID,
					'location_rule_param' => $location_rule['param'] // included this time
				);
		
				$parent_field_group = new ACFTC_Group( $args ); 

				echo $parent_field_group->get_field_group_html(); 

			}

?>
</div>
<?php

		return ob_get_clean();

	}	


	/**
	 * Render upgrade link for location support
	 * 
	 * @param Array $location_rule
	 * Array (
	 *	[param] => block
	 *	[operator] => ==
	 *	[value] => acf/example
	 * ) 
	 * @return string 
	 */
	private function get_locations_upgrade_notice_html( $location_rule ) { 
		
		ob_start();

?>
	<div class="acftc-field-meta">
		<span class="acftc-field-meta__title" data-pseudo-content="<?php echo $this->get_location_clean_text( $location_rule ); ?>"></span>
	</div>

	<div class="acftc-field-code">
		<a href="#" class="acftc-field__copy acf-js-tooltip" title="Copy to Clipboard"></a>
		<pre class="line-numbers"><code class="language-php"><?php
		
			echo htmlspecialchars( "<?php // Upgrade to ACF Theme Code Pro for '" . $location_rule['param'] . "' location support. ?>" ) . "\n";
			echo htmlspecialchars( "<?php // Visit http://www.hookturn.io for more information. ?>" ) . "\n";
			echo htmlspecialchars( "<?php // Alternatively add a Post or Page option to the Location Rules for this field group. ?>" ) . "\n";

		?></code></pre>
	</div>
<?php

		return ob_get_clean(); 
    }


	/**
	 * Get HTML for any notices to be added below field group
	 * 
	 * @return string 
	 */
	protected function get_after_field_group_notice_html() { 
		
		ob_start();
        ?>
<div class="acftc-pro-notice">
    <a class="acftc-pro-notice__link" href="https://hookturn.io/downloads/acf-theme-code-pro/?utm_source=acftcfree" target="_blank">Upgrade to <strong>ACF Theme Code Pro</strong>.</a>
</div>

<?php 
		return ob_get_clean(); 
	}

}
