<?php

require_once( 'class.BoxAdminMetaBox.php' );
require_once( 'class.BoxAdminMenuPage.php' );

class Box_Admin_Manager {
  private $m_metaBoxes;
  private $m_menuPages;
  
  public function __construct() {
    $this->initialize();
    $this->add_actions();
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
}
?>
