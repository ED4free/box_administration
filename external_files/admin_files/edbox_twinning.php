<?php
/**
 * Box Administration.
 *
 */

require_once( dirname(  __FILE__ ) . '/admin.php' );
require_once( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminTwinningListTable.php' );
require_once( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminBucketManager.php' );

class Box_Admin_School_Name_List extends Box_Admin_Twinning_List_Table {
  public function init_values() {
    $this->init_columns();
  }

  private function init_columns() {
    $this->set_columns( array(
      'cb'		=> '<input type="checkbox" />',
      'schoolName'	=> 'Nom'
    ) );
    $this->set_sortable( array(
      'schoolName'	=> array( 'schoolName', true)
    ) );
  }
}

require_once( 'admin-header.php' );
?>

<div class='wrap'>
  <h2><?php echo esc_html( 'Mes blogs mis en ligne' ); ?></h2>
</div>
<div id='actionReporter'></div>
<div class='card'>
  <p>
    mon Identifiant: <?php echo ( PERSONNAL_UID ); ?><br/>
    Mon nom: <input type='text' id='school-name' />
  </p>
</div>
<button onClick='UploadData()'><?php echo esc_html ( 'Mettre à jour' ); ?></button>

<?php
include ( ABSPATH . 'wp-admin/admin-footer.php' );
include ( ABSPATH . 'wp-content/plugins/box_administration/includes/FirebaseJsScript.php' );
?>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptListTable.js') ); ?>'></script>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'oXHR.js') ); ?>'>
</script>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptSync.js') ); ?>'>
</script>
<script type="text/javascript">
 var nameInput = document.getElementById('school-name');
 var nameRef = db.ref('schoolNames/<?php echo ( PERSONNAL_UID ) ?>');

 nameRef.once('value').then(function(snapshot) {
   var val = snapshot.val();
   if (val == null) {
     return;
   }
   nameInput.value = val;
 });
</script>
<script type="text/javascript">
 function ManageTwinning(twinUid) {
   console.log(twinUid);
 }
 
 function UploadData() {
   var name = nameInput.value;

   printReporter("Mise à jour en cours...");
   nameRef.set(name).then(function() {
     printReporter("Mise à jour réussie");
   }).catch(function(error) {
     printReporter(error, "notice-failure");
   });
 }
</script>
