<?php
class Box_Admin_Menu_Page {
  public function __contruct() {
    
  }

  public function add_actions() {
    add_action(
      'admin_menu',
      array( $this, 'add_edbox_admin_menus' )
    );
  }

  public function add_edbox_admin_menus() {
    $this->add_edbox_admin_menu();
    $this->add_edbox_wifi_submenu();
    $this->add_edbox_download_submenu();
    $this->add_edbox_my_blogs_submenu();
    //$this->add_edbox_twinning_submenu();
  }

  private function add_edbox_admin_menu() {
    add_menu_page(
      'Gestion de la box',
      'EdBox',
      'edit_posts',
      'edbox.php',
      '',
      plugins_url( 'box_administration/images/icon.jpg' ),
      81
    );
  }

  private function add_edbox_download_submenu() {
    $hook = add_submenu_page(
      'edbox.php',
      esc_html( 'Synchronisation avec les écoles jumelées' ),
      esc_html( 'Articles jumelés' ),
      'edit_posts',
      'edbox_sync.php'
    );
  }

  private function add_edbox_twinning_submenu() {
    add_submenu_page(
      'edbox.php',
      esc_html( 'Interface de jumelage' ),
      esc_html( 'Jumelage' ),
      'edit_posts',
      'edbox_twinning.php'
    );
  }

  private function add_edbox_wifi_submenu() {
    add_submenu_page(
      'edbox.php',
      esc_html( 'Connexion à internet' ),
      esc_html( 'Wifi' ),
      'edit_posts',
      'edbox.php'
    );
  }

  private function add_edbox_my_blogs_submenu() {
    add_submenu_page(
      'edbox.php',
      esc_html( 'Articles publiés' ),
      esc_html( 'Nos articles' ),
      'edit_posts',
      'edbox_my_blogs.php'
    );
  }
}
