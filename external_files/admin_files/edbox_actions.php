<?php

header( "Content-Type: text/plain; charset=utf-8" );
require_once( dirname( __FILE__ ) . '/admin.php' );
require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminActionsManager.php' );

if ( empty( $_POST[ 'actions' ] ) )
  wp_die( 'Aucune action sélectionné.' );
if ( Box_Admin_Actions_Manager::do_action( $_POST[ 'actions' ] ) == false )
  wp_die( 'L\'action sélectionné est invalide: ' . $_POST[ 'actions' ] );
echo $_POST[ 'actions' ] ;
?>
