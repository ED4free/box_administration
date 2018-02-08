<?php
/**
 * Box Administration.
 *
 * Cette page permet d'activer ou de desactiver les services de la box.
 */

require_once( dirname( __FILE__ ) . '/admin.php' );
require_once( 'admin-header.php' );

function service_exist( $serviceName ) {
  exec(
    'sudo service "' . $serviceName . '" status | grep -Fq "Loaded: not-found"',
    $_GET[ 'service_return' ],
    $shell_return
  );
  return ( $shell_return == 1 );
}

function service_is_running( $serviceName ) {
  exec(
    'sudo service "' . $serviceName . '" status | grep -Fq "Active: active"',
    $_GET[ 'service_return' ],
    $shell_return
  );
  return ( $shell_return == 0 );
}

function print_line( $serviceName ) {
  echo "$serviceName  ";
  if ( service_is_running( $serviceName ) ) {
    echo "<button onCLick='postServices(\"$serviceName\", \"stop\")'>";
    echo esc_html( "Arréter" );
    echo "</button>";
  }
  else {
    echo "<button onCLick='postServices(\"$serviceName\", \"start\")'>";
    echo esc_html( "Démarrer" );
    echo "</button>";
  }
  echo "<br/>";
}

?>

<div class='wrap'>
  <div id='actionReporter'></div>
  <h1 class='wp-heading-inline'>
    <?php echo esc_html( 'Gestion des services' ); ?>
  </h1>
  <div class='card'>
    <h2><?php echo esc_html( 'Services' ); ?></h2>
    <p>
      <?php
      if ( service_exist( 'ka-lite' ) ) {
	print_line( 'ka-lite' );
      }
      if ( service_exist( 'aflatounkalite' ) ) {
	print_line( 'aflatounkalite' );
      }
      if ( service_exist( 'supervisor' ) ) {
	print_line( 'supervisor' );
      }
      ?>
    </p>
  </div>
</div>
<?php
include ( ABSPATH . 'wp-admin/admin-footer.php' );
?>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'oXHR.js') ); ?>'>
</script>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptActions.js') ); ?>'>
</script>
