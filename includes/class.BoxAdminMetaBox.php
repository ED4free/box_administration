<?php
class Box_Admin_Meta_Box {
  public function __contruct() {
    
  }

  public function add_actions() {
    add_action(
      'add_meta_boxes',
      array( $this, 'add_upload_meta_box' )
    );
  }

  public function add_upload_meta_box() {
    add_meta_box(
      'id_edbox_upload_meta_box',
      'Jumelage',
      array( $this, 'print_upload_meta_box' ),
      'post',
      'side',
      'high'
    );
  }
  
  public function print_upload_meta_box() {
    //Si le blog est deja en ligne, afficher un champ pour le supprimer
    echo "<a href='edbox_upload_download.php?actions=upload&blog=" . $_GET[ 'post' ] . "'>";
    echo esc_html( 'Partager avec les écoles jumelées' );
    echo "</a>";
  }
}
