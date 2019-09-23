<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\SrProjectHelper\ActiveRecordConfigGUI;
use srag\Plugins\SrProjectHelper\Config\ConfigFormGUI;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class ilSrProjectHelperConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrProjectHelperConfigGUI extends ActiveRecordConfigGUI
{

    use SrProjectHelperTrait;
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var array
     */
    protected static $tabs = [self::TAB_CONFIGURATION => ConfigFormGUI::class];
}
