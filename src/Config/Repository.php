<?php

namespace srag\Plugins\SrProjectHelper\Config;

use ilSrProjectHelperPlugin;
use srag\ActiveRecordConfig\SrProjectHelper\Config\AbstractFactory;
use srag\ActiveRecordConfig\SrProjectHelper\Config\AbstractRepository;
use srag\ActiveRecordConfig\SrProjectHelper\Config\Config;
use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrProjectHelper\Config
 */
final class Repository extends AbstractRepository
{

    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
     */
    protected function __construct()
    {
        parent::__construct();
    }


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
    protected function getFields() : array
    {
        return [
            FormBuilder::KEY_GITHUB_ACCESS_TOKEN     => Config::TYPE_STRING,
            FormBuilder::KEY_GITHUB_ORGANISATION     => Config::TYPE_STRING,
            FormBuilder::KEY_GITHUB_USER             => Config::TYPE_STRING,
            FormBuilder::KEY_GITLAB_ACCESS_TOKEN     => Config::TYPE_STRING,
            FormBuilder::KEY_GITLAB_CLIENTS_GROUP_ID => Config::TYPE_INTEGER,
            FormBuilder::KEY_GITLAB_DEPLOY_KEY_ID    => Config::TYPE_INTEGER,
            FormBuilder::KEY_GITLAB_GROUPS           => [Config::TYPE_JSON, [], true],
            FormBuilder::KEY_GITLAB_ILIAS_PROJECT_ID => Config::TYPE_INTEGER,
            FormBuilder::KEY_GITLAB_ILIAS_VERSIONS   => [Config::TYPE_JSON, [], true],
            FormBuilder::KEY_GITLAB_MEMBERS_GROUP_ID => Config::TYPE_INTEGER,
            FormBuilder::KEY_GITLAB_PLUGINS          => [Config::TYPE_JSON, [], true],
            FormBuilder::KEY_GITLAB_PLUGINS_GROUP_ID => Config::TYPE_INTEGER,
            FormBuilder::KEY_GITLAB_PROJECTS         => [Config::TYPE_JSON, [], true],
            FormBuilder::KEY_GITLAB_URL              => Config::TYPE_STRING,
            FormBuilder::KEY_GITLAB_USERS            => [Config::TYPE_JSON, [], true],
            FormBuilder::KEY_ROLES                   => [Config::TYPE_JSON, []]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return ilSrProjectHelperPlugin::PLUGIN_ID . "_config";
    }
}
