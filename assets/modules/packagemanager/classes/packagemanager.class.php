<?php
/*
 * Package Manager
 *
 * @package packagemanager
 * @subpackage class_file
 *
 * @version 0.9
 * @author Thomas Jakobi <thomas.jakobi@partout.info>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

class PackageManager {

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
	 * Installed packages.
	 * @var array $installed
	 * @access private
	 */
	private $installed;

	/**
	 * Package types.
	 * @var array $types
	 * @access private
	 */
	private $types;

	/**
	 * Installer class.
	 * @var PackageInstaller $installer
	 * @access private
	 */
	private $installer;

	/**
	 * PackageManager Class Constructor
	 *
	 * @param modX &$modx A reference to the modX instance.
	 * @param array $config An array of configuration options.
	 */
	function __construct($modx, $config = array()) {
		$this->modx = &$modx;
		$this->options = $config;

		// load localization
		include_once INSTM_BASE_PATH . 'lang/english.inc.php';

		$language = $this->modx->config['manager_language'];
		if ($language != 'english') {
			$lang_file = INSTM_BASE_PATH . 'lang/' . $language . '.inc.php';
			if (file_exists($lang_file)) {
				include_once $lang_file;
			}
		}
		$this->language = $_lang;

		$this->types = array();
		$this->types['snippets'] = (object) array(
					'type' => 'snippets',
					'name' => $this->language['snippets'],
					'name_singular' => $this->language['snippets_singular'],
					'edit' => 22,
					'delete' => 25
		);
		$this->types['plugins'] = (object) array(
					'type' => 'plugins',
					'name' => $this->language['plugins'],
					'name_singular' => $this->language['plugins_singular'],
					'edit' => 102,
					'delete' => 104
		);
		$this->types['modules'] = (object) array(
					'type' => 'modules',
					'name' => $this->language['modules'],
					'name_singular' => $this->language['modules_singular'],
					'edit' => 108,
					'delete' => 110
		);

		$this->options['packagesPath'] = MODX_BASE_PATH . 'assets/packages/';
		$this->options['cachePath'] = MODX_BASE_PATH . 'assets/cache/packages/';
		$this->options['assetsPath'] = MODX_BASE_PATH . 'assets/';

		if (!file_exists($this->options['cachePath'])) {
			mkdir($this->options['cachePath'], intval($this->modx->config['new_folder_permissions'], 8));
		}

		if (!class_exists('newChunkie')) {
			include_once INSTM_BASE_PATH . 'classes/newchunkie.class.php';
		}
		if (!class_exists('Pagination')) {
			include_once INSTM_BASE_PATH . 'classes/pagination.class.php';
		}
		if (!class_exists('PackageInstaller')) {
			include_once INSTM_BASE_PATH . 'classes/packageinstaller.class.php';
		}
		$this->chunkie = new newChunkie($this->modx, array('basepath' => INSTM_PATH));
		$this->installer = new PackageInstaller($this->modx, $this->options, $this->language);
		$this->getInstalled();
	}

	/**
	 * Returns the module results
	 *
	 * @return string The module output
	 */
	public function run() {
		$output = '';
		switch ($this->options['action']) {
			case 'list_installed': {
					echo $this->displayInstalled();
					exit();
				}
			case 'upload_local': {
					echo $this->uploadLocalPackages();
					exit();
				}
			case 'load':
			default : {
					$this->chunkie->setPlaceholder('lang', $this->language, 'module');
					$this->chunkie->setPlaceholder('options', $this->options, 'module');
					$this->chunkie->setPlaceholder('packagesInstalled', $this->displayInstalled(), 'module');
					$this->chunkie->setPlaceholder('uploadLocal', $this->uploadLocalPackages(), 'module');
					$this->chunkie->setPlaceholder('packagesLocal', $this->getLocalPackages(), 'module');

					$this->chunkie->setTpl($this->chunkie->getTemplateChunk('@FILE templates/module.template.html'));
					$this->chunkie->prepareTemplate('', array(), 'module');

					$output = $this->chunkie->process('module');
				}
		}
		return $output;
	}

	/**
	 * Get installed packages
	 * 
	 * @return array Array of installed packages
	 * @return void
	 */
	private function getInstalled() {
		$this->installed = array();

		foreach ($this->types as $type) {
			$this->getInstalledType($type->type);
		}
		uasort($this->installed, array($this, 'sortByPackageName'));
		return $this->installed;
	}

	/**
	 * Get installed packages of a single type
	 *
	 * @param type $type
	 * @return void
	 */
	private function getInstalledType($type = 'snippets') {
		$res = $this->modx->db->select('*', $this->modx->getFullTableName('site_' . $type));
		if ($this->modx->db->getRecordCount($res)) {
			while ($row = $this->modx->db->getRow($res)) {
				if (!array_key_exists($row['name'], $this->installed)) {
					if (!isset($row['version'])) {
						$version = (strpos($row['description'], '<strong>') === FALSE) ? 'unknown' : $row['description'];
						$version = preg_replace('#<strong>(.*)<\/strong>.*#i', '$1', $version);
					} else {
						$version = $row['version'];
					}
					$installed = array(
						'version' => ($version) ? strtolower($version) : 'unknown',
						'description' => preg_replace('#<strong>.*<\/strong>(.*)#i', '$1', $row['description']),
						'name' => $row['name'],
						'type' => array($row['id'] => $type)
					);
					$this->installed[$row['name']] = $installed;
				} else {
					$this->installed[$row['name']]['type'][$row['id']] = $type;
				}
			}
		}
	}

	/**
	 * Filter the installed packages
	 *
	 * @param array $filter Array of filter setting objects
	 * @return array
	 */
	private function filterInstalled($filter) {
		$filtered = array();
		foreach ($this->installed as $installed) {
			if ($filter['type'] && $this->installed['type'] != $filter['type']->type) {
				continue;
			}
			if ($filter['search'] && (stripos($installed['name'], $filter['search']) === FALSE && stripos($installed['description'], $filter['search']) === FALSE)) {
				continue;
			}
			$filtered[] = $installed;
		}
		return $filtered;
	}

	/**
	 * Display the installed packages
	 *
	 * @return string The installed packages list
	 */
	private function displayInstalled() {
		$page = (isset($_REQUEST['page']) && $_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
		$limit = 10;
		$search = (isset($_POST['search']) && !isset($_POST['submit_reset'])) ? $this->modx->stripTags($_POST['search']) : '';
		$filter = array('search' => $search);

		$filtered = $this->filterInstalled($filter);
		$countFiltered = count($filtered);
		$filtered = array_slice($filtered, ($page - 1) * $limit, $limit, TRUE);

		$i = 0;
		foreach ($filtered as $installed) {
			$this->chunkie->setPlaceholders($installed, $i);
			$j = 0;
			foreach ($installed['type'] as $key => $value) {
				$this->chunkie->setPlaceholder($j . '.name', $this->types[$value]->name_singular, 'type');
				$this->chunkie->setPlaceholder($j . '.edit', $this->types[$value]->edit, 'type');
				$this->chunkie->setPlaceholder($j . '.delete', $this->types[$value]->delete, 'type');
				$this->chunkie->setPlaceholder($j . '.id', $key, 'type');
				$this->chunkie->setPlaceholder('lang', $this->language, 'type');
				$this->chunkie->setPlaceholder('options', $this->options, 'type');
				$this->chunkie->setTpl($this->chunkie->getTemplateChunk('@FILE templates/installedPackageType.template.html'));
				$this->chunkie->prepareTemplate($j, array(), 'type');
				$j++;
			}
			$this->chunkie->setPlaceholder($i . '.type', $this->chunkie->process('type', "<br/>\n"));
			$this->chunkie->setPlaceholder('lang', $this->language);
			$this->chunkie->setPlaceholder('options', $this->options);
			$this->chunkie->setTpl($this->chunkie->getTemplateChunk('@FILE templates/installedPackage.template.html'));
			$this->chunkie->prepareTemplate($i);
			$i++;
		}
		$wrapper = $this->chunkie->process();

		$p = new Pagination(array(
			'per_page' => $limit,
			'use_page_numbers' => TRUE,
			'num_links' => 5,
			'cur_page' => $page,
			'total_rows' => $countFiltered,
			'first_link' => '«',
			'prev_link' => '‹',
			'next_link' => '›',
			'last_link' => '»',
			'original_query_string' => ($search) ? '&search=' . urlencode($search) : '',
			'cur_url' => 'index.php?a=112&id=' . $this->options['moduleId']
		));
		$this->chunkie->setPlaceholder('pagination', $p->create_links());
		if (!$filter) {
			$this->chunkie->setPlaceholder('name', 'Packages');
			$this->chunkie->setPlaceholder('type', '');
		} else {
			$this->chunkie->setPlaceholder('name', $filter['type']->name);
			$this->chunkie->setPlaceholder('type', $filter['type']->type);
		}
		$this->chunkie->setPlaceholder('lang', $this->language);
		$this->chunkie->setPlaceholder('options', $this->options);
		$this->chunkie->setPlaceholder('page', ($page > 1) ? $page : '');
		$this->chunkie->setPlaceholder('search', $search);
		$this->chunkie->setPlaceholder('wrapper', $wrapper);
		$this->chunkie->setTpl($this->chunkie->getTemplateChunk('@FILE templates/installedPackages.template.html'));
		$this->chunkie->prepareTemplate();
		return $this->chunkie->process();
	}

	/**
	 * Get and display local downloaded packages (unzip new ones and delete selected ones)
	 *
	 * @return string The local packages list
	 */
	private function getLocalPackages() {
		$messages = array();
		if ($result = $this->deleteLocalPackage()) {
			$messages[] = $result;
		};
		if ($result = $this->unzipLocalPackages()) {
			$messages = array_merge($messages, $result);
		};
		if ($result = $this->installLocalPackage()) {
			$messages = array_merge($messages, $result);
		};
		$packages = $this->getLocalPackagesInfo();
		return $this->displayLocalPackages($packages, $messages);
	}

	/**
	 * Unzip local packages
	 *
	 * @return array Unzip result messages
	 */
	private function unzipLocalPackages() {
		$errors = array();
		foreach (glob($this->options['packagesPath'] . '*.zip') as $filename) {
			$result = $this->unzipLocalPackage($filename);
			if ($result != '') {
				$errors[] = $result;
			}
		}
		return $errors;
	}

	/**
	 * Unzip a local package
	 *
	 * @param string $file The name of the zip file
	 * @return string Unzip result message
	 */
	private function unzipLocalPackage($file) {
		$fileinfo = pathinfo($file);
		$extractFolder = $fileinfo['dirname'] . '/' . $fileinfo['filename'];
		$error = '';
		if (!file_exists($extractFolder)) {
			mkdir($extractFolder, intval($this->modx->config['new_folder_permissions'], 8));
			$zip = new ZipArchive;
			$res = $zip->open($file);
			if ($res === TRUE) {
				if (!$zip->extractTo($extractFolder)) {
					$error = $this->createMessage(array(
						'filename' => $fileinfo['basename']
							), '[+lang.file_extract_error+]');
				}
			} else {
				$error = $this->createMessage(array(
					'filename' => $fileinfo['basename']
						), '[+lang.file_extract_error_open+]');
			}
			$zip->close();
		}
		return $error;
	}

	/**
	 * Zip a local package
	 *
	 * @param string $file The name of the zip file
	 * @param string $folder The name of the packed folder
	 * @return string Unzip result message
	 */
	private function zipLocalPackage($file, $folder) {
		$pathinfo = pathinfo($folder);
		$dirname = $pathInfo['basename'];

		$zip = new ZipArchive();
		$zip->open($file, ZIPARCHIVE::CREATE);
		$this->folderToZip($folder, $zip, strlen($folder . '/'));
		$zip->close();
	}

	/**
	 * Add files and sub-directories in a folder to zip file.
	 *
	 * @param string $folder
	 * @param ZipArchive $zipFile
	 * @param int $prefixLength Number of chars to be removed from the file path.
	 */
	private function folderToZip($folder, &$zipFile, $prefixLength) {
		$handle = opendir($folder);
		if (!$handle) {
			die(var_dump($folder));
		}
		while (FALSE !== $f = readdir($handle)) {
			if ($f != '.' && $f != '..') {
				$filePath = "$folder/$f";
				// Remove prefix from file path before add to zip.
				$localPath = substr($filePath, $prefixLength);
				if (is_file($filePath)) {
					$zipFile->addFile($filePath, $localPath);
				} elseif (is_dir($filePath)) {
					// Add sub-directory.
					$zipFile->addEmptyDir($localPath);
					self::folderToZip($filePath, $zipFile, $prefixLength);
				}
			}
		}
		closedir($handle);
	}

	/**
	 * Get the local packages information
	 *
	 * @param string $path The folder containing the unzipped package
	 * @return array The packages information
	 */
	private function getLocalPackagesInfo($path = '') {
		$packages = array();
		$path = ($path) ? $path : $this->options['packagesPath'];
		$filenames = (glob($path . '*/install/assets/*/*.tpl')) ? glob($path . '*/install/assets/*/*.tpl') : array();
		foreach ($filenames as $filename) {
			$fileinfo = pathinfo($filename);
			$parts = array();
			if (preg_match('#([^\/]*)\/([^\/]*)\/([^\/]*)\/([^\/]*)$#', $fileinfo['dirname'], $parts)) {
				if (!array_key_exists($parts[1], $packages)) {
					$docblock = $this->parseDocblock($fileinfo['dirname'], $fileinfo['basename']);
					$package = array_merge($docblock, array(
						'type' => array($parts[4]),
						'package' => $parts[1]
					));
					unset($package['filename']);
					$packages[$parts[1]] = $package;
				} else {
					$packages[$parts[1]]['type'][] = $parts[4];
				}
			}
		}
		return $packages;
	}

	/**
	 * Display local downloaded packages and installer/dowloader/unzip/delete messages
	 *
	 * @param array $packages The local packages information
	 * @param array $messages The installer/dowloader/unzip messages
	 * @return string The installed packages list and the installer/dowloader/unzip/delete messages
	 */
	private function displayLocalPackages($packages, $messages) {
		$i = 0;
		if ($packages) {
			foreach ($packages as $package) {
				$this->chunkie->setPlaceholders($package, $i);
				$localType = array();
				foreach ($package['type'] as $value) {
					$localType[] = $this->language[$value . '_singular'];
				}
				$this->chunkie->setPlaceholder($i . '.type', implode(', ', $localType));
				$this->chunkie->setPlaceholder('lang', $this->language);
				$this->chunkie->setPlaceholder('options', $this->options);
				$this->chunkie->setTpl($this->chunkie->getTemplateChunk('@FILE templates/localPackage.template.html'));
				$this->chunkie->prepareTemplate($i);
				$i++;
			}
			$wrapper = $this->chunkie->process();
		} else {
			$wrapper = $this->language['no_local_packages'];
		}

		$this->chunkie->setPlaceholder('name', $this->language['packages']);
		$this->chunkie->setPlaceholder('type', '');
		$this->chunkie->setPlaceholder('messages', implode("<br/>\n", $messages));
		$this->chunkie->setPlaceholder('lang', $this->language);
		$this->chunkie->setPlaceholder('options', $this->options);
		$this->chunkie->setPlaceholder('wrapper', $wrapper);
		$this->chunkie->setTpl($this->chunkie->getTemplateChunk('@FILE templates/localPackages.template.html'));
		$this->chunkie->prepareTemplate();
		return $this->chunkie->process();
	}

	/**
	 * Delete a local package
	 *
	 * @return string The delete result message
	 */
	private function deleteLocalPackage() {
		$result = '';
		if (isset($_POST['submit_delete'])) {
			$package = (isset($_POST['package'])) ? $this->modx->stripTags($_POST['package']) : '';
			$foldername = $this->options['packagesPath'] . $package;
			$filename = $foldername . '.zip';
			if (file_exists($filename)) {
				unlink($filename);
			}
			$this->removeFolder($this->options['packagesPath'] . $package);
			$result = $this->language['file_delete_success'];
		}
		return $result;
	}

	/**
	 * Upload local packages and return the result
	 *
	 * @return string The upload local package message
	 */
	private function uploadLocalPackages() {
		$this->chunkie->setPlaceholder('uploadResult', $this->uploadLocalResult());
		$this->chunkie->setPlaceholder('lang', $this->language);
		$this->chunkie->setPlaceholder('options', $this->options);
		$this->chunkie->setTpl($this->chunkie->getTemplateChunk('@FILE templates/localUpload.template.html'));
		$this->chunkie->prepareTemplate();
		return $this->chunkie->process();
	}

	/**
	 * Execute the upload and return the upload message
	 *
	 * @return string The upload result message
	 */
	private function uploadLocalResult() {
		$result = '';
		if (isset($_POST['submit_upload']) && isset($_FILES['upload'])) {
			if (!$_FILES['upload']['error'] || (isset($_POST['remote']) && $_POST['remote'] != '')) {
				$tmpname = uniqid('package_');
				$zipname = $this->options['cachePath'] . $tmpname . '.zip';
				$extractFolder = $this->options['cachePath'] . $tmpname;
				if ($_FILES['upload']['type'] == 'application/zip' || $_FILES['upload']['type'] == 'application/x-zip-compressed' || $_FILES['upload']['type'] == 'multipart/x-zip' || $_FILES['upload']['type'] == 'application/x-compressed' || $_FILES['upload']['type'] == 'application/octet-stream') {
					move_uploaded_file($_FILES['upload']['tmp_name'], $zipname);
					// Allow directly downloaded GitHub packages
					$packageFolder = $extractFolder . '/' . substr($_FILES['upload']['name'], 0, -4);
					$result = $this->uploadLocalCheck($zipname, $extractFolder, $packageFolder);
				} elseif (isset($_POST['remote']) && $_POST['remote'] != '') {
					$fp = fopen($zipname, 'w+');
					$ch = curl_init($_POST['remote']);
					curl_setopt($ch, CURLOPT_TIMEOUT, 50);
					curl_setopt($ch, CURLOPT_FILE, $fp);
					$this->curl_exec_follow($ch);
					curl_close($ch);
					fclose($fp);
					$remotePath = pathinfo($_POST['remote']);
					if ($remotePath['dirname'] != '.') {
						$result = $this->uploadLocalCheck($zipname, $extractFolder, substr($remotePath['basename'], 0, -4));
					} else {
						$result = 'No file';
					}
				} else {
					$result = $this->createMessage(array(
						'filename' => $_FILES['upload']['name']
							), '[+lang.file_upload_error_type+]');
				}
			} else {
				if ($_FILES['upload']['name']) {
					$result = $this->createMessage(array(
						'filename' => $_FILES['upload']['name']
							), '[+lang.file_upload_error+]');
				} else {
					$result = $this->createMessage(array(
						'filename' => $_FILES['upload']['name']
							), '[+lang.file_upload_nofile+]');
				}
			}
		}
		return $result;
	}

	/**
	 * Check uploaded file and move to package folder on success
	 *
	 * @param type $zipname Name of the uploaded zip file
	 * @param type $extractFolder
	 * @return type
	 */
	private function uploadLocalCheck($zipname, $extractFolder, $packageFolder) {
		$result = '';
		if (!$result = $this->unzipFile($zipname, $extractFolder)) {
			$info = $this->getLocalPackagesInfo($extractFolder);
			$subfolders = glob($extractFolder . '/*', GLOB_ONLYDIR);
			if (!$info && $subfolders) {
				$info = $this->getLocalPackagesInfo($subfolders[0]);
				if ($info) {
					unlink($zipname);
					$this->zipLocalPackage($zipname, $subfolders[0]);
				}
			}
			if ($info) {
				$info = reset($info);
				$newname = strtolower($info[name]) . '-' . strtolower($info['version']) . '.zip';
				$packagename = $info[name] . ' ' . $info['version'];
				if (file_exists($this->options['packagesPath'] . $newname)) {
					$result = $this->createMessage(array(
						'package' => $info[name] . ' ' . $info['version']
							), '[+lang.file_upload_error_exists+]');
				} else {
					if (!rename($zipname, $this->options['packagesPath'] . $newname)) {
						unlink($zipname);
						$this->removeFolder($extractFolder);
						$result = $this->createMessage(array(
							'filename' => $packagename
								), '[+lang.file_upload_error+]');
					} else {
						$this->removeFolder($extractFolder);
						$result = $this->createMessage(array(
							'filename' => $packagename
								), '[+lang.file_upload_success+]');
					}
				}
			} else {
				unlink($zipname);
				$this->removeFolder($extractFolder);
				$result = $this->createMessage(array(
					'filename' => ($_FILES['upload']['name'] != '') ? $_FILES['upload']['name'] : $this->language['remote_files_singular']
						), '[+lang.file_upload_error_package+]');
			}
		}
		return $result;
	}

	/**
	 * Install local package
	 *
	 * @return array The install local package result messages
	 */
	private function installLocalPackage() {
		$result = '';
		$backup = ($_POST['updatemode'] == 'backup') ? TRUE : FALSE;
		if (isset($_POST['submit_install'])) {
			$package = (isset($_POST['package'])) ? $this->modx->stripTags($_POST['package']) : '';
			$result = $this->installer->installPackage($package, $backup);
		}
		return $result;
	}

	// Helper functions

	/**
	 * Parse message template
	 *
	 * @param array $placeholder Message placeholder
	 * @param string $template Message template
	 * @return string The message
	 */
	private function createMessage($placeholder, $template) {
		$this->chunkie->setPlaceholders($placeholder, '', '', 'message');
		$this->chunkie->setPlaceholder('lang', $this->language, 'message');
		$this->chunkie->setPlaceholder('options', $this->options, 'message');

		$this->chunkie->setTpl($template);
		$this->chunkie->prepareTemplate('', array(), 'message');

		return $this->chunkie->process('message');
	}

	/**
	 * Compare two arrays by name key
	 *
	 * @param array $a First array to compare
	 * @param array $b Second array to compare
	 * @return int The comparison result
	 */
	private function sortByPackageName($a, $b) {
		$a = strtolower($a['name']);
		$b = strtolower($b['name']);
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}

	/**
	 * Parse the DocBlock of a MODX installer template file
	 *
	 * @param string $folder Folder name of the installer template file
	 * @param string $filename File name of the installer template file
	 * @return array The parsed DocBlock result
	 */
	private function parseDocblock($folder, $filename) {
		$result = array();
		if (is_readable($folder . '/' . $filename)) {
			$tpl = @fopen($folder . '/' . $filename, 'r');
			if ($tpl) {
				$result['filename'] = $filename;
				$docblockStartFound = FALSE;
				$nameFound = FALSE;
				$descriptionFound = FALSE;
				$docblockEndFound = FALSE;

				while (!feof($tpl)) {
					$line = fgets($tpl);
					if (!$docblockStartFound) {
						// find docblock start
						if (strpos($line, '/**') !== FALSE) {
							$docblockStartFound = TRUE;
						}
						continue;
					} elseif (!$nameFound) {
						// find name
						$ma = NULL;
						if (preg_match('/^\s+\*\s+(.+)/', $line, $ma)) {
							$result['name'] = trim($ma[1]);
							$nameFound = !empty($result['name']);
						}
						continue;
					} elseif (!$descriptionFound) {
						// find description
						$ma = NULL;
						if (preg_match('/^\s+\*\s+(.+)/', $line, $ma)) {
							$result['description'] = trim($ma[1]);
							$descriptionFound = !empty($result['description']);
						}
						continue;
					} else {
						$ma = NULL;
						if (preg_match('/^\s+\*\s+\@([^\s]+)\s+(.+)/', $line, $ma)) {
							$param = trim($ma[1]);
							$val = trim($ma[2]);
							if (!empty($param) && !empty($val)) {
								if ($param == 'internal') {
									$ma = NULL;
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
							$docblockEndFound = TRUE;
							break;
						}
					}
				}
				@fclose($tpl);
			}
		}
		return $result;
	}

	/**
	 * Unzip a zip file
	 *
	 * @param string $zipsource The path to the zip file
	 * @param string $destination The path of the extract destination
	 * @return string The unzip message (empty on success)
	 */
	private function unzipFile($zipsource, $destination) {
		$zip = new ZipArchive;
		$res = $zip->open($zipsource);
		if ($res === TRUE) {
			if (!$zip->extractTo($destination)) {
				$result = $this->createMessage(array(
					'filename' => $this->language['temporary_files_singular']
						), '[+lang.file_extract_error:ucfirst+]');
			} else {
				$result = '';
			}
		} else {
			$result = $this->createMessage(array(
				'filename' => $this->language['temporary_files_singular']
					), '[+lang.file_extract_error_open:ucfirst+]');
		}
		$zip->close();
		return $result;
	}

	/**
	 * Recursice remove a folder
	 *
	 * @param string $folder The folder path
	 * @return void
	 */
	private function removeFolder($folder) {
		if (!is_dir($folder)) {
			return;
		}
		$it = new RecursiveDirectoryIterator($folder);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($files as $file) {
			if ($file->getFilename() === '.' || $file->getFilename() === '..') {
				continue;
			}
			if ($file->isDir()) {
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($folder);
	}

	/**
	 * Expanded curl_exec function
	 * See http://www.php.net/manual/en/function.curl-setopt.php#102121
	 *
	 * @param resource $ch
	 * @param int $maxredirect
	 * @return mixed
	 */
	private function curl_exec_follow($ch, &$maxredirect = NULL) {
		$mr = ($maxredirect === NULL) ? 5 : intval($maxredirect);
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
		} else {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
			if ($mr > 0) {
				$newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

				$rch = curl_init();
				curl_setopt($rch, CURLOPT_HEADER, TRUE);
				curl_setopt($rch, CURLOPT_NOBODY, TRUE);
				curl_setopt($rch, CURLOPT_FORBID_REUSE, FALSE);
				curl_setopt($rch, CURLOPT_RETURNTRANSFER, TRUE);
				do {
					curl_setopt($rch, CURLOPT_URL, $newurl);
					$header = curl_exec($rch);
					if (curl_errno($rch)) {
						$code = 0;
					} else {
						$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
						if ($code == 301 || $code == 302) {
							preg_match('/Location:(.*?)\n/', $header, $matches);
							$newurl = trim(array_pop($matches));
						} else {
							$code = 0;
						}
					}
				} while ($code && --$mr);
				curl_close($rch);
				if (!$mr) {
					if ($maxredirect === NULL) {
						die('test');
						trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
					} else {
						$maxredirect = 0;
					}
					return FALSE;
				}
				curl_setopt($ch, CURLOPT_URL, $newurl);
			}
		}
		return curl_exec($ch);
	}

}

?>
