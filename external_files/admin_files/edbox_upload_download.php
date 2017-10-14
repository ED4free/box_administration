<?php

require_once( dirname( __FILE__ ) . '/admin.php' );

function print_upload_page() {
?>
<h2><?php echo esc_html( 'Synchronisation'); ?></h2>
<?php
}

function print_download_page() {
?>
<h2><?php echo esc_html( 'Téléchargement'); ?></h2>
<?php
}

function print_remove_page() {
?>
<h2><?php echo esc_html( 'Annulation' ); ?></h2>
<?php
}

/** EXECUTION **/
require_once( 'admin-header.php' );

if ( empty( $_GET[ 'actions' ] ) ) {
  wp_die( 'Aucune action sélectionné.' );
}

?>
<div class='wrap'>
  <?php
  if ( $_GET[ 'actions' ] == 'upload' ) {
    print_upload_page();
  }
  else if ( $_GET[ 'actions' ] == 'download' ) {
    print_download_page();
  }
  else if ( $_GET[ 'actions' ] == 'remove' ) {
    print_remove_page();
  }
  else {
    wp_die( 'L\'action renseigné est invalide' );
  }
  ?>
</div>
<div id='actionReporter'></div>
<?php
include ( ABSPATH . 'wp-admin/admin-footer.php' );
include ( ABSPATH . 'wp-content/plugins/box_administration/includes/FirebaseJsScript.php');
?>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'oXHR.js') ); ?>'>
</script>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptSync.js') ); ?>'>
</script>
<script type='text/javascript'>
 <?php
 if ( $_GET[ 'actions' ] == 'upload' ) {
   echo 'getUploadStatus();';
 }
 else if ( $_GET[ 'actions' ] == 'download' ) {
   echo 'getDownloadStatus();';
 }
 else if ( $_GET[ 'actions' ] == 'remove' ) {
   echo 'getRemoveStatus();';
 }
 ?>
</script>
