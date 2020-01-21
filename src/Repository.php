<?php

namespace srag\Plugins\SrProjectHelper;

use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Access\Ilias;
use srag\Plugins\SrProjectHelper\Config\ConfigFormGUI;
use srag\Plugins\SrProjectHelper\Config\Repository as ConfigRepository;
use srag\Plugins\SrProjectHelper\Gitlab\Api;
use srag\Plugins\SrProjectHelper\Gitlab\Client;
use srag\Plugins\SrProjectHelper\Job\Repository as JobsRepository;
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

    }


    /**
     * @return ConfigRepository
     */
    public function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }


    /**
     * @return bool
     */
    public function currentUserHasRole() : bool
    {
        $user_id = $this->ilias()->users()->getUserId();

        $user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
        $config_roles = $this->config()->getValue(ConfigFormGUI::KEY_ROLES);

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
        $this->jobs()->dropTables();
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
        $this->jobs()->installTables();
    }


    /**
     * @return JobsRepository
     */
    public function jobs() : JobsRepository
    {
        return JobsRepository::getInstance();
    }
}
