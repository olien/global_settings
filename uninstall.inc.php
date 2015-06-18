<?php

$REX['ADDON']['install']['global_settings'] = false;

require($REX['INCLUDE_PATH'] . '/addons/global_settings/classes/metainfo/global_settings_metainfo.php');

global_settings_metainfo::setProperty();
// delete the metafields
if($error = global_settings_metainfo::delFields()) {
    $REX['ADDON']['install']['global_settings'] = true;
    $REX['ADDON']['installmsg']['global_settings'] .= $error;
} else {
    //delete the global settings table
    $sql = new rex_sql();
    $sql->setQuery('DROP TABLE `' . $REX['TABLE_PREFIX'] . 'global_settings`');
}
