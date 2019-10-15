<?php 
require_once(CONFIG_DIR.'config.php');
$routes = require_once(CONFIG_DIR.'routes.php');
define('BASE_URL', $config['base_url']);
define('DEFAULT_CONTROLLER', $routes['default_controller']);

require_once('core/Controller.php');

$controller = new Controller();
$controller->parse_request();