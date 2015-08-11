<?php

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');
$msg = rex_request('_msg', 'string');
$clang = rex_request('clang', 'int', $REX['START_CLANG_ID']);
$urlParams = '&amp;subpage=' . $subpage;

if ($func) {
    $urlParams .= '&amp;func=' . $func;
}

// msg
if ($msg != '') {
	echo rex_info($msg);
}

//output languages
rex_global_settings_language::buildLanguageNavigation($clang, $urlParams);
?>

<div class="rex-addon-output">

    <?php

    $form = new rex_global_settings_form(
        $REX['TABLE_PREFIX'] . 'global_settings',
        $I18N->msg('global_settings_settings'),
        'clang = ' . $clang
    );
    $form->divId = 'global_settings-addon-editmode';

    if(OOAddon::isAvailable('metainfo')) {
        $form->addRawField($form->getMetainfoExtension());
    }

    $form->addHiddenField('clang', (int)$clang);
    $form->addParam('clang', (int) $clang);

    $form->show();

    ?>

</div>



