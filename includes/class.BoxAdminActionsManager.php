<?php
class Box_Admin_Actions_Manager {
  
  static function do_action( $actionName ) {
    switch ( $actionName ) {
      case "connect":
	Box_Admin_Actions_Manager::connect();
	return true;
	
      case "disconnect":
	Box_Admin_Actions_Manager::disconnect();
	return true;
	
      case "upload":
	Box_Admin_Actions_Manager::upload();
	return true;
	
      case "download":
	Box_Admin_Actions_Manager::download();
	return true;
	
      case "remove":
	Box_Admin_Actions_Manager::remove();
	return true;
	
      case "poweroff":
	Box_Admin_Actions_Manager::poweroff();
	return true;
	
      case "reboot":
	Box_Admin_Actions_Manager::reboot();
	return true;

      case "upgrade":
	Box_Admin_Actions_Manager::upgrade();
	return true;
	
      default:
	return false;
    }
  }

  static function connect( ) {
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminWifiManager.php');
    $wifiManager = new Box_Admin_Wifi_Manager();
    $wifiManager->connect( $_POST[ 'essid' ], $_POST[ 'password' ] );
  }
  
  static function disconnect( ) {
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminWifiManager.php');
    $wifiManager = new Box_Admin_Wifi_Manager();
    $wifiManager->disconnect();
  }
  
  static function upload( ) {
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminSyncManager.php');
    
    $syncManager = new Box_Admin_Sync_Manager();
    $syncManager->upload( $_POST[ 'blog' ] );
  }
  
  static function download( ) {
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminSyncManager.php');
    
    $syncManager = new Box_Admin_Sync_Manager();
    $syncManager->download( $_POST[ 'blog' ] );
  }
  
  static function remove() {
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminSyncManager.php');
    
    $syncManager = new Box_Admin_Sync_Manager();
    $syncManager->remove( $_POST[ 'blog' ] );
  }

  static function poweroff( ) {
    exec(
      'sudo poweroff',
      $_GET[ 'script_output' ],
      $script_return
    );
  }

  static function reboot( ) {
    exec(
      'sudo reboot',
      $_GET[ 'script_output' ],
      $script_return
    );
  }

  static function upgrade( ) {
    exec(
      'sudo /var/www/html/wordpress/wp-content/plugins/box_administration/upgrade.sh',
      $_GET[ 'upgrade_output' ],
      $upgrade_return
    );
  }
}
?>
