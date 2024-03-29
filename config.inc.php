<?php
// init addon
$REX['ADDON']['name']['global_settings'] = 'Globale Einstellungen';
$REX['ADDON']['page']['global_settings'] = 'global_settings';
$REX['ADDON']['version']['global_settings'] = '1.0.1';
$REX['ADDON']['author']['global_settings'] = 'RexDude, Sysix-Coding, Polarpixel';
$REX['ADDON']['supportpage']['global_settings'] = 'forum.redaxo.org';
$REX['ADDON']['perm']['global_settings'] = 'global_settings[]';

// permissions
$REX['PERM'][] = 'global_settings[]';

// add lang file
if ($REX['REDAXO']) {
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/global_settings/lang/');
}

// includes
require($REX['INCLUDE_PATH'] . '/addons/global_settings/classes/class.rex_global_settings.inc.php');
require($REX['INCLUDE_PATH'] . '/addons/global_settings/classes/class.rex_global_settings_utils.inc.php');
require($REX['INCLUDE_PATH'] . '/addons/global_settings/classes/class.rex_global_settings_language.php');
require($REX['INCLUDE_PATH'] . '/addons/global_settings/classes/class.rex_global_settings_form.php');

// default settings (user settings are saved in data dir!)
$REX['ADDON']['global_settings']['settings'] = array(
);

// overwrite default settings with user settings
rex_global_settings_utils::includeSettingsFile();

// check the clang in our database table
rex_global_settings_language::checkLangsInDatabase();

// init global settings 
if (!$REX['SETUP']) {
	rex_register_extension('ADDONS_INCLUDED', 'rex_global_settings::init');
}

if ($REX['REDAXO']) {
	// add subpages
	$REX['ADDON']['global_settings']['SUBPAGES'] = array(
		array('', $I18N->msg('global_settings_settings'))
	);

    if (OOAddon::isAvailable('metainfo')) {
        require($REX['INCLUDE_PATH'] . '/addons/global_settings/classes/metainfo/global_settings_metainfo.php');

        // add global_settings to metaTables and metaPrefix
        rex_register_extension('PAGE_CHECKED', 'global_settings_metainfo::setProperty');

        // load page into navigation
		if (isset($REX['USER']) && $REX['USER']->isAdmin()) {
		    $REX['ADDON']['global_settings']['SUBPAGES'][] = array(
		        'metainfo',
		        $I18N->msg('global_settings_metainfo')
		    );
		}
    }

	// add subpages
	if (isset($REX['USER']) && $REX['USER']->isAdmin()) {
		$REX['ADDON']['global_settings']['SUBPAGES'][] = array(
			'help', $I18N->msg('global_settings_help')
		);
	}

	// add css/js files to page header
	if (rex_request('page') == 'global_settings') {
		rex_register_extension('PAGE_HEADER', 'rex_global_settings_utils::appendToPageHeader');
	}

    // handles if new clangs added or removed
    rex_register_extension('CLANG_ADDED', 'rex_global_settings_language::onClangAdd');
    rex_register_extension('CLANG_DELETED', 'rex_global_settings_language::onClangDelete');
}
?>
