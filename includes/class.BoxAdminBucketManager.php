<?php

require_once( BUCKET_CONF_PHP_FILE );

class Box_Admin_Bucket_Manager {
  static function list_files( $repositoryName ) {
    $base_command = 'sudo ' . GET_BLOG_SCRIPT . ' ' . BUCKET_URL;
    $array_list = array();
    
    if ( is_array( $repositoryName ) ) {
      foreach ( $repositoryName as $repo ) {
	exec(
	  $base_command . $repo,
	  $_GET['command_output'],
	  $exec_return
	);
      }
      $array_list = array_merge( $array_list, $_GET[ 'command_output' ] );
    }
    else {
      exec(
	$base_command . $repositoryName,
	$_GET['command_output'],
	$exec_return
      );
      $array_list = $_GET[ 'command_output' ];
    }
    return ($array_list);
  }
  
  static function download_file( $fileName ) {
    $bucket_path = BUCKET_URL . $fileName;
    $local_path = TMP_LOCAL_PATH;

    exec (
      'sudo ' . SYNC_BLOG_SCRIPT . " '$bucket_path' '$local_path'",// 1>/dev/null 2>&1",
      $_GET[ 'scr_output' ],
      $script_return
    );
    
    return array( $_GET[ 'scr_output' ], $script_return );
  }

  static function upload_file( $fileName ) {
    $local_path = TMP_LOCAL_PATH . $fileName . COMPRESS_FILE_EXTENSION;
    $bucket_path = BUCKET_URL . PERSONNAL_UID . "/";
    
    exec (
      'sudo ' . SYNC_BLOG_SCRIPT . " '$local_path' '$bucket_path' 1>/dev/null 2>&1",
      $_GET[ 'scr_output' ],
      $script_return
    );
    
    return array( $_GET[ 'scr_output' ], $script_return );
  }

  static function remove_file( $fileName ) {
    $bucket_path = BUCKET_URL . $fileName;

    exec (
      'sudo ' . REMOVE_BLOG_SCRIPT . " '$bucket_path' 1>/dev/null 2>&1",
      $_GET[ 'scr_output' ],
      $script_return
    );

    return array( $_GET[ 'scr_output' ], $script_return );
  }
}
?>
