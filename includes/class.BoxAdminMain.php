<?php

require_once( 'class.BoxAdminMetaBox.php' );
require_once( 'class.BoxAdminMenuPage.php' );

class Box_Admin_Manager {
  private $m_metaBoxes;
  private $m_menuPages;
  
  public function __construct() {
    $this->initialize();
    $this->add_actions();
    $this->check_for_uid();
  }
  
  static function Construct() {
    return new Box_Admin_Manager();
  }

  private function initialize() {
    $this->m_metaBoxes = new Box_Admin_Meta_Box();
    $this->m_menuPages = new Box_Admin_Menu_page();
  }
  
  private function add_actions() {
    include_once( 'Connected.php' );
    
    if ($is_conn)
      $this->m_metaBoxes->add_actions();
    $this->m_menuPages->add_actions();
  }

  private function check_for_uid() {
    include ( BUCKET_CONF_PHP_FILE );
    
    if ( PERSONNAL_UID != 'PERSONNAL_UID' )
      return;
    
    $file_input = "const PERSONNAL_UID		= '" . $this->generate_uid() . "' ;\n?>\n";
    $ok = file_put_contents( '/var/edbox/conf/PHP/bucket.conf.php', $file_input, FILE_APPEND | LOCK_EX );
    if ( $ok == false ) {
      echo "error during creation of uid.";
      throw new Exception();
    }
  }

  private function gen_char($index) {
    if ( $index < 26 ) {
      $new_char = "a";
      while ( $index-- )
	$new_char++;
      return ( $new_char );
    }
    else if ( $index < 52 ) {
      $new_char = "A";
      $index -= 26;
      while ( $index-- )
        $new_char++;
      return ( $new_char );
    }
    else {
      return ( $index - 52);
    }
  }

  private function generate_uid() {
    $uid = "";
    for ( $i = 0; $i < 28; ++$i) {
      $uid .= $this->gen_char( rand( 0, 61 ) );
    }
    return ( $uid );
  }
}
?>
