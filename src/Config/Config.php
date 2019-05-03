<?php

namespace srag\Plugins\SrGitlabHelper\Config;

use ilSrGitlabHelperPlugin;
use srag\ActiveRecordConfig\SrGitlabHelper\ActiveRecordConfig;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class Config
 *
 * @package srag\Plugins\SrGitlabHelper\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig {

	use SrGitlabHelperTrait;
	const TABLE_NAME = "srgitlabhelper_config";
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	/**
	 * @var array
	 */
	protected static $fields = [];
}
