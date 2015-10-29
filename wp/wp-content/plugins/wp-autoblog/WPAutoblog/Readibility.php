<?php
require_once 'ComponentInterface.php';
class WPAutoblog_Readibility implements WPAutoblog_ComponentInterface
{
    private $token = '';
    private $enabled = false;

    public function __construct()
    {
        $this->token = get_option('readability_token');
    }

    public function isAvailable()
    {
        return $this->enabled && preg_match('/[0-9a-f]{40}/', $this->token);
    }

    public function enable()
    {
        $this->enabled = true;
    }

    public function parse($url)
    {
        $result = wp_remote_get('https://readability.com/api/content/v1/parser?url=' . urlencode($url) . '&token=' . $this->token);
        if (!is_array($result)) {
            $result = wp_remote_get('https://readability.com/api/content/v1/parser?url=' . urlencode($url) . '&token=' . $this->token, array('timeout' => 0.01));
            if (!is_array($result)) {
                return false;
            }
        }
        $response = json_decode($result['body'], true);
        if ($response['error']) {
            return false;
        }
        return $response['content'];
    }

    public function addSettings()
    {
        register_setting( 'wpab-settings', 'readability_token' );
    }

    public function displaySettings()
    {
        echo '<h3 class="title">' . __('Readibility', 'wp-autoblog') . '</h3>';
        echo '<p>' . __('If you wish to expand shortened feed contents (i.e. &quot;Read More&quot; posts) via Readibility API, please specify your Token here. <a href="https://www.readability.com/developers/api/parser" target="_blank">More info</a>.', 'wp-autoblog') . '</p>';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="readability_token">Token</label></th>';
        echo '<td><input id="readability_token" class="regular-text code" type="text" value="' . get_option('readability_token') . '" name="readability_token" /></td>';
        echo '</tr>';
        echo '</table>';
    }

}
