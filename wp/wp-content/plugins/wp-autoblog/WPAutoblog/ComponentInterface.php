<?php
interface WPAutoblog_ComponentInterface {
    public function isAvailable();
    public function enable();
    public function addSettings();
    public function displaySettings();
}
