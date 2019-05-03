<?php

namespace srag\Plugins\SrGitlabHelper\Config;

use ilNumberInputGUI;
use ilSrGitlabHelperPlugin;
use srag\ActiveRecordConfig\SrGitlabHelper\ActiveRecordConfigFormGUI;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrGitlabHelper\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	const CONFIG_CLASS_NAME = Config::class;


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [];
	}
}
