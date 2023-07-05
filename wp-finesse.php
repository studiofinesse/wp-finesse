<?php

/**
* @wordpress-plugin
* Plugin Name:       Studio Finesse
* Plugin URI:        https://studiofinesse.co.uk
* Description:       Just the core functionality that we like to use with most WordPress installs
* Version:           0.0.1
* Author:            Studio Finesse
* Plugin URI:        https://studiofinesse.co.uk
 */

// Define constants
define( 'FIN_DIR', dirname( __FILE__ ) ); // e.g /var/www/...
define( 'FIN_URL', plugins_url( '', __FILE__ ) ); // e.g http://...

include FIN_DIR . '/acf/acf-config.php';

include FIN_DIR . '/lib/helpers.php';

include FIN_DIR . '/lib/admin.php';
include FIN_DIR . '/lib/login.php';
include FIN_DIR . '/lib/functions.php';
include FIN_DIR . '/lib/shortcodes.php';