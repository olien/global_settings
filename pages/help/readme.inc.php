<?php

$search = array('(CHANGELOG.md)', '(LICENSE.md)');
$replace = array('(index.php?page=global_settings&subpage=help&chapter=changelog)', '(index.php?page=global_settings&subpage=help&chapter=license)');

echo rex_global_settings_utils::getHtmlFromMDFile('README.md', $search, $replace);

