<?php
const EDBOX_REPO			= '/var/edbox/';

const CONF_REPO				= EDBOX_REPO . 'conf/';
const SYNC_CONF_REPO			= CONF_REPO . 'sync/';
const PHP_CONF_REPO			= CONF_REPO . 'PHP/';
const BUCKET_CONF_PHP_FILE		= PHP_CONF_REPO . 'bucket.conf.php';
const DEPLOYMENT_CONF_REPO		= CONF_REPO . 'deployment/';

const SCRIPT_REPO			= EDBOX_REPO . 'script/';
const DEPLOYMENT_SCRIPT_REPO		= SCRIPT_REPO . 'deployment/';
const SERVER_CONFIGURATION_SCRIPT_REPO	= SCRIPT_REPO . 'server_configuration/';
const SYNC_SCRIPT_REPO			= SCRIPT_REPO . 'sync/';
const COMPRESS_BLOG_SCRIPT		= SYNC_SCRIPT_REPO . 'compress_blog.sh';
const UNCOMPRESS_BLOG_SCRIPT		= SYNC_SCRIPT_REPO . 'uncompress_blog.sh';
const SYNC_BLOG_SCRIPT			= SYNC_SCRIPT_REPO . 'sync_blog.sh';
const GET_BLOG_SCRIPT			= SYNC_SCRIPT_REPO . 'get_blogs_list.sh';
const REMOVE_BLOG_SCRIPT		= SYNC_SCRIPT_REPO . 'remove_blog.sh';
const WIFI_CONNEXION_SCRIPT_REPO	= SCRIPT_REPO . 'wifi_connexion/';
const CONNECT_SCRIPT			= WIFI_CONNEXION_SCRIPT_REPO . 'connect_to_wifi.sh';
const DISCONNECT_SCRIPT			= WIFI_CONNEXION_SCRIPT_REPO . 'disconnect_to_wifi.sh';
const TEST_WIFI_SCRIPT			= WIFI_CONNEXION_SCRIPT_REPO . 'test_wifi.sh';

const PLUGIN_REPOSITORY			= WP_PLUGIN_DIR . '/box_administration/';
const PLUGIN_INCLUDES_REPOSITORY	= PLUGIN_REPOSITORY . 'includes/';
const PLUGIN_JS_REPOSITORY		= PLUGIN_REPOSITORY . 'js/';
const PLUGIN_JS_BASE_REPOSITORY		= 'box_administration/js/';
const PLUGIN_IMAGES_REPOSITORY		= PLUGIN_REPOSITORY . 'images/';

?>
