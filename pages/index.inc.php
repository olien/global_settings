<?php
// post vars
$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');

// if no subpage specified, use this one
if ($subpage == '') {
	$subpage = 'settings';
}

// layout top
require($REX['INCLUDE_PATH'] . '/layout/top.php');

// title
rex_title($REX['ADDON']['name']['global_settings'] . ' <span style="font-size:14px; color:silver;">' . $REX['ADDON']['version']['global_settings'] . '</span>', $REX['ADDON']['global_settings']['SUBPAGES']);

// include subpage
include($REX['INCLUDE_PATH'] . '/addons/global_settings/pages/' . $subpage . '.inc.php');

// layout bottom
require($REX['INCLUDE_PATH'] . '/layout/bottom.php');
?>
