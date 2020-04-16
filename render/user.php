<?php
// User field

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$multiple_values = isset( $this->settings['multiple'] ) ? $this->settings['multiple'] : ''; // Note value is a string
$return_format = isset( $this->settings['return_format'] ) ? $this->settings['return_format'] : '';

if ( $return_format == 'array' ) { 

    if (  $multiple_values == '0' ) { 
        echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. ' = ' . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("	<a href=\"<?php echo get_author_posts_url( \$".$this->var_name."['ID'] ); ?>\"><?php echo esc_html( \$".$this->var_name."['display_name'] ); ?></a>")."\n"; // get_author_posts_url() is used because $user['user_url'] was empty in testing
        echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
    }

    if (  $multiple_values == '1' ) { 
        echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. ' = ' . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$user ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("		<a href=\"<?php echo get_author_posts_url( \$user['ID'] ); ?>\"><?php echo esc_html( \$user['display_name'] ); ?></a>")."\n"; // get_author_posts_url() is used because $user['user_url'] was empty in testing
        echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
    }

}

if ( $return_format == 'object' ) { 

    if ( $multiple_values == '0' ) { 
        echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. ' = ' . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("	<a href=\"<?php echo get_author_posts_url( \$".$this->var_name."->ID ); ?>\"><?php echo esc_html( \$".$this->var_name."->display_name ); ?></a>")."\n"; // get_author_posts_url() is used because $user->user_url was empty in testing
        echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
    }

    if ( $multiple_values == '1' ) { 
        echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. ' = ' . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$user ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("		<a href=\"<?php echo get_author_posts_url( \$user->ID ); ?>\"><?php echo esc_html( \$user->display_name ); ?></a>")."\n"; // get_author_posts_url() is used because $user->user_url was empty in testing
        echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
    }

}

if ( $return_format == 'id' ) { 

    if ( $multiple_values == '0' ) { 
        echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. ' = ' . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("	<?php \$user_data = get_userdata( \$".$this->var_name." ); ?>")."\n";
        echo $this->indent . htmlspecialchars("	<?php if ( \$user_data ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("		<a href=\"<?php echo get_author_posts_url( \$".$this->var_name." ); ?>\"><?php echo esc_html( \$user_data->display_name ); ?></a>")."\n"; // get_author_posts_url() is used because $user_data->user_url was empty in testing
        echo $this->indent . htmlspecialchars("	<?php endif; ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
    }

    if ( $multiple_values == '1' ) { 
        echo $this->indent . htmlspecialchars("<?php \$".$this->var_name. ' = ' . $this->get_field_method . "( '" . $this->name ."'". $this->location_rendered_param . " ); ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php if ( \$".$this->var_name." ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("	<?php foreach ( \$".$this->var_name." as \$user_id ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("		<?php \$user_data = get_userdata( \$user_id ); ?>")."\n";
        echo $this->indent . htmlspecialchars("		<?php if ( \$user_data ) : ?>")."\n";
        echo $this->indent . htmlspecialchars("			<a href=\"<?php echo get_author_posts_url( \$user_id ); ?>\"><?php echo esc_html( \$user_data->display_name ); ?></a>")."\n"; // get_author_posts_url() is used because $user_data->user_url was empty in testing
        echo $this->indent . htmlspecialchars("		<?php endif; ?>")."\n";
        echo $this->indent . htmlspecialchars("	<?php endforeach; ?>")."\n";
        echo $this->indent . htmlspecialchars("<?php endif; ?>")."\n";
    }

}