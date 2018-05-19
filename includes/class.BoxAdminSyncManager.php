<?php
require_once( BUCKET_CONF_PHP_FILE );

class Box_Admin_Sync_Manager {
  /** CONSTATNS **/
  const XML_VERSION		= "1.0";
  const XML_FORMATING		= "utf-8";
  const XML_BLOG_ELEM_NAME	= "blog";
  const XML_MEDIA_ELEM_NAME	= "media";
  const XML_ATTACHED_MEDIA_NAME	= "attached_media";

  /** PRIVATE FUNCTIONS **/
  
  // XML GENERATION
  private function create_xml ( ) {
    return new DomDocument( Box_Admin_Sync_Manager::XML_VERSION, Box_Admin_Sync_Manager::XML_FORMATING );
  }
  
  private function add_blog_to_xml( $xml, $data ) {
    $blog_xml = $xml->createElement( Box_Admin_Sync_Manager::XML_BLOG_ELEM_NAME );
    foreach ( $data as $key => $value ) {
      if ( $key == "post_content" ) {
	      $blog_content = $xml->createElement( "post_content" );
	      $blog_content->appendChild( $xml->createCDATASection( "$value" ) );
	      $blog_xml->appendChild( $blog_content );
      }
      else
	      $blog_xml->appendChild( $xml->createElement( "$key", "$value" ) );
    }
    return ( $blog_xml );
  }
  
  private function add_attached_media_to_xml( &$blog_xml, $data, $xml ) {
    $attached_media = get_attached_media( "", $data->ID );
    $sql_query = "SELECT * FROM wp_posts WHERE ";
    
    foreach( $attached_media as $media ) {
      $sql_query .= "ID=" . $media->ID . " OR ";
      $attached_media_xml = $xml->createElement( Box_Admin_Sync_Manager::XML_ATTACHED_MEDIA_NAME );
      foreach ( get_object_vars( $media ) as $key => $value )
	      $attached_media_xml->appendChild( $xml->createElement( "$key", "$value" ) );
      $blog_xml->appendChild( $attached_media_xml );
    }
    $sql_query .= "1=2;";
    return ( $sql_query );
  }
  
  private function add_media_to_xml( $blog_xml, $sql_query, $xml ) {
    $media_sql_result = mysql_query( $sql_query );
    while ( $media_data = mysql_fetch_assoc( $media_sql_result ) ) {
      $media_xml = $xml->createElement( Box_Admin_Sync_Manager::XML_MEDIA_ELEM_NAME );
      foreach ( $media_data as $key => $value )
	      $media_xml->appendChild( $xml->createElement( "$key", "$value" ) );
      $blog_xml->appendChild( $media_xml );
    }
    return ( $blog_xml );
  }
  
  // UPLOADING
  private function connect_to_database( $blog ) {
    mysql_connect( WORDPRESS_DB_HOST, WORDPRESS_DB_USERNAME, WORDPRESS_DB_PASSWORD );
    mysql_select_db( WORDPRESS_DB_NAME );
    $mysqli = new mysqli(WORDPRESS_DB_HOST, WORDPRESS_DB_USERNAME, WORDPRESS_DB_PASSWORD, WORDPRESS_DB_NAME );
    $mysqli->query("utf8");
    return $mysqli->query('SELECT * FROM wp_posts WHERE ID=' . $blog . ';');

    // catch error then loop $blog
    return mysql_query( 'SELECT * FROM wp_posts WHERE ID=' . $blog . ';');
  }

  private function create_tmp_repository( $data, $xml_result ) {
    mkdir( TMP_LOCAL_PATH . $data->post_name );
    $xml_result->save( TMP_LOCAL_PATH . $data->post_name . "/" . BLOG_XML_FILE_NAME );
    $medias = $xml_result->getElementsByTagName( Box_Admin_Sync_Manager::XML_ATTACHED_MEDIA_NAME );
    $medias_guid = array();
    foreach ( $medias as $media ){
      foreach ( $media->childNodes as $node){
	      if ( $node->tagName == "guid" ) {
	        array_push (
	          $medias_guid,
	          ABSPATH . substr( $node->nodeValue, strlen( get_site_url() ) + 1 )
	        );
	      }
      }
    }
    foreach ($medias_guid as $guid)
      copy( $guid, TMP_LOCAL_PATH . $data->post_name . "/" . basename( $guid ) );
  }
  
