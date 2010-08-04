<?php
# Init
$bootstrap = $run = false;
$indexphp = str_replace('public/styles/csscaffold.php','',$_SERVER['SCRIPT_FILENAME']).'index.php';

# Bootstrap
require_once($indexphp);
$Application->bootstrap('balphp');

# CSSScaffold
$config = array();
$config['document_root'] = ROOT_PATH;
$config['system'] = COMMON_PATH.DIRECTORY_SEPARATOR.'csscaffold'.DIRECTORY_SEPARATOR;
$config['urlpath'] = PUBLIC_PATH;
require_once ($config['system'].'index.php');
