<?php
require 'Log.php';
class WPAutoblog_Admin
{
    private $components = array();

    public function init()
    {
        $this->initActions();
    }

    private function initActions()
    {
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_action('admin_init', array($this, 'initSettings'));
    }

    public function initSettings()
    {
        foreach ($this->components as $component) {
            $component->addSettings();
        }
    }

    public function addAdminMenu()
    {
        add_menu_page(__('WordPress Autoblog', WPAB_ID), __('WP Autoblog', WPAB_ID), 'manage_options', WPAB_ID, array($this, 'displayAdminMenu'), 'dashicons-admin-site', '3');
        add_submenu_page(WPAB_ID, __('Add Blog Feed', WPAB_ID), __('Add Blog Feed', WPAB_ID), 'manage_options', "post-new.php?post_type=wpab_blog_feed");
        add_submenu_page(WPAB_ID, __('Logs', WPAB_ID), __('Logs', WPAB_ID), 'manage_options', WPAB_ID . '-logs', array('WPAutoblog_Log', 'displayMenu'));
        add_submenu_page(WPAB_ID, __('Stats', WPAB_ID), __('Stats', WPAB_ID), 'manage_options', WPAB_ID . '-stats', array('WPAutoblog_Stat', 'displayMenu'));
        if (count($this->components) > 0) {
            add_submenu_page(WPAB_ID, __('Settings', WPAB_ID), __('Settings', WPAB_ID), 'manage_options', WPAB_ID . '-settings', array($this, 'displaySettings'));
        }
    }

    public function registerComponent($component)
    {
        $this->components[] = $component;
        $component->enable();
    }

    public function displaySettings()
    {
        $this->checkPermissions();

        echo '<div class="wrap">';
        echo '<h2>' . __('WordPress Autoblog Settings', WPAB_ID) . '</h2>';
        echo '<form action="options.php" method="post">';

        settings_fields(WPAB_ID . '-settings');
        do_settings_sections( WPAB_ID . '-settings' );

        foreach ($this->components as $component) {
            $component->displaySettings();
        }

        submit_button();
        echo '</form>';
        echo '</div>';
    }

    private function checkPermissions()
    {
        if (!current_user_can('manage_options'))
        {
            wp_die( __('You do not have sufficient permissions to access this page.', WPAB_ID) );
        }
    }
}
