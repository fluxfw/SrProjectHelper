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
	const KEY_GITLAB_ACCESS_TOKEN = "gitlab_access_token";
	const KEY_GITLAB_ILIAS_REPO_ID = "gitlab_ilias_repo_id";
	const KEY_GITLAB_ILIAS_VERSIONS = "gitlab_ilias_versions";
	const KEY_GITLAB_PLUGINS = "gitlab_plugins";
	const KEY_GITLAB_PLUGINS_GROUP_ID = "gitlab_plugins_group_id";
	const KEY_GITLAB_URL = "gitlab_url";
	const KEY_ROLES = "roles";
	/**
	 * @var array
	 */
	protected static $fields = [
		self::KEY_GITLAB_ACCESS_TOKEN => self::TYPE_STRING,
		self::KEY_GITLAB_ILIAS_REPO_ID => self::TYPE_INTEGER,
		self::KEY_GITLAB_ILIAS_VERSIONS => [ self::TYPE_JSON, [], true ],
		self::KEY_GITLAB_PLUGINS => [ self::TYPE_JSON, [], true ],
		self::KEY_GITLAB_PLUGINS_GROUP_ID => self::TYPE_INTEGER,
		self::KEY_GITLAB_URL => self::TYPE_STRING,
		self::KEY_ROLES => [ self::TYPE_JSON, [] ]
	];
}
