<?php

namespace srag\Plugins\SrProjectHelper;

use ilSrProjectHelperPlugin;
use srag\ActiveRecordConfig\SrProcessGraph\Config\Config;
use srag\ActiveRecordConfig\SrProjectHelper\Config\Repository as ConfigRepository;
use srag\ActiveRecordConfig\SrProjectHelper\Utils\ConfigTrait;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Access\Ilias;
use srag\Plugins\SrProjectHelper\Config\ConfigFormGUI;
use srag\Plugins\SrProjectHelper\Gitlab\Api;
use srag\Plugins\SrProjectHelper\Gitlab\Client;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrProjectHelper
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrProjectHelperTrait;
    use ConfigTrait {
        config as protected _config;
    }
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Repository constructor
     */
    private function __construct()
    {
        $this->config()->withTableName(ilSrProjectHelperPlugin::PLUGIN_ID . "_config")->withFields([
            ConfigFormGUI::KEY_GITLAB_ACCESS_TOKEN     => Config::TYPE_STRING,
            ConfigFormGUI::KEY_GITLAB_CLIENTS_GROUP_ID => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_GITLAB_DEPLOY_KEY_ID    => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_GITLAB_GROUPS           => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_GITLAB_ILIAS_PROJECT_ID => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS   => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_GITLAB_MEMBERS_GROUP_ID => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_GITLAB_PLUGINS          => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_GITLAB_PLUGINS_GROUP_ID => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_GITLAB_PROJECTS         => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_GITLAB_URL              => Config::TYPE_STRING,
            ConfigFormGUI::KEY_GITLAB_USERS            => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_ROLES                   => [Config::TYPE_JSON, []]
        ]);
    }


    /**
     * @inheritDoc
     */
    public function config() : ConfigRepository
    {
        return self::_config();
    }


    /**
     * @return bool
     */
    public function currentUserHasRole() : bool
    {
        $user_id = $this->ilias()->users()->getUserId();

        $user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
        $config_roles = $this->config()->getField(ConfigFormGUI::KEY_ROLES);

        foreach ($user_roles as $user_role) {
            if (in_array($user_role, $config_roles)) {
                return true;
            }
        }

        return false;
    }


    /**
     *
     */
    public function dropTables()/*:void*/
    {
        $this->config()->dropTables();
    }


    /**
     * @return Client
     */
    public function gitlab() : Client
    {
        return Api::getClient();
    }


    /**
     * @return Ilias
     */
    public function ilias() : Ilias
    {
        return Ilias::getInstance();
    }


    /**
     *
     */
    public function installTables()/*:void*/
    {
        $this->config()->installTables();
    }
}
