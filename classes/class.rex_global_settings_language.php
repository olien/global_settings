<?php

class rex_global_settings_language
{
    /**
     * check if all languages are in the databse
     * @return bool
     */
    public static function checkLangsInDatabase()
    {
        global $REX;

        try {

            foreach ($REX['CLANG'] as $id => $name) {
                $sql = new rex_sql();
                $sql->setQuery('SELECT `clang` FROM ' . $REX['TABLE_PREFIX'] . 'global_settings WHERE `clang` = ' . $id);

                switch ($sql->getRows()) {
                    case 0:
                        $sql = new rex_sql();
                        $sql->setTable($REX['TABLE_PREFIX'] . 'global_settings');
                        $sql->setValue('clang', $id);
                        $sql->insert();
                        break;
                    case 1:
                        // clang is in the database
                        break;
                    default:
                        throw new Exception('global_settings: clang #' . $id . ' is ' . $sql->getRows() . 'x in the database, only once allowed.');

                }
            }

            return true;

        } catch(Exception $e) {
            echo rex_warning($e->getMessage());

            return false;
        }
    }

    public static function buildLanguageNavigation($curClang, $urlParams)
    {
        global $REX;
        global $I18N;
        reset($REX['CLANG']);
        $num_clang = count($REX['CLANG']);
        if ($num_clang > 1) {
            echo '
<div id="rex-clang" class="rex-toolbar">
    <div class="rex-toolbar-content">
        <ul>
            <li>' . $I18N->msg('languages') . ' : </li>';
            $stop = false;
            $i = 1;
            foreach ($REX['CLANG'] as $key => $val) {
                if ($i == 1) {
                    echo '<li class="rex-navi-first rex-navi-clang-' . $key . '">';
                } else {
                    echo '<li class="rex-navi-clang-' . $key . '">';
                }
                $val = rex_translate($val);
                if (!$REX['USER']->isAdmin() && !$REX['USER']->hasPerm('clang[all]') && !$REX['USER']->hasPerm('clang[' . $key . ']')) {
                    echo '<span class="rex-strike">' . $val . '</span>';
                    if ($curClang == $key) {
                        $stop = true;
                    }
                } else {
                    $class = '';
                    if ($key == $curClang) {
                        $class = ' class="rex-active"';
                    }
                    echo '<a' . $class . ' href="index.php?page=' . $REX['PAGE'] . '&amp;clang=' . $key . $urlParams . '"' . rex_tabindex() . '>' . $val . '</a>';
                }
                echo '</li>';
                $i++;
            }
            echo '
        </ul>
    </div>
</div>';
            if ($stop) {
                echo rex_warning('You have no permission to this area');
                require $REX['INCLUDE_PATH'] . '/layout/bottom.php';
                exit;
            }
        }
    }

    public static function onClangAdd($params)
    {
        global $REX;

        self::copyLanguageData($REX['START_CLANG_ID'], $params['id']);
    }

    public static function onClangDelete($params)
    {
        self::removeLanguageData($params['id']);
    }

    /**
     * @param $from
     * @param $to
     * @return bool
     */
    public static function copyLanguageData($from, $to)
    {
        global $REX;
        $sql = new rex_sql();

        $sql->setQuery('SELECT * FROM `' . $REX['TABLE_PREFIX'] . 'global_settings` WHERE `clang` = ' . (int)$from);
        for ($i = 1; $i <= $sql->getRows(); $i++) {

            $save = new rex_sql();
            $save->setTable($REX['TABLE_PREFIX'] . 'global_settings');
            $save->setValues($sql->getRow());

            $save->setValue('clang', $to);

            return $save->insert();
        }

        return false;
    }

    /**
     * @param $clang
     * @return bool
     */
    public static function removeLanguageData($clang)
    {
        global $REX;

        $sql = new rex_sql();
        $sql->setTable($REX['TABLE_PREFIX'] . 'global_settings');
        $sql->setWhere('clang = ' . (int)$clang);

        return $sql->delete();
    }
}
