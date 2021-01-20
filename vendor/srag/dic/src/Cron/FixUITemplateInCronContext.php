<?php

namespace srag\DIC\SrProjectHelper\Cron;

use ilTemplate;

/**
 * Trait FixUITemplateInCronContext
 *
 * @package srag\DIC\SrProjectHelper\Cron
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait FixUITemplateInCronContext
{

    /**
     *
     */
    protected static function fixUITemplateInCronContext()/*:void*/
    {
        // Fix missing tpl ui in cron context used in some core object constructor
        if (self::dic()->dic()->offsetExists("tpl")) {
            if (!isset($GLOBALS["tpl"])) {
                $GLOBALS["tpl"] = self::dic()->ui()->mainTemplate();
            }
        } else {
            if (!isset($GLOBALS["tpl"])) {
                $GLOBALS["tpl"] = new ilTemplate("tpl.main_menu.html", true, true, "Services/MainMenu");
            }

            self::dic()->dic()->offsetSet($GLOBALS["tpl"]);
        }
    }
}