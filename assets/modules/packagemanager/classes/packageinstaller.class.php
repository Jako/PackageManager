<?php
/*
 * Package Manager
 *
 * @package packagemanager
 * @subpackage installer_class_file
 *
 * @version 1.0-RC
 * @author Thomas Jakobi <thomas.jakobi@partout.info>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

class PackageInstaller {

	/**
	 * A reference to the DocumentParser instance
	 * @var DocumentParser $modx
	 */
	private $modx;

	/**
	 * A newChunkie instance
	 * @var newChunkie $chunkie
	 */
	private $chunkie;

	/**
	 * Global options.
	 * @var array $options
	 * @access private
	 */
	private $options;

	/**
	 * Global language.
	 * @var array $language
	 * @access private
	 */
	private $language;

	/**
	 * Internal package depedencies.
	 * @var array $depedencies
	 * @access private
	 */
	private $depedencies;

	/**
	 * PackageManager Class Constructor
	 *
	 * @param modX &$modx A reference to the modX instance.
	 * @param array $config An array of configuration options.
	 */
	function __construct($modx, $config = array(), $language = array()) {
		$this->modx = &$modx;
		$this->options = &$config;
		$this->language = &$language;
		$this->depedencies = array();

		if (!class_exists('newChunkie')) {
			include_once INSTM_BASE_PATH . 'classes/newchunkie.class.php';
		}
		$this->chunkie = new newChunkie($this->modx, array('basepath' => INSTM_PATH));
	}

	function installPackage($package, $backup = FALSE) {
		if ($this->modx->hasPermission('new_template') && $this->modx->hasPermission('new_chunk') && $this->modx->hasPermission('new_module') && $this->modx->hasPermission('new_snippet') && $this->modx->hasPermission('new_plugin')) {
			$result = array();
			$foldername = $this->options['packagesPath'] . $package . '/';
			$result = array_merge($result, $this->installFiles($foldername, $backup, $package));
			$result = array_merge($result, $this->installTemplates($foldername, $backup));
			$result = array_merge($result, $this->installTemplateVariables($foldername, $backup));
			$result = array_merge($result, $this->installChunks($foldername, $backup));
			$result = array_merge($result, $this->installModules($foldername, $backup));
			$result = array_merge($result, $this->installSnippets($foldername, $backup));
			$result = array_merge($result, $this->installPlugins($foldername, $backup));
			$result = array_merge($result, $this->setDepedencies());

			// Always empty cache after install
			include MODX_BASE_PATH . $this->options['managerDir'] . 'processors/cache_sync.class.processor.php';
			$sync = new synccache();
			$sync->setCachepath(MODX_BASE_PATH . 'assets/cache/');
			$sync->setReport(false);
			$sync->emptyCache();
		} else {
			$result[] = $this->resultMessage(array(
					), '[+lang.runtime_error_privileges_install+]', FALSE);
		}

		return $result;
	}

	private function installFiles($path, $backup, $package) {
		$result = array();
		// copy and backup all extra folders
		foreach (glob($path . 'assets/*/*', GLOB_ONLYDIR) as $foldername) {
			$parts = array();
			preg_match('#([^\/]*)\/([^\/]*)$#', $foldername, $parts);
			$type = $parts[1];
			$folder = $parts[2];
			// move files to backup if the package name starts with the folder name
			if ($backup && file_exists($this->options['assetsPath'] . $type . '/' . $folder) && strpos($package, $folder) === 0) {
				rename($this->options['assetsPath'] . $type . '/' . $folder, $this->options['assetsPath'] . $type . '/' . $folder . '.old');
				$result[] = $this->resultMessage(array(
					'folder' => $this->options['assetsPath'] . $type . '/' . $folder . '.old'
						), '[+lang.install_files_success_backup+]', TRUE);
			}
			$this->copyFolder($foldername, $this->options['assetsPath'] . $type . '/' . $folder);
			$result[] = $this->resultMessage(array(
				'folder' => $this->options['assetsPath'] . $type . '/' . $folder
					), '[+lang.install_files_success+]', TRUE);
		}
		// copy all other folders
		foreach (glob($path . 'assets/*', GLOB_ONLYDIR) as $foldername) {
			$parts = array();
			preg_match('#([^\/]*)$#', $foldername, $parts);
			$type = $parts[1];
			if (!in_array($type, array('backup', 'cache', 'modules', 'plugins', 'snippets'))) {
				$this->copyFolder($foldername, $this->options['assetsPath'] . $type . '/' . $folder);
				$result[] = $this->resultMessage(array(
					'folder' => $this->options['assetsPath'] . $type
						), '[+lang.install_files_success+]', TRUE);
			}
		}
		return $result;
	}

	private function installTemplates($path, $backup) {
		$result = array();
		$filenames = glob($path . 'install/assets/templates/*.tpl') ? glob($path . 'install/assets/templates/*.tpl') : array();
		foreach ($filenames as $filename) {
			$fileinfo = pathinfo($filename);
			$docblock = $this->parseDocblock($fileinfo['dirname'], $fileinfo['basename']);
			$code = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filename)));
			// remove installer docblock
			$code = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $code, 1);
			if (count($docblock)) {
				$fields = array(
					'name' => $docblock['name'],
					'description' => (!empty($docblock['version']) ? '<strong>' . $docblock['version'] . '</strong> ' : '') . $docblock['description'],
					'category' => $this->getCategoryId($docblock['modx_category']),
					'content' => $code,
					'locked' => (isset($docblock['lock_template']) && $docblock['lock_template']) ? 1 : 0
				);
				$result[] = $this->installType('templates', $fields, $docblock['version'], $backup);
			}
		}
		return $result;
	}

	private function installTemplateVariables($path, $backup) {
		$result = array();
		$filenames = glob($path . 'install/assets/tvs/*.tpl') ? glob($path . 'install/assets/tvs/*.tpl') : array();
		foreach ($filenames as $filename) {
			$fileinfo = pathinfo($filename);
			$docblock = $this->parseDocblock($fileinfo['dirname'], $fileinfo['basename']);
			if (count($docblock)) {
				$fields = array(
					'name' => $docblock['name'],
					'caption' => $docblock['caption'],
					'description' => (!empty($docblock['version']) ? '<strong>' . $docblock['version'] . '</strong> ' : '') . $docblock['description'],
					'type' => $docblock['input_type'],
					'elements' => $docblock['input_options'],
					'default_text' => $docblock['input_default'],
					'display' => $docblock['output_widget'],
					'display_params' => $docblock['output_widget_params'],
					'category' => $this->getCategoryId($docblock['modx_category']),
					'locked' => $docblock['lock_tv'],
				);
				$result[] = $this->installType('tmplvars', $fields, $docblock['version'], $backup);

				// add template assignments
				$assignments = isset($docblock['template_assignments']) ? explode(',', $docblock['template_assignments']) : array();
				if (count($assignments) > 0) {

					// remove existing tv -> template assignments
					$rs = $this->modx->db->select('id', $this->modx->getFullTableName('site_tmplvars'), 'name="' . $fields['name'] . '" AND description="' . $fields['description'] . '"');
					$id = $this->modx->db->getValue($rs);
					$this->modx->db->delete($this->modx->getFullTableName('site_tmplvar_templates'), 'tmplvarid=' . $id);

					// add tv -> template assignments
					foreach ($assignments as $template) {
						$where = ($template != '*') ? 'WHERE templatename="' . $this->modx->db->escape($template) . '"' : '';
						$rsa = $this->modx->db->select('id', $this->modx->getFullTableName('site_templates'), $where);
						if ($this->modx->db->getRecordCount($rsa)) {
							$templateId = $this->modx->db->getValue($rsa);
							$this->modx->db->insert(array('tmplvarid' => $id, 'templateid' => $templateId), $this->modx->getFullTableName('site_tmplvar_templates'));
						}
					}
				}
			}
		}
		return $result;
	}

	private function installChunks($path, $backup) {
		$result = array();
		$filenames = glob($path . 'install/assets/chunks/*.tpl') ? glob($path . 'install/assets/chunks/*.tpl') : array();
		foreach ($filenames as $filename) {
			$fileinfo = pathinfo($filename);
			$docblock = $this->parseDocblock($fileinfo['dirname'], $fileinfo['basename']);
			$code = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filename)));
			// remove installer docblock
			$code = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $code, 1);
			if (count($docblock)) {
				$fields = array(
					'name' => $docblock['name'],
					'description' => (!empty($docblock['version']) ? '<strong>' . $docblock['version'] . '</strong> ' : '') . $docblock['description'],
					'category' => $this->getCategoryId($docblock['modx_category']),
					'snippet' => $code
				);
				$overwrite = ($docblock['overwrite'] == 'false' || $backup) ? TRUE : FALSE;
				$result[] = $this->installType('htmlsnippets', $fields, $docblock['version'], $overwrite);
			}
		}
		return $result;
	}

	private function installModules($path, $backup) {
		$result = array();
		$filenames = glob($path . 'install/assets/modules/*.tpl') ? glob($path . 'install/assets/modules/*.tpl') : array();
		foreach ($filenames as $filename) {
			$fileinfo = pathinfo($filename);
			$docblock = $this->parseDocblock($fileinfo['dirname'], $fileinfo['basename']);
			$code = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filename)));
			// remove installer docblock
			$code = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $code, 1);
			if (count($docblock)) {
				if (isset($docblock['dependencies'])) {
					$this->prepareDepedencies($docblock['name'], $docblock['dependencies']);
				}
				$fields = array(
					'name' => $docblock['name'],
					'description' => (!empty($docblock['version']) ? '<strong>' . $docblock['version'] . '</strong> ' : '') . $docblock['description'],
					'category' => $this->getCategoryId($docblock['modx_category']),
					'modulecode' => $code,
					'properties' => $docblock['properties'],
					'guid' => $docblock['guid'],
					'enable_sharedparams' => isset($docblock['dependencies'])
				);
				$result[] = $this->installType('modules', $fields, $docblock['version'], $backup);
			}
		}
		return $result;
	}

	private function installSnippets($path, $backup) {
		$result = array();
		$filenames = glob($path . 'install/assets/snippets/*.tpl') ? glob($path . 'install/assets/snippets/*.tpl') : array();
		foreach ($filenames as $filename) {
			$fileinfo = pathinfo($filename);
			$docblock = $this->parseDocblock($fileinfo['dirname'], $fileinfo['basename']);
			$code = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filename)));
			// remove installer docblock
			$code = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $code, 1);
			if (count($docblock)) {
				$fields = array(
					'name' => $docblock['name'],
					'description' => (!empty($docblock['version']) ? '<strong>' . $docblock['version'] . '</strong> ' : '') . $docblock['description'],
					'category' => $this->getCategoryId($docblock['modx_category']),
					'snippet' => $code,
					'properties' => $docblock['properties']
				);
				$result[] = $this->installType('snippets', $fields, $docblock['version'], $backup);
			}
		}
		return $result;
	}

	private function installPlugins($path, $backup) {
		$result = array();
		$filenames = glob($path . 'install/assets/plugins/*.tpl') ? glob($path . 'install/assets/plugins/*.tpl') : array();
		foreach ($filenames as $filename) {
			$fileinfo = pathinfo($filename);
			$docblock = $this->parseDocblock($fileinfo['dirname'], $fileinfo['basename']);
			$code = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filename)));
			// remove installer docblock
			$code = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $code, 1);
			if (count($docblock)) {
				$fields = array(
					'name' => $docblock['name'],
					'description' => (!empty($docblock['version']) ? '<strong>' . $docblock['version'] . '</strong> ' : '') . $docblock['description'],
					'category' => $this->getCategoryId($docblock['modx_category']),
					'plugincode' => $code,
					'properties' => $docblock['properties'],
					'disabled' => (isset($docblock['disabled']) && $docblock['disabled']) ? 1 : 0
				);
				$result[] = $this->installType('plugins', $fields, $docblock['version'], $backup);

				// disable legacy versions based on legacy_names provided
				if (isset($docblock['legacy_names'])) {
					$legacies = explode(',', $docblock['legacy_names']);
					array_walk($legacies, create_function('&$val', '$val = trim($val);'));
					$rs = $this->modx->db->update(array('disabled' => 1), $this->modx->getFullTableName('site_plugins'), 'name IN(' . $this->modx->db->escape(implode(',', $legacies)) . ')');
					$result[] = $this->resultMessage(array(
						'legacies' => implode(', ', $legacies)
							), '[+lang.install_legacy+]', TRUE);
				}

				// add system events
				$events = isset($docblock['events']) ? explode(',', $docblock['events']) : array();
				if (!empty($events)) {
					$rs = $this->modx->db->select('id', $this->modx->getFullTableName('site_plugins'), 'name="' . $fields['name'] . '" AND description="' . $fields['description'] . '"');
					if ($this->modx->db->getRecordCount($rs)) {
						$id = $this->modx->db->getValue($rs);
						// remove existing events
						$this->modx->db->delete($this->modx->getFullTableName('site_plugin_events'), 'pluginid = ' . $id);
						// add new events
						foreach ($events as $event) {
							$event = $this->modx->db->escape($event);
							$rse = $this->modx->db->select('id', $this->modx->getFullTableName('system_eventnames'), 'name="' . $event . '"');
							if ($this->modx->db->getRecordCount($rse)) {
								$evtid = $this->modx->db->getValue($rse);
								$this->modx->db->insert(array('pluginid' => $id, 'evtid' => $evtid), $this->modx->getFullTableName('site_plugin_events'));
							}
						}
					}
				}
			}
		}
		return $result;
	}

	private function prepareDepedencies($modulename, $dependencies) {
		$dependencies = explode(',', $dependencies);
		foreach ($dependencies as $dependency) {
			$dependency = explode(':', $dependency);
			$type = $dependency[0];
			$name = isset($dependency[1]) ? trim($dependency[1]) : '';
			switch ($type) {
				case 'template':
					$this->depedencies[] = array(
						'module' => $modulename,
						'table' => 'templates',
						'column' => 'templatename',
						'type' => 50,
						'name' => $name
					);
					break;
				case 'tv':
				case 'tmplvar':
					$this->depedencies[] = array(
						'module' => $modulename,
						'table' => 'tmplvars',
						'column' => 'name',
						'type' => 60,
						'name' => $name
					);
					break;
				case 'chunk':
				case 'htmlsnippet':
					$this->depedencies[] = array(
						'module' => $modulename,
						'table' => 'htmlsnippets',
						'column' => 'name',
						'type' => 10,
						'name' => $name
					);
					break;
				case 'snippet':
					$this->depedencies[] = array(
						'module' => $modulename,
						'table' => 'snippets',
						'column' => 'name',
						'type' => 40,
						'name' => $name
					);
					break;
				case 'plugin':
					$this->depedencies[] = array(
						'module' => $modulename,
						'table' => 'plugins',
						'column' => 'name',
						'type' => 30,
						'name' => $name
					);
					break;
				case 'resource':
					$this->depedencies[] = array(
						'module' => $modulename,
						'table' => 'content',
						'column' => 'pagetitle',
						'type' => 20,
						'name' => $name
					);
					break;
			}
		}
	}

	private function setDepedencies() {
		$result = array();
		foreach ($this->depedencies as $dependency) {
			$rs = $this->modx->db->select('id, guid', $this->modx->getFullTableName('site_modules'), 'name="' . $dependency['module'] . '"');
			$row = $this->modx->db->getRow($rs);
			$moduleId = $row['id'];
			$moduleGuid = $row['guid'];

			// get extra id
			$rs = $this->modx->db->select('id', $this->modx->getFullTableName('site_' . $dependency['table']), $dependency['column'] . '="' . $dependency['name'] . '"');
			$extraId = $this->modx->db->getValue($rs);

			// setup extra as module dependency
			$rs = $this->modx->db->select('module', $this->modx->getFullTableName('site_module_depobj'), 'module=' . $moduleId . ' AND resource=' . $extraId . ' AND type=' . $dependency['type'] . ' LIMIT 1');
			if (!$this->modx->db->getRecordCount($rs)) {
				$this->modx->db->insert(array('module' => $moduleId, 'resource' => $extraId, 'type' => $dependency['type']), $this->modx->getFullTableName('site_module_depobj'));
				$result[] = $this->resultMessage(array(
					'modulename' => $dependency['module']
						), '[+lang.install_depedency_created+]', TRUE);
			} else {
				$this->modx->db->update(array('module' => $moduleId, 'resource' => $extraId, 'type' => $dependency['type']), $this->modx->getFullTableName('site_module_depobj'), 'module=' . $moduleId . ' AND resource=' . $extraId . ' AND type=' . $dependency['type']);
				$result[] = $this->resultMessage(array(
					'modulename' => $dependency['module']
						), '[+lang.install_depedency_updated+]', TRUE);
			}
			if ($dependency['type'] == 30 || $dependency['type'] == 40) {
				// set extra guid for plugins and snippets
				$rs = $this->modx->db->select('id', $this->modx->getFullTableName('site_' . $dependency['table']), 'id=' . $extraId . ' LIMIT 1');
				if ($this->modx->db->getRecordCount($rs)) {
					$this->modx->db->update(array('moduleguid' => $moduleGuid), $this->modx->getFullTableName('site_' . $dependency['table']), 'id=' . $extraId);
					$result[] = $this->resultMessage(array(
						'depedencyname' => $dependency['name'],
						'type' => ($dependency['type'] == 30) ? $this->language['plugins_singular'] : $this->language['snippets_singular']
							), '[+lang.install_depedency_guid_set+]', TRUE);
				}
			}
		}
		return $result;
	}

	private function installType($type, $fields, $version, $backup) {
		$fields = $this->modx->db->escape($fields);
		$version = $this->modx->db->escape($version);
		$rs = $this->modx->db->select('*', $this->modx->getFullTableName('site_' . $type), 'name = "' . $fields['name'] . '"');
		if ($this->modx->db->getRecordCount($rs) && !$backup) {
			$row = $this->modx->db->getRow($rs);
			if ($fields['properties']) {
				$fields['properties'] = $this->propertiesUpdate($fields['properties'], $row['properties']);
			}
			if (!$this->modx->db->update($fields, $this->modx->getFullTableName('site_' . $type), 'name = "' . $fields['name'] . '"')) {
				$result = $this->resultMessage(array(
					'type' => $this->language[$type . '_singular'],
					'name' => $fields['name'] . ' ' . $version,
					'error' => $this->modx->db->getLastError()
						), '[+lang.install_update_error+]', FALSE);
			} else {
				$result = $this->resultMessage(array(
					'type' => $this->language[$type . '_singular'],
					'name' => $fields['name'] . ' ' . $version
						), '[+lang.install_update_success+]', TRUE);
			}
		} else {
			if ($backup && $this->modx->db->getRecordCount($rs)) {
				$row = $this->modx->db->getRow($rs);
				if (isset($fields['disabled'])) {
					$row['disabled'] = 1;
				}
				if ($fields['properties']) {
					$row['properties'] = $this->propertiesUpdate($fields['properties'], $row['properties']);
				}
				$row['name'] = $row['name'] . ' old';
				$row = $this->modx->db->escape($row);
				$this->modx->db->update($row, $this->modx->getFullTableName('site_' . $type), 'name = "' . $fields['name'] . '"');
			}
			if (!$this->modx->db->insert($fields, $this->modx->getFullTableName('site_' . $type))) {
				$result = $this->resultMessage(array(
					'type' => $this->language[$type . '_singular'],
					'name' => $fields['name'] . ' ' . $version,
					'error' => $this->modx->db->getLastError()
						), '[+lang.install_add_error+]', FALSE);
			} else {
				$result = $this->resultMessage(array(
					'type' => $this->language[$type . '_singular'],
					'name' => $fields['name'] . ' ' . $version
						), '[+lang.install_add_success+]', TRUE);
			}
		}
		return $result;
	}

	// Helper functions

	public function resultMessage($placeholder, $template, $success) {
		$this->chunkie->setPlaceholders($placeholder, '', '', 'message');
		$this->chunkie->setPlaceholder('lang', $this->language, 'message');
		$this->chunkie->setPlaceholder('options', $this->options, 'message');
		$this->chunkie->setTpl($template);
		$this->chunkie->prepareTemplate('', array(), 'message');

		$result = new stdClass();
		$result->message = $this->chunkie->process('message');
		$result->success = $success;

		return $result;
	}

	public function parseDocblock($folder, $filename) {
		$result = array();
		if (is_readable($folder . '/' . $filename)) {
			$tpl = @fopen($folder . '/' . $filename, 'r');
			if ($tpl) {
				$result['filename'] = $filename;
				$docblockStartFound = false;
				$nameFound = false;
				$descriptionFound = false;
				$docblockEndFound = false;

				while (!feof($tpl)) {
					$line = fgets($tpl);
					if (!$docblockStartFound) {
						// find docblock start
						if (strpos($line, '/**') !== false) {
							$docblockStartFound = true;
						}
						continue;
					} elseif (!$nameFound) {
						// find name
						$ma = null;
						if (preg_match('/^\s+\*\s+(.+)/', $line, $ma)) {
							$result['name'] = trim($ma[1]);
							$nameFound = !empty($result['name']);
						}
						continue;
					} elseif (!$descriptionFound) {
						// find description
						$ma = null;
						if (preg_match('/^\s+\*\s+(.+)/', $line, $ma)) {
							$result['description'] = trim($ma[1]);
							$descriptionFound = !empty($result['description']);
						}
						continue;
					} else {
						$ma = null;
						if (preg_match('/^\s+\*\s+\@([^\s]+)\s+(.+)/', $line, $ma)) {
							$param = trim($ma[1]);
							$val = trim($ma[2]);
							if (!empty($param) && !empty($val)) {
								if ($param == 'internal') {
									$ma = null;
									if (preg_match('/\@([^\s]+)\s+(.+)/', $val, $ma)) {
										$param = trim($ma[1]);
										$val = trim($ma[2]);
									}
									if (empty($param)) {
										continue;
									}
								}
								$result[$param] = $val;
							}
						} elseif (preg_match('/^\s*\*\/\s*$/', $line)) {
							$docblockEndFound = true;
							break;
						}
					}
				}
				@fclose($tpl);
			}
		}
		return $result;
	}

	private function getCategoryId($category) {
		$categoryId = 0;
		if (!empty($category)) {
			$category = $this->modx->db->escape($category);
			$rs = $this->modx->db->select('id', $this->modx->getFullTableName('categories'), 'category = "' . $category . '"');
			if ($this->modx->db->getRecordCount($rs)) {
				$categoryId = $this->modx->db->getValue($rs);
			} else {
				$categoryId = $this->modx->db->insert(array('category' => $category), $this->modx->getFullTableName('categories'));
			}
		}
		return $categoryId;
	}

	private function propertiesUpdate($new, $old) {
		// Split properties up into arrays
		$returnProperties = array();
		$newProperties = explode('&', $new);
		$oldProperties = explode('&', $old);

		// Get new properties
		foreach ($newProperties as $k => $v) {
			if (!empty($v)) {
				list($propKey, $propValue) = explode('=', trim($v));
				$returnProperties[trim($propKey)] = trim($propValue);
			}
		}
		// Overwrite with old properties
		foreach ($oldProperties as $k => $v) {
			if (!empty($v)) {
				list($propKey, $propValue) = explode('=', trim($v));
				$returnProperties[trim($propKey)] = trim($propValue);
			}
		}

		$result = '';
		// Build new string for new properties value
		foreach ($returnProperties as $k => $v) {
			$result .= '&' . $k . '=' . $v . ' ';
		}

		return $result;
	}

	private function copyFolder($source, $destination) {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
		if (!file_exists($destination)) {
			mkdir($destination, intval($this->modx->config['new_folder_permissions'], 8));
		}
		foreach ($iterator as $item) {
			if ($item->isDir()) {
				if (!file_exists($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName())) {
					mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName(), intval($this->modx->config['new_folder_permissions'], 8));
				}
			} else {
				copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			}
		}
	}

}
