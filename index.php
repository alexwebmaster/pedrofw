<?php 

// CONFIG VARIABLES
$app_dir 	= 'app';
$sys_dir 	= 'system';
$pub_dir 	= 'public';
$ses_name 	= 'session';

ini_set('display_errors', 'On');
error_reporting(E_ALL & ~(E_DEPRECATED | E_STRICT));

session_start();
header('Cache-Control: max-age=86400');
header("Content-type: text/html; charset=UTF-8");

define('SYS_ROOT', str_replace('system/config.php', '', str_replace('\\', '/', __FILE__)));

if (!defined('SRV_ROOT')) define('SRV_ROOT', SYS_ROOT);

define('ENV_DEVELOP',		3);
define('ENV_TEST',			2);
define('ENV_PRODUCTION',	1);

define('ENVIRONMENT',		ENV_DEVELOP);

// Diretórios do sistema
define('SYSTEM_DIR',		$sys_dir.'/');
define('CORE_DIR',			SYSTEM_DIR.'core/');
define('LIBRARY_DIR',		SYSTEM_DIR.'library/');

// Diretórios da Aplicação
define('APP_DIR',			$app_dir.'/');
define('MODELS_DIR',		APP_DIR.'models/');
define('CONTROLLERS_DIR',	APP_DIR.'controllers/');
define('VIEWS_DIR',			APP_DIR.'views/');
define('CONFIG_DIR',		APP_DIR.'config/');

// Diretórios do Públicos
define('PUB_DIR',			$pub_dir.'/');
define('IMG_DIR',			PUB_DIR.'img/');
define('CSS_DIR',			PUB_DIR.'css/');
define('JSCRIPTS_DIR',		PUB_DIR.'js/');
define('DOWNLOAD_DIR',		PUB_DIR.'downloads/');
define('UPLOAD_DIR',		PUB_DIR.'uploads/');

require_once( __DIR__.'/vendor/autoload.php');
require_once( __DIR__.'/system/bootstrap.php');