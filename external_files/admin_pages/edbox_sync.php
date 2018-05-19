<?php
/**
 * Box Administration.
 *
 */
/** Initialization **/
//require_once( dirname(  __FILE__ ) . '/admin.php' );
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

if ( ! file_exists( '/tmp/school_names.twinning.php' ) )
  Box_Admin_Bucket_Manager::download_file( "school_names.twinning.php" );

class Box_Admin_Twinning_Blog_List extends Box_Admin_Twinning_List_Table {
  public function has_items() {
    return (true);
  }

  public function init_values() {
    $this->init_columns();
  }
  private function init_columns() {
    $this->set_columns( array(
      'cb'			=> '<input type="checkbox" />',
      'blogTitle'		=> 'Titre',
      'uploadDate'		=> 'Mise ne ligne',
      'schoolName'		=> 'Provenance',
      'size'			=> 'Taille'
    ) );
    $this->set_sortable( array(
      'blogTitle'		=> array( 'blogTitle', true),
      'uploadDate'		=> array( 'uploadDate', true),
      'schoolName'		=> array( 'schoolName', true),
      'size'			=> array( 'size', true)
      
    ) );
    $this->set_bulk_actions( array(
      'download'		=> 'Télécharger'
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

  private function convert_school_name( $path ) {
    $uid = explode( '/', $path )[0];
    include( '/tmp/school_names.twinning.php' );    
    
    if ( array_key_exists($uid, $schoolNames ) )
      return ( $schoolNames[ "$uid" ] );
    return ($uid);
  }

/** OVERLOADED FUNCTIONS **/
  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="blog[]" value="%s" />', $item[ 'path' ]        
    );
  }
  
  function column_blogTitle( $item ) {
    $actions = array(
      'download'      => sprintf(
	'<a href="%s?actions=%s&blog=%s">Télécharger</a>',
	'edbox_upload_download.php',
	'download',
	$item[ 'path' ]
      )
    );
    
    return sprintf( '%1$s %2$s', $item[ 'blogTitle' ], $this->row_actions( $actions ) );
  }
}

$myListTable = new Box_Admin_Twinning_Blog_List();
$myListTable->init_values();
//$myListTable->set_per_page( 10 );
$myListTable->prepare_items();

/** Execution **/
require_once( 'admin-header.php' );

?>
<div class="wrap">
  <h2><?php echo esc_html( 'Récupération des blogs en ligne' ); ?></h2>
  <div class="tablenav-pages">
    <span class="displaying-num" id="nb-elem">
      0 éléments
    </span>
    <span class="pagination-links">
      <button onClick="firstPage()">
        «
      </button>
      <button onClick="prevPage()">
        ‹
      </button>
      <span class="paging-input">
        <label for="current-page-selector" class="screen-reader-text">
          Page actuelle
        </label>
        <input class="current-page" id="current-page-selector" type="number" name="paged" value="1" max="1" min="1" size="2" aria-describedby="table-paging" onChange="curPageChange()">
        <span class="tablenav-paging-text">
          sur 
          <span class="total-pages" id="total-pages">
            1
          </span>
        </span>
      </span>
      <button onClick="nextPage()">
        »
      </button>
      <button onClick="lastPage()">
        ›
      </button>
    </span>
    <br/>
    éléments par page
    <input type="number" value="10" id="per-page-selector" onChange="perPageChange()" />
  </div>
  <form method="post">
    <?php
    $myListTable->search_box( 'Rechercher', 'search_id' );
    $myListTable->display();
    ?>
  </form>
</div>
<?php
include ( ABSPATH . 'wp-admin/admin-footer.php' );
include ( ABSPATH . 'wp-content/plugins/box_administration/includes/FirebaseJsScript.php');
?>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptListTable.js') ); ?>'></script>
<script type='text/javascript'>
  var blogs = [];
  var per_page = document.getElementById("per-page-selector");
  var cur_page = document.getElementById("current-page-selector");
  var nb_elem = document.getElementById("nb-elem");
  var total_pages = document.getElementById("total-pages");

  function refreshTable() {
    clearTable();
    for (var i = 0; (cur_page.value - 1) * per_page.value + i < blogs.length && i < per_page.value; ++i){
      curBlog = blogs[(cur_page.value - 1) * per_page.value + i];
      addTwinningsRow(
        curBlog["twinUid"],
        curBlog["blogName"],
        curBlog["date"],
        curBlog["schoolName"],
        curBlog["size"]
      );
    }
  }

  function actualizeNbElem() {
    nb_elem.innerHTML = blogs.length + " element";
    if (!blogs.empty)
      nb_elem.innerHTML += "s";
    total_pages.innerHTML = Math.trunc(blogs.length / per_page.value) + 1;
    cur_page.max = Math.trunc(blogs.length / per_page.value) + 1;
  }

  function curPageChange() {
    if (cur_page.value > Number(total_pages.innerHTML)) {
      cur_page.value = total_pages.innerHTML;
    }
    if (cur_page.value < 1) {
      cur_page.value = 1;
    }
    refreshTable();
  }

  function perPageChange() {
    actualizeNbElem();
    curPageChange()
  }

  function firstPage() {
    cur_page.value = 1;
    refreshTable();
  }

  function lastPage() {
    cur_page.value = total_pages.innerHTML;
    refreshTable();
  }

  function prevPage() {
    if (cur_page.value > 1) {
      cur_page.value--;
    }
    refreshTable();
  }

  function nextPage() {
    if (cur_page.value < Number(total_pages.innerHTML)) {
      cur_page.value++;
    }
    refreshTable();
  }

 db.ref('schoolNames').once('value').then(function(schoolNamesSnapshot) {
   var schoolNames = schoolNamesSnapshot.val();
   
   db.ref('twinnings/<?php echo ( PERSONNAL_UID ) ?>').once('value').then(function(twinningSnapshot) {
     var twinningsUid = twinningSnapshot.val();
     twinningsUid = twinningsUid.split(',');

     for (uid in twinningsUid) {
       db.ref('schools/' + twinningsUid[uid]).once('value').then(function(snapshot) {
	 var val = snapshot.val();
   var twinUid = snapshot.key;
   for (blogName in val) {
	   /*addTwinningsRow(
	     twinUid,
	     blogName,
	     val[blogName].date,
	     schoolNames[twinUid],
	     val[blogName].size
     );*/
     blogs.push({
       "twinUid": twinUid,
       "blogName": blogName,
       "date": val[blogName].date,
	     "schoolName": schoolNames[twinUid],
	     "size": val[blogName].size
     });
   }
   refreshTable();
   actualizeNbElem();
       })
     }
   });
 });
</script>
