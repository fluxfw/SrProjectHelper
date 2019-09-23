<?php

namespace srag\Plugins\SrProjectHelper\Config;

use ilSrProjectHelperPlugin;
use srag\ActiveRecordConfig\SrProjectHelper\ActiveRecordConfig;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Config
 *
 * @package srag\Plugins\SrProjectHelper\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig
{

    use SrProjectHelperTrait;
    const TABLE_NAME = "srprojecthelper_config";
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    const KEY_GITLAB_ACCESS_TOKEN = "gitlab_access_token";
    const KEY_GITLAB_CLIENTS_GROUP_ID = "gitlab_clients_group_id";
    const KEY_GITLAB_DEPLOY_KEY_ID = "gitlab_deploy_key_id";
    const KEY_GITLAB_GROUPS = "gitlab_groups";
    const KEY_GITLAB_ILIAS_PROJECT_ID = "gitlab_ilias_project_id";
    const KEY_GITLAB_ILIAS_VERSIONS = "gitlab_ilias_versions";
    const KEY_GITLAB_MEMBERS_GROUP_ID = "gitlab_members_group_id";
    const KEY_GITLAB_PLUGINS = "gitlab_plugins";
    const KEY_GITLAB_PLUGINS_GROUP_ID = "gitlab_plugins_group_id";
    const KEY_GITLAB_URL = "gitlab_url";
    const KEY_GITLAB_USERS = "gitlab_users";
    const KEY_ROLES = "roles";
    /**
     * @var array
     */
    protected static $fields
        = [
            self::KEY_GITLAB_ACCESS_TOKEN     => self::TYPE_STRING,
            self::KEY_GITLAB_CLIENTS_GROUP_ID => self::TYPE_INTEGER,
            self::KEY_GITLAB_DEPLOY_KEY_ID    => self::TYPE_INTEGER,
            self::KEY_GITLAB_GROUPS           => [self::TYPE_JSON, [], true],
            self::KEY_GITLAB_ILIAS_PROJECT_ID => self::TYPE_INTEGER,
            self::KEY_GITLAB_ILIAS_VERSIONS   => [self::TYPE_JSON, [], true],
            self::KEY_GITLAB_MEMBERS_GROUP_ID => self::TYPE_INTEGER,
            self::KEY_GITLAB_PLUGINS          => [self::TYPE_JSON, [], true],
            self::KEY_GITLAB_PLUGINS_GROUP_ID => self::TYPE_INTEGER,
            self::KEY_GITLAB_URL              => self::TYPE_STRING,
            self::KEY_GITLAB_USERS            => [self::TYPE_JSON, [], true],
            self::KEY_ROLES                   => [self::TYPE_JSON, []]
        ];
}
