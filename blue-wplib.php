<?php

	/*
		Bluestar's
		WordPress Advanced Library v0.2
		~ magic functions ~
		~ mod: 2018.03.05 ~


		v0.2
		* Added: "edit_user_link" function
		* Added: "get_excerpt_by_id" function

		v0.1
		* Initial release

	*/


	// get the current post type in any cases
	function get_current_post_type() {

	  global $post, $typenow, $current_screen;

	  // we have a post so we can just get the post type from that
	  if ( $post && $post->post_type )
		return $post->post_type;

	  // check the global $typenow - set in admin.php
	  elseif( $typenow )
		return $typenow;

	  // check the global $current_screen object - set in sceen.php
	  elseif( $current_screen && $current_screen->post_type )
		return $current_screen->post_type;

	  // lastly check the post_type querystring
	  elseif( isset( $_REQUEST['post_type'] ) )
		return sanitize_key( $_REQUEST['post_type'] );

	  // aw, we still don't know the post type!
	  return null;

	}

	// get the full name of the given user's role instead of its ID
	function get_user_role_fullname($user_id, $role_key=""){

		// grab all roles
		$current_role_name = "";
		$all_roles = wp_roles()->roles;

		// get current user's role
		$user_role_str = $role_key;
		if (empty($role_key)){
			$user = get_userdata($user_id);
			$user_role = $user->roles;
			if (!empty($user_role)){
				$user_role_str = $user_role[0];
			}
		}

		// loop through all roles and match user's current role
		foreach ($all_roles as $role_key => $role_details) {
			if ($role_key == $user_role_str){
				$current_role_name = $role_details['name'];
			}
		}

		// return its full role name
		return $current_role_name;

	}

	// get the excerpt of a given post
	function get_excerpt($post_id=null, $limit=100){
		
		$excerpt = "";
		if (empty($post_id)){
			$post_id = get_the_ID();
		}

		if ($post_id){
			// try to get the real excerpt
			$excerpt = get_the_excerpt($post_id);
			if (empty($excerpt)){
				// generate excerpt from content
				$excerpt = get_the_content($post_id);
			}

			// strip it by the limit
			$excerpt = strip_shortcodes($excerpt);
			$excerpt = strip_tags($excerpt);
			$excerpt = substr($excerpt, 0, $limit);
			$excerpt .= " ";
			$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
		}
		
		// return excerpt
		return $excerpt;

	}

	// generate edit user link
	function edit_user_link($user_id){
		echo '<a href="'. get_edit_user_link($user_id) .'" target="_blank">'. esc_attr( get_userdata($user_id)->user_login ) .'</a>';
	}

?>