  private function compress_tmp_repository( $data ) {
    exec(
      COMPRESS_BLOG_SCRIPT . ' "' . $data->post_name . '"',
      $_GET[ 'scr_output' ],
      $script_return
    );
    if ( $script_return ) {
      echo( "Erreur lors de la compression" );
      throw new Exception();
    }
  }
  
  private function export_blog_into_bucket( $data ) {
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminBucketManager.php' );
    Box_Admin_Bucket_Manager::upload_file( $data->post_name );
  }
  
  // DOWNLOADING
  private function download_archive_from_bucket( $blog ) {
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminBucketManager.php' );
    Box_Admin_Bucket_Manager::download_file( $blog );
  }
  
  private function unarchive_new_blog( $blog ) {
    $blog_archive_name = basename( $blog );
    $blog_unarchive_name = substr(
      $blog_archive_name,
      0,
      strlen( $blog_archive_name ) - strlen( COMPRESS_FILE_EXTENSION )
    );
    exec(
      UNCOMPRESS_BLOG_SCRIPT . ' "' . $blog_archive_name . '" 2>&1',
      $_GET[ "scr_output" ],
      $script_return
    );
    if ( $script_return ) {
      echo 'Erreur lors de la décompression du fichier';
      throw new Exception();
    }
    return ( $blog_unarchive_name );
  }
  
  private function create_twinning_user( ) {
    if ( ( $twinning_user_id = username_exists( TWINNING_USERNAME ) ) == false )
      $twinning_user_id = wp_create_user( TWINNING_USERNAME, TWINNING_PASSWORD );
    return ( $twinning_user_id );
  }
  
  private function load_blog_xml( $blog_unarchive_name ) {
    $blog_xml = new DOMDocument();
    $blog_xml->load( TMP_LOCAL_PATH . $blog_unarchive_name . '/' . BLOG_XML_FILE_NAME );
    echo var_dump(TMP_LOCAL_PATH . $blog_unarchive_name . '/' . BLOG_XML_FILE_NAME);
    return ( $blog_xml );
  }
  
  private function create_post_array( $blog_xml, $twinning_user_id ) {
    $node_blog_xml = $blog_xml->getElementsByTagName( Box_Admin_Sync_Manager::XML_BLOG_ELEM_NAME );
    $new_post = array();
    foreach ( $node_blog_xml[0]->childNodes as $key => $value ) {
      if ( $value->tagName == "ID" ||
	   $value->tagName == "post_author" ||
	   $value->tagName == "post_parent" ||
	   $value->tagName == "guid" ||
	   $value->tagName == "attached_media" ||
	   $value->tagName == Box_Admin_Sync_Manager::XML_MEDIA_ELEM_NAME )
      continue;
      if ( $value->tagName == "post_title" )
	$new_post[ $value->tagName ] = "jumelage - " . $value->textContent;
      else
	$new_post[ $value->tagName ] = $value->textContent;
    }
    $new_post[ "post_author" ] = $twinning_user_id;
    return ( $new_post );
  }
  
  private function upload_attached_medias_to_wordpress( $blog_xml, $blog_unarchive_name, $new_post_id, $twinning_user_id ) {
    $node_media_xml = $blog_xml->getElementsByTagName( Box_Admin_Sync_Manager::XML_ATTACHED_MEDIA_NAME );
    foreach ( $node_media_xml as $media ) {
      $exploded_guid = explode( "/", $media->getElementsByTagName("guid")[0]->nodeValue );
      $name = end( $exploded_guid );
      $media_tmp_path = TMP_LOCAL_PATH . $blog_unarchive_name . "/" . $name;
      $upload_file = wp_upload_bits( $name, null, file_get_contents( $media_tmp_path ), "$exploded_guid[5]/$exploded_guid[6]" );
      if ( !$upload_file[ 'error' ] ) {
	$wp_filetype = wp_check_filetype( $name, null );
	$attachement = array (
	  'post_mime_type' => $wp_filetype[ 'type' ],
	  'post_parent' => $new_post_id,
	  'post_title' => preg_replace('/\.[^.]+$/', '', $name),
	  'post_content' => '',
	  'post_status' => 'inherit',
	  'post_author' => $twinning_user_id
	);
	
  $attachment_id = wp_insert_attachment( $attachement, $upload_file[ 'file' ], $new_post_id );
	if ( ! is_wp_error( $attachment_id ) ) {
	  require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
	  $attachment_data = wp_generate_attachment_metadata(
	    $attachment_id,
	    $upload_file[ 'file' ]
	  );
	  wp_update_attachment_metadata( $attachment_id,  $attachment_data );
	}
      }
      else {
        echo 'Une erreur est survenue lors de la creation des medias';
        throw new Exception();
      }
    }
  }
  
