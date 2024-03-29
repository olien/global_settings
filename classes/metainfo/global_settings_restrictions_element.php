<?php

class global_settings_restrictons_element extends rex_form_select_element
{
    var $chkbox_element;

    // 1. Parameter nicht genutzt, muss aber hier stehen,
    // wg einheitlicher Konstrukturparameter
    function __construct($tag = '', /*rex_a62_tableExpander*/ &$table, $attributes = array())
    {
        global $I18N;
        global $REX;

        parent::rex_form_select_element('', $table, $attributes);

        $dummy = null;
        $this->chkbox_element = new rex_form_checkbox_element('', $dummy);
        $this->chkbox_element->setAttribute('name', 'enable_restrictions');
        $this->chkbox_element->setAttribute('id', 'enable_restrictions_chkbx');
        $this->chkbox_element->addOption($I18N->msg('minfo_field_label_no_restrictions'), '');

        if ($table->getPrefix() == 'art_' || $table->getPrefix() == 'cat_') {
            $restrictionsSelect = new rex_category_select(false, false, true, false);
        } elseif ($table->getPrefix() == 'med_') {
            $restrictionsSelect = new rex_mediacategory_select();
        } elseif($table->getPrefix() == global_settings_metainfo::PREFIX) {
            $restrictionsSelect = new rex_select();
            $restrictionsSelect->setSize(1);
            $restrictionsSelect->setDisabled(true);
        } else {
            trigger_error('Unexpected TablePrefix "' . $table->getPrefix() . '"!', E_USER_ERROR);
            exit();
        }

        $restrictionsSelect->setMultiple(true);
        $this->setSelect($restrictionsSelect);
        $this->setNotice($I18N->msg('ctrl'));
    }

    function get()
    {
        $slctDivId = $this->getAttribute('id') . '_div';

        // Wert aus dem select in die checkbox übernehmen
        $this->chkbox_element->setValue($this->getValue());

        $html = '';

        $html .= '
        <script type="text/javascript">
        <!--

        jQuery(function($) {

            $("#enable_restrictions_chkbx").click(function() {
                $("#' . $slctDivId . '").slideToggle("slow");
                if($(this).is(":checked"))
                {
                    $("option:selected", "#' . $slctDivId . '").each(function () {
                        $(this).removeAttr("selected");
                    });
                }
            });

            if($("#enable_restrictions_chkbx").is(":checked")) {
                $("#' . $slctDivId . '").hide();
            }
        });

        //-->
        </script>';

        $html .= $this->chkbox_element->get();

        $element = parent :: get();
        $html .= str_replace('class="rex-form-row"', 'id="' . $slctDivId . '" class="rex-form-row"', $element);

        return $html;
    }
}
