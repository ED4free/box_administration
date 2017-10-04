<?php
/**
 * Box Administration.
 *
 * Cette page affiche la connection aux réseaux Wi-Fi et permet d'éteindre
 * ou de redémarrer la box
 */

require_once( dirname(  __FILE__ ) . '/admin.php' );
require_once( 'admin-header.php' );

function print_current_wifi() {
?>
<p>
  <?php
  foreach($_GET[ 'script_output' ] as $output) {
    echo "$output<br/>";
  }
  ?>
</p>
<button onclick="postDisconnect()">
  <?php echo esc_html( "Se déconnecter." ); ?>
</button>
<?php
}

function print_available_wifi() {
?>
<div>
  <select id="selected_essid">
    <?php
    foreach ($_GET[ 'script_output' ] as $wifi_essid) {
      echo "<option>$wifi_essid</option>";
    }
    ?>
  </select>
  <br/>
  <?php echo esc_html( "Mot de passe:" ); ?>
  <input type="text" id="wifi_password"></input>
  <button onclick='postConnect()'><?php echo esc_html("Se connecter."); ?></button>
</div>
<?php
}

function print_internet_connexion() {
?>
<h2>
  <?php echo esc_html( 'Connection à internet' ); ?>
</h2>
<?php
  exec(
    'sudo ' . TEST_WIFI_SCRIPT,
    $_GET[ 'script_output' ],
    $script_return
  );
  if ( $script_return) {
    print_available_wifi();
  }
  else {
    print_current_wifi();
  }
}

function print_poweroff_reboot() {
?>
<h2>
  <?php echo esc_html( 'Eteindre / Redémarrer la box' ); ?>
</h2>
<button onclick='postPoweroff()'><?php echo esc_html( 'Eteindre' ); ?></button>
<button onclick='postReboot()'><?php echo esc_html( 'Redémarrer' ); ?></button>
<?php
}

function print_plugin_upgrading() {
  exec(
    "sudo '" . NEED_UPGRADE_SCRIPT . "' 'plugin path' 'var path'",
    $_GET[ 'script_output' ],
    $script_return
  );

  if ( $script_return == 0 ) {
?>
<div class='card'>
  <h2>
    <?php echo esc_html( 'Mettre a jour le plugin' ); ?>
  </h2>
  <button onclick='postUpgrade()'><?php echo esc_html( 'Mettre a jour' ); ?></button>
</div>
<?php
  }
}

?>
<div class='wrap'>
  <div id='actionReporter'></div>
  <h1 class='wp-heading-inline'>
    <?php echo esc_html( 'Gestion de la box' ); ?>
  </h1>
  <div class='card'>
    <?php
    print_internet_connexion();
    ?>
  </div>
  <div class='card'>
    <?php
    print_poweroff_reboot();
    ?>
  </div>
</div>
<?php
include ( ABSPATH . 'wp-admin/admin-footer.php' );
?>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'oXHR.js') ); ?>'>
</script>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptActions.js') ); ?>'>
</script>
