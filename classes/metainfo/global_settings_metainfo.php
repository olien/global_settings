<?php

/**
 * Class global_settings_metainfo
 * Für den leichteren Gebrauch von Metainfo Funktionen/Einstellungen
 * wird sowohl für die install.inc.php, uninstall.inc.php und update.inc.php benutzt
 */
class global_settings_metainfo
{

    const TABLE = 'global_settings';
    const PREFIX = 'glob_';
    /**
     * set the property for the metainfo.
     * added Entrys in metaTables and prefixes
     */
    public static function setProperty()
    {
        global $REX;

        $metaTables = OOAddon::getProperty('metainfo', 'metaTables', array());
        $metaTables[self::PREFIX] = $REX['TABLE_PREFIX'] . self::TABLE;
        OOAddon::setProperty('metainfo', 'metaTables', $metaTables);

        $prefixes = OOAddon::getProperty('metainfo', 'prefixes', array());
        if (!in_array(self::PREFIX, $prefixes)) {
            $prefixes[] = self::PREFIX;
        }
        OOAddon::setProperty('metainfo', 'prefixes', $prefixes);
    }

    /**
     * added the metafields
     * @return string
     */
    public static function addFields()
    {

        return self::checkErrorMessage(
            /* a62_add_field('translate:', 'column', 4, '', 2, '') */
        );
    }

    /**
     * @param mixed $args
     * @return string
     */
    public static function checkErrorMessage($args = null)
    {
        $args = (is_array($args)) ? $args : func_get_args();

        $returnString = '';
        foreach ($args as $toCheck) {

            if (is_string($toCheck)) {
                $returnString .= $toCheck . '<br />';
            }

        }

        if($returnString) {
            $returnString = 'Metainfo Error: <br />' . $returnString;
        }

        return $returnString;
    }

    /**
     * delete the metafields
     */
    public static function delFields()
    {
        global $REX;

        $sql = new rex_sql();
        $sql->setQuery('SELECT `name` FROM ' . $REX['TABLE_PREFIX'] . '62_params WHERE `name` LIKE "' . self::PREFIX . '%"');

        $delFields = array();

        for($i = 1; $i <= $sql->getRows(); $i++) {
            $delFields[] = a62_delete_field($sql->getValue('name'));
            $sql->next();
        }

        return self::checkErrorMessage($delFields);
    }

}