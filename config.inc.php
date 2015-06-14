<?php
// init addon
$REX['ADDON']['name']['global_settings'] = 'Global Settings';
$REX['ADDON']['page']['global_settings'] = 'global_settings';
$REX['ADDON']['version']['global_settings'] = '1.0.0';
$REX['ADDON']['author']['global_settings'] = 'RexDude, Sysix-Coding';
$REX['ADDON']['supportpage']['global_settings'] = 'forum.redaxo.org';
$REX['ADDON']['perm']['global_settings'] = 'global_settings[]';

// permissions
$REX['PERM'][] = 'global_settings[]';

// add lang file
if ($REX['REDAXO']) {
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/global_settings/lang/');
}

// includes
require($REX['INCLUDE_PATH'] . '/addons/global_settings/classes/class.rex_global_settings_utils.inc.php');

// default settings (user settings are saved in data dir!)
$REX['ADDON']['global_settings']['settings'] = array(
	'foo' => 'bar',
	'foo2' => true,
);

// overwrite default settings with user settings
rex_global_settings_utils::includeSettingsFile();

if ($REX['REDAXO']) {
	// add subpages
	$REX['ADDON']['global_settings']['SUBPAGES'] = array(
		array('', $I18N->msg('global_settings_start')),
		array('settings', $I18N->msg('global_settings_settings')),
		array('setup', $I18N->msg('global_settings_setup')),
		array('help', $I18N->msg('global_settings_help'))
	);

	// add css/js files to page header
	if (rex_request('page') == 'global_settings') {
		rex_register_extension('PAGE_HEADER', 'rex_global_settings_utils::appendToPageHeader');
	}
}
?>
