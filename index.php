<?php

# Prepare
error_reporting(E_ALL | E_STRICT);
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
	
# Prepare
if ( !empty($_SERVER['REDIRECT_URL']) ) {
	$_SERVER['REQUEST_URI'] = $_SERVER['REDIRECT_URL'];
}

# Prepare
define('APPLICATION_ROOT_PATH', 			realpath(dirname(__FILE__)));
if ( !isset($_SERVER) ) {
	$_SERVER = array();
}
if ( empty($_SERVER['DOCUMENT_ROOT']) ) {
	$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__).'/../../');
}
if ( empty($_SERVER['SCRIPT_FILENAME']) ) {
	$_SERVER['SCRIPT_FILENAME'] = realpath(__FILE__);
} else {
	$_SERVER['SCRIPT_FILENAME'] = realpath($_SERVER['SCRIPT_FILENAME']);
}

# Debug Secret
define('DEBUG_SECRET',						md5(APPLICATION_ROOT_PATH));

# Include paths
if ( in_array($_SERVER['DOCUMENT_ROOT'], array('/Users/balupton/Server/htdocs','/usr/local/zend/apache2/htdocs')) ) {
	# Development Environment
	define('APPLICATION_ENV', 				'development');
	if ( substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['DOCUMENT_ROOT'])) === $_SERVER['DOCUMENT_ROOT'] )
		define('ROOT_PATH', 					realpath($_SERVER['DOCUMENT_ROOT']));
	else # we are located in a apache alias'd directory
		define('ROOT_PATH', 					substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/htdocs')).'/htdocs');
	define('APPLICATION_PATH', 				realpath(APPLICATION_ROOT_PATH . '/application'));
	define('CONFIG_PATH', 					realpath(APPLICATION_PATH.'/config'));
	
	define('COMMON_PATH', 					realpath(ROOT_PATH.'/common'));
	define('DOCTRINE_PATH', 				realpath(COMMON_PATH.'/doctrine-1.2.1-lib'));
	define('DOCTRINE_EXTENSIONS_PATH', 		realpath(COMMON_PATH.'/doctrine-extensions'));
	define('ZEND_PATH', 					realpath(COMMON_PATH.'/zend-1.10.2-lib'));
	define('BALPHP_PATH', 					realpath(COMMON_PATH.'/balphp-lib'));
	
	define('CONFIG_APP_PATH', 				realpath(CONFIG_PATH.'/balcms.ini'));
	define('ROOT_URL',						'http://localhost');
	define('BASE_URL', 						'/~balupton/projects/balcms');
}
elseif ( strpos($_SERVER['HTTP_HOST'], 'balcms.com.au') !== false ) {
	# Production Server
	define('APPLICATION_ENV', 				!empty($_COOKIE['debug']) && $_COOKIE['debug']===DEBUG_SECRET ? 'staging' : 'production');
	define('ROOT_PATH', 					realpath($_SERVER['DOCUMENT_ROOT']));
	define('APPLICATION_PATH', 				realpath(APPLICATION_ROOT_PATH . '/application'));
	define('CONFIG_PATH', 					realpath(APPLICATION_PATH.'/config'));
	
	define('COMMON_PATH', 					realpath(ROOT_PATH.'/common'));
	define('DOCTRINE_PATH', 				realpath(COMMON_PATH.'/doctrine-1.2.1-lib'));
	define('DOCTRINE_EXTENSIONS_PATH', 		realpath(COMMON_PATH.'/doctrine-extensions'));
	define('ZEND_PATH', 					realpath(COMMON_PATH.'/zend-1.10.2-lib'));
	define('BALPHP_PATH', 					realpath(COMMON_PATH.'/balphp-lib'));
	
	define('CONFIG_APP_PATH', 				realpath(CONFIG_PATH.'/balcms.ini'));
	define('ROOT_URL',						'http://www.balcms.com.au');
}
else {
	throw new Exception('Unkown Project Location');
}


# --------------------------