  /** PUBLIC FUNCTIONS**/
  public function generate_xml( $data ) {
    $xml = $this->create_xml();
    $blog_xml = $this->add_blog_to_xml( $xml, $data );
    $sql_query = $this->add_attached_media_to_xml( $blog_xml, $data, $xml );
    $blog_xml = $this->add_media_to_xml( $blog_xml, $sql_query, $xml );
    
    $xml->appendChild( $blog_xml );
    return ( $xml );
  }

  public function fileSizeConvert( $bytes )
  {
    $bytes = floatval($bytes);
    $arBytes = array(
      0 => array(
        "UNIT" => "To",
        "VALUE" => pow(1024, 4)
      ),
      1 => array(
        "UNIT" => "Go",
        "VALUE" => pow(1024, 3)
      ),
      2 => array(
        "UNIT" => "Mo",
        "VALUE" => pow(1024, 2)
      ),
      3 => array(
        "UNIT" => "Ko",
        "VALUE" => 1024
      ),
      4 => array(
        "UNIT" => "o",
        "VALUE" => 1
      ),
    );
    
    foreach($arBytes as $arItem)
    {
      if($bytes >= $arItem["VALUE"])
      {
        $result = $bytes / $arItem["VALUE"];
        $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
        break;
      }
    }
    return $result;
  }
  
  public function upload( $blog ) {
    if ( empty( $blog ) ) {
      echo 'Aucun blog sélectionné.';
      throw new Exception();
    }
    
    $blogs = explode(',', $blog);
    foreach ( $blogs as $blogPath) {
      if ( empty( $blogPath ) )
        continue;      
      $data = get_post($blogPath);
      $xml_result = $this->generate_xml( $data );
      $this->create_tmp_repository( $data, $xml_result );
      $this->compress_tmp_repository( $data );
      $this->export_blog_into_bucket( $data );
      echo "{\n";
      echo '"' . PERSONNAL_UID . "\": {\n";
      echo "\"$data->post_name\": {\n";
      echo '"size": "' . $this->fileSizeConvert( filesize( TMP_LOCAL_PATH . $data->post_name . COMPRESS_FILE_EXTENSION ) ) . "\",\n";
      echo '"date": "' . "$data->post_modified\"\n";
      echo "}\n";
      echo "}\n";
      echo '}';
      unlink( TMP_LOCAL_PATH . $data->post_name . COMPRESS_FILE_EXTENSION );
    }
  }
  
  public function download( $blog ) {
    if ( empty( $blog ) ) {
      echo( 'Aucun blog sélectionné.' );
      throw new Exception();      
    }
    $blogs = explode(',', $blog);
    foreach ( $blogs as $blogPath) {
      if ( empty( $blogPath ) )
        continue;
      $this->download_archive_from_bucket( $blogPath );
      $blog_unarchive_name = $this->unarchive_new_blog( $blogPath );
      $twinning_user_id = $this->create_twinning_user();
      $blog_xml = $this->load_blog_xml( $blog_unarchive_name );
      $new_post = $this->create_post_array( $blog_xml, $twinning_user_id );
      $new_post_id = wp_insert_post( $new_post );
      $this->upload_attached_medias_to_wordpress( $blog_xml, $blog_unarchive_name, $new_post_id, $twinning_user_id );
    }
  }
  
  public function remove( $blog ) {
    if ( empty( $blog ) ) {
      echo( 'Aucun blog sélectionné.' );
      throw new Exception();
    }
    require_once ( PLUGIN_INCLUDES_REPOSITORY . 'class.BoxAdminBucketManager.php' );
    $blogs = explode(',', $blog);
    foreach ( $blogs as $blogPath) {
      if ( empty( $blogPath ) )
        continue;
      Box_Admin_Bucket_Manager::remove_file( $blogPath );
      echo "$blogPath,";
    }
  }
  
  public function get_blogs_list() {
    
  }
}
?>
