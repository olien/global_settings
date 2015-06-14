<?php
class rex_global_settings_utils {
	public static function appendToPageHeader($params) {
		global $REX;

		$insert = '<!-- BEGIN global_settings -->' . PHP_EOL;
		$insert .= '<link rel="stylesheet" type="text/css" href="../' . self::getMediaAddonDir() . '/global_settings/backend.css" />' . PHP_EOL;
		$insert .= '<!-- END global_settings -->';
	
		return $params['subject'] . PHP_EOL . $insert;
	}

	public static function getMediaAddonDir() {
		global $REX;

		// check for media addon dir var introduced in REX 4.5
		if (isset($REX['MEDIA_ADDON_DIR'])) {
			return $REX['MEDIA_ADDON_DIR'];
		} else {
			return 'files/addons';
		}
	}

	public static function getSettingsFile() {
		global $REX;

		$dataDir = $REX['INCLUDE_PATH'] . '/data/addons/global_settings/';

		return $dataDir . 'settings.inc.php';
	}

	public static function includeSettingsFile() {
		global $REX; // important for include

		$settingsFile = self::getSettingsFile();

		if (!file_exists($settingsFile)) {
			self::updateSettingsFile(false);
		}

		require_once($settingsFile);
	}

	public static function updateSettingsFile($showSuccessMsg = true) {
		global $REX, $I18N;

		$settingsFile = self::getSettingsFile();
		$msg = self::checkDirForFile($settingsFile);

		if ($msg != '') {
			if ($REX['REDAXO']) {
				echo rex_warning($msg);			
			}
		} else {
			if (!file_exists($settingsFile)) {
				self::createDynFile($settingsFile);
			}

			$content = "<?php\n\n";
		
			foreach ((array) $REX['ADDON']['global_settings']['settings'] as $key => $value) {
				$content .= "\$REX['ADDON']['global_settings']['settings']['$key'] = " . var_export($value, true) . ";\n";
			}

			if (rex_put_file_contents($settingsFile, $content)) {
				if ($REX['REDAXO'] && $showSuccessMsg) {
					echo rex_info($I18N->msg('global_settings_config_ok'));
				}
			} else {
				if ($REX['REDAXO']) {
					echo rex_warning($I18N->msg('global_settings_config_error'));
				}
			}
		}
	}

	public static function replaceSettings($settings) {
		global $REX;

		// type conversion
		foreach ($REX['ADDON']['global_settings']['settings'] as $key => $value) {
			if (isset($settings[$key])) {
				$settings[$key] = self::convertVarType($value, $settings[$key]);
			}
		}

		$REX['ADDON']['global_settings']['settings'] = array_merge((array) $REX['ADDON']['global_settings']['settings'], $settings);
	}

	public static function createDynFile($file) {
		$fileHandle = fopen($file, 'w');

		fwrite($fileHandle, "<?php\r\n");
		fwrite($fileHandle, "// --- DYN\r\n");
		fwrite($fileHandle, "// --- /DYN\r\n");

		fclose($fileHandle);
	}

	public static function checkDir($dir) {
		global $REX, $I18N;

		$path = $dir;

		if (!@is_dir($path)) {
			@mkdir($path, $REX['DIRPERM'], true);
		}

		if (!@is_dir($path)) {
			if ($REX['REDAXO']) {
				return $I18N->msg('global_settings_install_make_dir', $dir);
			}
		} elseif (!@is_writable($path . '/.')) {
			if ($REX['REDAXO']) {
				return $I18N->msg('global_settings_install_perm_dir', $dir);
			}
		}
		
		return '';
	}

	public static function checkDirForFile($fileWithPath) {
		$pathInfo = pathinfo($fileWithPath);

		return self::checkDir($pathInfo['dirname']);
	}

	public static function convertVarType($originalValue, $newValue) {
		$arrayDelimiter = ',';

		switch (gettype($originalValue)) {
			case 'string':
				return trim($newValue);
				break;
			case 'integer':
				return intval($newValue);
				break;
			case 'boolean':
				return (bool) $newValue;
				break;
			case 'array':
				if ($newValue == '') {
					return array();
				} else {
					return explode($arrayDelimiter, $newValue);
				}
				break;
			default:
				return $newValue;
				
		}
	}

	public static function getHtmlFromMDFile($mdFile, $search = array(), $replace = array(), $setBreaksEnabled = true) {
		global $REX;

		$curLocale = strtolower($REX['LANG']);

		if ($curLocale == 'de_de') {
			$file = $REX['INCLUDE_PATH'] . '/addons/global_settings/' . $mdFile;
		} else {
			$file = $REX['INCLUDE_PATH'] . '/addons/global_settings/lang/' . $curLocale . '/' . $mdFile;
		}

		if (file_exists($file)) {
			$md = file_get_contents($file);
			$md = str_replace($search, $replace, $md);
			$md = self::makeHeadlinePretty($md);

			if (method_exists('Parsedown', 'set_breaks_enabled')) {
				$out = Parsedown::instance()->set_breaks_enabled($setBreaksEnabled)->parse($md);
			} elseif (method_exists('Parsedown', 'setBreaksEnabled')) {
				$out = Parsedown::instance()->setBreaksEnabled($setBreaksEnabled)->parse($md);
			} else {
				$out = Parsedown::instance()->parse($md);
			}

			return $out;
		} else {
			return '[translate:' . $file . ']';
		}
	}

	public static function makeHeadlinePretty($md) {
		return str_replace('Global Settings - ', '', $md);
	}
}

