<?php
/*
Plugin Name: Box administration
 */

require_once '/var/edbox/conf/PHP/edbox.conf.php';
require 'includes/class.BoxAdminMain.php';

function my_plugin_activate() {
  $path = __DIR__ . "/install.sh";
  add_option( 'Activated_Plugin', 'Plugin-Slug' );

  exec(
    "sudo '$path' '" . __DIR__ . "' '/var/'",
    $_GET['scr_return'],
    $script_output
  );

  wp_die(var_dump($_GET['scr_return']));
  /* activation code here */
}
register_activation_hook( __FILE__, 'my_plugin_activate' );

Box_Admin_Manager::Construct();