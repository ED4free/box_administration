<?php
/*
Plugin Name: Box administration
 */

require_once '/var/edbox/conf/PHP/edbox.conf.php';
require 'includes/class.BoxAdminMain.php';

Box_Admin_Manager::Construct();
