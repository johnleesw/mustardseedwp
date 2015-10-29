<?php

/**
 * Backend Class for use in all Yoast plugins
 * Version 0.2
 */

if ( ! class_exists( 'Yoast_Plugin_Admin' ) ) {
	class Yoast_Plugin_Admin {

		var $hook = '';
		var $filename = '';
		var $longname = '';
		var $shortname = '';
		var $optionname = '';
		var $homepage = '';
		var $accesslvl = 'manage_options';
        var $toc = '';

		function __construct() {
		}

		function config_page_styles() {
			if ( isset( $_GET['page'] ) && $_GET['page'] == $this->hook ) {
				wp_enqueue_style( 'dashboard' );
				wp_enqueue_style( 'wp-admin' );
				wp_enqueue_style( $this->hook . '-admin-css', WP_CONTENT_URL . '/plugins/' . plugin_basename( dirname( __FILE__ ) ) . '/yst_plugin_tools.css' );
			}
		}

		function register_settings_page() {
			add_options_page( $this->longname, $this->shortname, $this->accesslvl, $this->hook, array(
				$this,
				'config_page'
			) );
		}

		function plugin_options_url() {
			return admin_url( 'options-general.php?page=' . $this->hook );
		}

		/**
		 * Add a link to the settings page to the plugins list
		 */
		function add_action_link( $links, $file ) {
			static $this_plugin;
			if ( empty( $this_plugin ) ) {
				$this_plugin = $this->filename;
			}
			if ( $file == $this_plugin ) {
				$settings_link = '<a href="' . $this->plugin_options_url() . '">' . __( 'Settings', 'slideshare' ) . '</a>';
				array_unshift( $links, $settings_link );
			}

			return $links;
		}

		function config_page() {
		}

		function config_page_scripts() {
			if ( isset( $_GET['page'] ) && $_GET['page'] == $this->hook ) {
				wp_enqueue_script( 'postbox' );
				wp_enqueue_script( 'dashboard' );
			}
		}

		/**
		 * Create a Checkbox input field
		 */
		function checkbox( $id, $label ) {
			$options = get_option( $this->optionname );

			return '<input type="checkbox" id="' . $id . '" name="' . $id . '"' . checked( $options[ $id ], true, false ) . '/> <label for="' . $id . '">' . $label . '</label><br/>';
		}

		/**
		 * Create a Text input field
		 */
		function textinput( $id, $label ) {
			$options = get_option( $this->optionname );

			return '<label for="' . $id . '">' . $label . ':</label><br/><input size="45" type="text" id="' . $id . '" name="' . $id . '" value="' . $options[ $id ] . '"/><br/><br/>';
		}

		/**
		 * Create a potbox widget
		 */
		function postbox( $id, $title, $content ) {
			?>
			<div id="<?php echo $id; ?>" class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class="hndle"><span><?php echo $title; ?></span></h3>

				<div class="inside">
					<?php echo $content; ?>
				</div>
			</div>
			<?php
			$this->toc .= '<li><a href="#' . $id . '">' . $title . '</a></li>';
		}


		/**
		 * Create a form table from an array of rows
		 */
		function form_table( $rows ) {
			$content = '<table class="form-table">';
			$i       = 1;
			foreach ( $rows as $row ) {
				$class = '';
				if ( $i > 1 ) {
					$class .= 'yst_row';
				}
				if ( $i % 2 == 0 ) {
					$class .= ' even';
				}
				$content .= '<tr class="' . $row['id'] . '_row ' . $class . '"><th valign="top" scrope="row">';
				if ( isset( $row['id'] ) && $row['id'] != '' ) {
					$content .= '<label for="' . $row['id'] . '">' . $row['label'] . ':</label>';
				} else {
					$content .= $row['label'];
				}
				$content .= '</th><td valign="top">';
				$content .= $row['content'];
				$content .= '</td></tr>';
				if ( isset( $row['desc'] ) && ! empty( $row['desc'] ) ) {
					$content .= '<tr class="' . $row['id'] . '_row ' . $class . '"><td colspan="2" class="yst_desc"><small>' . $row['desc'] . '</small></td></tr>';
				}

				$i ++;
			}
			$content .= '</table>';

			return $content;
		}

		/**
		 * Create a "plugin like" box.
		 */
		function plugin_like( $hook = '' ) {
			if ( empty( $hook ) ) {
				$hook = $this->hook;
			}
			$content = '<p>' . __( 'Why not do any or all of the following:', $this->hook ) . '</p>';
			$content .= '<ul>';
			$content .= '<li><a href="' . $this->homepage . '">' . __( 'Link to it so other folks can find out about it.', $this->hook ) . '</a></li>';
			$content .= '<li><a href="http://wordpress.org/extend/plugins/' . $hook . '/">' . __( 'Give it a good rating on WordPress.org.', $this->hook ) . '</a></li>';
			$content .= '<li><a href="http://wordpress.org/extend/plugins/' . $hook . '/">' . __( 'Let other people know that it works with your WordPress setup.', $this->hook ) . '</a></li>';
			$content .= '</ul>';
			$this->postbox( $hook . 'like', __( 'Like this plugin?', $this->hook ), $content );
		}

	}
}
