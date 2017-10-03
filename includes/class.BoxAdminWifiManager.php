<?php
class Box_Admin_Wifi_Manager {
  /** PRIVATE FUCNTIONS **/

  /** PUBLIC FUNCTIONS **/
  public function connect( $essid, $password ) {
    if ( empty( $essid ) || empty( $password ) )
      exit;
    exec(
      'sudo ' . CONNECT_SCRIPT . ' "' . $_POST[ 'essid' ] . '" "' . $_POST[ 'password' ] . '" 2>&1',
      $_GET[ 'connect_output' ],
      $script_return
    );
    echo "{\"returnValue\":$script_return,\"output\":[";
    $i = 1; 
    foreach( $_GET[ 'connect_output' ] as $outputLine ) {
      echo "\"" . $outputLine . "\"";
      if ( $i < sizeof( $_GET[ 'connect_output' ] ) )
	echo ",";
      $i++;
    }
    echo "]}";
  }

  public function disconnect( ) {
    exec(
      'sudo ' . DISCONNECT_SCRIPT . ' 2>&1',
      $_GET[ 'disconnect_output' ],
      $script_return
    );
    echo "{\"returnValue\":$script_return,\"output\":[";
    $i = 1; 
    foreach( $_GET[ 'disconnect_output' ] as $outputLine ) {
      echo "\"" . $outputLine . "\"";
      if ( $i < sizeof( $_GET[ 'disconnect_output' ] ) )
	echo ",";
      $i++;
    }
    echo "]}";
  }

  static function is_connected( ) {
    $connected = @fsockopen("ed4free.org", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;
  }
}
?>
