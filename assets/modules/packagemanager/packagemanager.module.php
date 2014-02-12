<?php
/*
 * Package Manager
 *
 * @package packagemanager
 * @subpackage module
 *
 * @version 1.0.RC2
 * @author Thomas Jakobi <thomas.jakobi@partout.info>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

if (IN_MANAGER_MODE != 'true') {
	die('<h1>ERROR:</h1><p>Please use the MODx Content Manager instead of accessing this file directly.</p>');
}

define('INSTM_PATH', str_replace(MODX_BASE_PATH, '', str_replace('\\', '/', realpath(dirname(__FILE__)))) . '/');
define('INSTM_BASE_PATH', MODX_BASE_PATH . INSTM_PATH);

// load classfile
$class_file = INSTM_BASE_PATH . '/classes/packagemanager.class.php';
if (!file_exists($class_file)) {
	$modx->messageQuit(sprintf('Classfile "%s" not found. Did you upload the module files?', $class_file));
}
require_once ($class_file);

$options = array();
$options['moduleId'] = (int) $_GET['id'];
$options['action'] = isset($_GET['action']) ? trim(strip_tags($_GET['action'])) : 'load';
$options['managerDir'] = MGR_DIR . '/';
$options['moduleUrl'] = INSTM_PATH;
$options['managerTheme'] = $modx->config['manager_theme'];

$packageManager = new PackageManager($modx, $options);
$output = $packageManager->run();

echo $output;
?>