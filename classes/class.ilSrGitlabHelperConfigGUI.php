<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\SrGitlabHelper\ActiveRecordConfigGUI;
use srag\Plugins\SrGitlabHelper\Config\ConfigFormGUI;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class ilSrGitlabHelperConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrGitlabHelperConfigGUI extends ActiveRecordConfigGUI {

	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	/**
	 * @var array
	 */
	protected static $tabs = [ self::TAB_CONFIGURATION => ConfigFormGUI::class ];
}
