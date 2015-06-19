<?php
$error = '';

$sql = new rex_sql();
$sql->setQuery('
CREATE TABLE `' . $REX['TABLE_PREFIX'] . 'global_settings` (
    `clang` int(11) NOT NULL,
    PRIMARY KEY (`clang`)
)');

if ($error == '') {
	$REX['ADDON']['install']['global_settings'] = true;
} else {
	$REX['ADDON']['installmsg']['global_settings'] = $error;
}
