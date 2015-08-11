<?php
class rex_global_settings {
	protected static $globalValues;
	protected static $curClang;
	protected static $defaultClang;

	public static function init() {	
		global $REX;

		$sql = new rex_sql();
		$sql->setQuery('SELECT * FROM '. $REX['TABLE_PREFIX'] . 'global_settings');

		self::$globalValues = $sql;
		self::$curClang = $REX['CUR_CLANG'];
		self::$defaultClang = $REX['START_CLANG_ID'];
	}

	public static function getDefaultValue($field) {
		return self::getValue($field, self::$defaultClang);
	}

	public static function getValue($field, $clang = -1) {
		if ($clang == -1) {
			$clang = self::$curClang;
		}

		self::$globalValues->reset();

		for ($i = 0; $i < self::$globalValues->getRows(); $i++) {
			if (self::$globalValues->getValue('clang') == $clang) {
				return self::$globalValues->getValue($field);
			}

			self::$globalValues->next();
		}

		return '';
	}
}