# Defines
if ( !defined('APPLICATION_ENV') ) {
	define('APPLICATION_ENV', 				(getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
}
if ( !defined('APPLICATION_ROOT_PATH') ) {
	define('APPLICATION_ROOT_PATH', 		realpath(APPLICATION_PATH.'/..'));
}
if ( !defined('CONFIG_PATH') ) {
	define('CONFIG_PATH', 					realpath(APPLICATION_PATH.'/config'));
}
if ( !defined('MODELS_PATH') ) {
	define('MODELS_PATH', 					realpath(APPLICATION_PATH.'/models'));
}
if ( !defined('CONFIG_APP_PATH') ) {
	define('CONFIG_APP_PATH', 				realpath(CONFIG_PATH.'/application.ini'));
}
if ( !defined('LIBRARY_PATH') ) {
	define('LIBRARY_PATH', 					realpath(APPLICATION_ROOT_PATH.'/library'));
}
if ( !defined('IL8N_PATH') ) {
	define('IL8N_PATH', 					realpath(APPLICATION_ROOT_PATH.'/il8n'));
}
if ( !defined('MODULES_PATH') ) {
	define('MODULES_PATH', 					realpath(APPLICATION_PATH.'/modules'));
}
if ( !defined('DEBUG_MODE') ) {
	define('DEBUG_MODE',					(
			'development' === APPLICATION_ENV || 'testing' === APPLICATION_ENV ||
			(!empty($_COOKIE['debug']) && $_COOKIE['debug'] === DEBUG_SECRET)
		)	? 1
			: 0
	);
}

# --------------------------
		
if ( !defined('BASE_URL') ) {
	define('BASE_URL', 						'');
}
	
if ( !defined('PUBLIC_PATH') ) {
	define('PUBLIC_PATH', 					realpath(APPLICATION_ROOT_PATH.'/public'));
}
if ( !defined('PUBLIC_URL') ) {
	define('PUBLIC_URL', 					BASE_URL.'/public');
}

# --------------------------

if ( !defined('HTMLPURIFIER_PATH') ) {
	define('HTMLPURIFIER_PATH', 			realpath(COMMON_PATH . '/htmlpurifier-4.0.0-lib'));
}

# --------------------------

if ( !defined('MEDIA_URL') ) {
	define('MEDIA_URL', 					PUBLIC_URL . '/media');
}
if ( !defined('MEDIA_PATH') ) {
	define('MEDIA_PATH', 					realpath(PUBLIC_PATH . '/media'));
}

if ( !defined('DELETED_URL') ) {
	define('DELETED_URL', 					MEDIA_URL . '/deleted');
}
if ( !defined('DELETED_PATH') ) {
	define('DELETED_PATH', 					realpath(MEDIA_PATH . '/deleted'));
}

if ( !defined('IMAGES_URL') ) {
	define('IMAGES_URL', 					MEDIA_URL . '/images');
}
if ( !defined('IMAGES_PATH') ) {
	define('IMAGES_PATH', 					realpath(MEDIA_PATH . '/images'));
}

if ( !defined('INVOICES_URL') ) {
	define('INVOICES_URL', 					MEDIA_URL . '/invoices');
}
if ( !defined('INVOICES_PATH') ) {
	define('INVOICES_PATH', 				realpath(MEDIA_PATH . '/invoices'));
}

if ( !defined('TEMPLATES_URL') ) {
	define('TEMPLATES_URL', 				MEDIA_URL . '/templates');
}
if ( !defined('TEMPLATES_PATH') ) {
	define('TEMPLATES_PATH', 				realpath(MEDIA_PATH . '/templates'));
}

if ( !defined('UPLOADS_URL') ) {
	define('UPLOADS_URL', 					MEDIA_URL . '/uploads');
}
if ( !defined('UPLOADS_PATH') ) {
	define('UPLOADS_PATH', 					realpath(MEDIA_PATH . '/uploads'));
}

if ( !defined('THEMES_URL') ) {
	define('THEMES_URL', 					PUBLIC_URL . '/themes');
}
if ( !defined('THEMES_PATH') ) {
	define('THEMES_PATH', 					realpath(PUBLIC_PATH . '/themes'));
}

# --------------------------

# Ensure library/ is on include_path
$include_paths = $include_paths_original = array();
if ( defined('ZEND_PATH') )
	$include_paths[] = ZEND_PATH;
//if ( defined('DOCTRINE_PATH') )
//	$include_paths[] = DOCTRINE_PATH;
$include_paths[] = LIBRARY_PATH;
$include_paths[] = BALPHP_PATH;
$include_paths[] = MODELS_PATH;
$include_paths_original = str_replace('.:/usr/local/zend/share/ZendFramework/library:', '', get_include_path());
$include_paths_original = array_diff(explode(':',$include_paths_original),$include_paths);
$include_paths = array_merge($include_paths, $include_paths_original);
$include_paths = implode(PATH_SEPARATOR, $include_paths);
set_include_path($include_paths);
unset($include_paths, $include_paths_original);

# Load
require_once implode(DIRECTORY_SEPARATOR, array(ZEND_PATH,'Zend','Application.php'));
require_once implode(DIRECTORY_SEPARATOR, array(BALPHP_PATH,'Bal','Application.php'));
//require_once implode(DIRECTORY_SEPARATOR, array(LIBRARY_PATH,'Bal','Controller','Plugin','App.php'));

# --------------------------

# Fix magic quotes
if ( !isset($fix_magic_quotes) || $fix_magic_quotes ) {
	require_once BALPHP_PATH.'/core/functions/_params.funcs.php';
	fix_magic_quotes();
}

# Create
$Application = new Bal_Application(
    APPLICATION_ENV,
    CONFIG_APP_PATH
);

# Bootstrap
if ( !isset($bootstrap) || $bootstrap )
$Application->bootstrap();

# Run
if ( !isset($run) || $run )
$Application->run();
