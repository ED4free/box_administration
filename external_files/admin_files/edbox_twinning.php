<?php
/**
 * Box Administration.
 *
 */

require_once( dirname(  __FILE__ ) . '/admin.php' );
require_once( 'admin-header.php' );
require_once( WP_PLUGIN_DIR . '/box_administration/includes/class.BoxAdminTwinningListTable.php' );

$myListTable = new Box_Admin_Twinning_List_Table();
echo '<div class="wrap"><h2>' . esc_html( 'Interface de jumelage' ) . '</h2>';
$myListTable->prepare_items();
$myListTable->display();
echo '</div>';
include ( ABSPATH . 'wp-admin/admin-footer.php' );
// Ici afficher la liste des écoles disponnibles pour le jumelage
?>
