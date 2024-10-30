<?php
/**
 * Plugin Name: Bulk Remove Users
 * Plugin URI: http://themeinthebox.com
 * Description:  A plugin for removing subscribers by giving email address in text area.
 * Version: 1.0
 * Author: ThemeintheBox
 * Author URI: http://themeinthebox.com
 * License: GPL2
 */
class juru_options_page {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	function admin_menu() {
		add_options_page(
			'Bulk Remove Users',
			'Bulk Remove Users',
			'manage_options',
			'juru_options_page_slug',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function  settings_page() {
		if(isset($_POST["juru_submit"])){
			$juru_users = $_POST["juru_users"];
			$juru_users = explode(",", $juru_users);
			$counter = 0;
			foreach($juru_users as $juru_user){
				$user = get_user_by( 'email', trim($juru_user));
				if($user){
					$user_meta=get_userdata($user->ID);
					$user_roles=$user_meta->roles;
					if (in_array("subscriber", $user_roles)){
						if(wp_delete_user( $user->ID ))
						$counter++;
					}
				}

			}
			$message = "";
			if($counter <= 1){
				$message = "$counter subscriber removed";
			}
			if($counter > 1){
				$message = "$counter subscribers removed";
			}

			echo "<span style='font-size:18px;border:1px dashed black;background:#3ce63b;color:black;'>$message</span>";
		}
		?>

        <h1>Remove Users</h1>
        <h3>Insert email addresses separated by commas</h3>
        <form method="post" action="">
        <textarea id="juru_users" name="juru_users" cols="60" rows="10" required></textarea><br>
        <input type="submit" value="Bulk Remove Users" name="juru_submit" class="button-primary"/>
		<?php
	}
}

new juru_options_page;
