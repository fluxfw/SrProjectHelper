<?php

namespace srag\Plugins\SrProjectHelper\Config;

use ilSrProjectHelperPlugin;
use srag\ActiveRecordConfig\SrProcessGraph\Config\Config;
use srag\ActiveRecordConfig\SrProjectHelper\Config\AbstractFactory;
use srag\ActiveRecordConfig\SrProjectHelper\Config\AbstractRepository;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrProjectHelper\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository extends AbstractRepository
{

    use SrProjectHelperTrait;
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
    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     *
     * @return Factory
     */
    public function factory() : AbstractFactory
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return ilSrProjectHelperPlugin::PLUGIN_ID . "_config";
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        return [
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
        ];
    }
}
