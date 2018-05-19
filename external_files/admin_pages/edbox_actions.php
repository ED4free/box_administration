<?php

header( "Content-Type: text/plain; charset=utf-8" );
//require_once( dirname( __FILE__ ) . '/admin.php' );
require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminActionsManager.php' );

if ( empty( $_POST[ 'actions' ] ) ) {
  echo( 'Aucune action sélectionné.' );
  throw new Exception();
}
if ( Box_Admin_Actions_Manager::do_action( $_POST[ 'actions' ] ) == false ) {
  echo( 'L\'action sélectionné est invalide: ' . $_POST[ 'actions' ] );
  throw new Exception();
}
?>
