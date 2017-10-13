<?php
/**
 * Box Administration.
 *
 */

/** Initialization **/
require_once( dirname(  __FILE__ ) . '/admin.php' );
require_once( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminTwinningListTable.php' );
require_once( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminBucketManager.php' );

if ( ! empty( $_POST[ "blog" ] ) && (! empty( $_POST[ "action" ] )  || ! empty( $_POST[ "action2" ] ) ) ) {
  $newHeader = "Location: edbox_upload_download.php?";
  if ( $_POST[ "action" ] == "-1")
    $newHeader .= "actions=" . $_POST[ "action2" ];
  else
    $newHeader .= "actions=" . $_POST[ "action" ];
  $newHeader .= "&blog=";
  foreach ( $_POST[ "blog" ] as $blog )
    $newHeader .= $blog . ",";
  header( $newHeader );
}  

class Box_Admin_Personnal_Blog_List extends Box_Admin_Twinning_List_Table {
  public function init_values() {
    $this->init_data();
    $this->init_columns();
  }

  private function init_data() {
    $blogNames = Box_Admin_Bucket_Manager::list_files( PERSONNAL_UID );
    $data = array();
    $blogs_path = array();
    $i = 0;

    foreach ( $blogNames as $name ) {
      if ( empty( $name) )
	continue;
      $blogInfos = explode( ' ', $name, 3 );
      array_push( $data, array(
	'ID'			=> $i,
	'size'			=> $this->convert_size( $blogInfos[0] ),
	'uploadDate'		=> $this->convert_date( $blogInfos[1] ),
	'blogTitle'		=> $this->convert_title( $blogInfos[2] ),
	'path'			=> $blogInfos[2]
      ) );
      $i++;
    }
    $this->set_data( $data );
  }

  private function init_columns() {
    $this->set_columns( array(
      'cb'			=> '<input type="checkbox" />',
      'blogTitle'		=> 'Titre',
      'uploadDate'		=> 'Mise ne ligne',
      'size'			=> 'Taille'
    ) );
    $this->set_sortable( array(
      'blogTitle'		=> array( 'blogTitle', true),
      'uploadDate'		=> array( 'uploadDate', true),
      'size'			=> array( 'size', true)
      
    ) );
    $this->set_bulk_actions( array(
      //'upload'			=> 'Mettre à jour',
      'remove'			=> 'Retirer du partage'
    ) );
  }

  private function convert_size( $size ) {
    $return_value;
    $int_size = intval( $size );

    if ( $int_size <= 1024 )
      return ( $size . " o");
    else if ( ( $int_size /= 1024 ) <= 1024)
    return ( round( $int_size, 1) . " Ko" );
    else if ( ( $int_size /= 1024 ) <= 1024)
    return ( round( $int_size , 1) . " Mo" );
    $int_size /= 1024;
    return ( round( $int_size , 1) . " Go" );
  }

  private function convert_date( $date ) {
    return ( date ( "d-m-Y : G:i e", strtotime( $date ) ) );
  }

  private function convert_title( $title ) {
    $title = basename( $title );
    
    return ( substr( $title, 0,	strlen( $title ) - strlen( COMPRESS_FILE_EXTENSION ) ) );
  }

/** OVERLOADED FUNCTIONS **/
  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="blog[]" value="%s" />', $item[ 'path' ]        
    );
  }
  
function column_blogTitle( $item ) {
  $actions = array(
    /*'upload'      => sprintf(
      '<a href="%s?actions=%s&blog=%s">Mettre à jour</a>',
      'edbox_upload_download.php',
      'upload',
      $item[ 'path' ]
    ),*/
    'trash'      => sprintf(
      '<a href="%s?actions=%s&blog=%s">Retirer du partage</a>',
      'edbox_upload_download.php',
      'remove',
      $item[ 'path' ]
    )
  );
  
    return sprintf( '%1$s %2$s', $item[ 'blogTitle' ], $this->row_actions( $actions ) );
  }
}

$myListTable = new Box_Admin_Personnal_Blog_List();
$myListTable->init_values();
$myListTable->set_per_page( 10 );
$myListTable->prepare_items();

/** Execution **/
require_once( 'admin-header.php' );

?>
<div class="wrap">
  <h2><?php echo esc_html( 'Mes blogs mis en ligne' ); ?></h2>
  <form method="post">
    <?php
    $myListTable->search_box( 'Rechercher', 'search_id' );
    $myListTable->display();
    ?>
  </form>
</div>
<?php
include ( ABSPATH . 'wp-admin/admin-footer.php' );
include ( ABSPATH . 'wp-content/plugins/box_administration/includes/FirebaseJsScript.php' );
?>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptListTable.js') ); ?>'></script>
<script type='text/javascript'>
 var ref = db.ref( 'schools/<?php echo ( PERSONNAL_UID ) ?>' );

 ref.once('value').then(function(snapshot) {
   console.log(snapshot.val());
 })
</script>
