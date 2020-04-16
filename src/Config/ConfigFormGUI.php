<?php

namespace srag\Plugins\SrProjectHelper\Config;

use ilFormSectionHeaderGUI;
use ilNumberInputGUI;
use ilPasswordInputGUI;
use ilSrProjectHelperPlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\SrProjectHelper\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\CustomInputGUIs\SrProjectHelper\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends PropertyFormGUI
{

    use SrProjectHelperTrait;
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    const KEY_GITHUB_ACCESS_TOKEN = "github_access_token";
    const KEY_GITHUB_ORGANISATION = "github_organisation";
    const KEY_GITHUB_USER = "github_user";
    const KEY_GITLAB_ACCESS_TOKEN = "gitlab_access_token";
    const KEY_GITLAB_CLIENTS_GROUP_ID = "gitlab_clients_group_id";
    const KEY_GITLAB_DEPLOY_KEY_ID = "gitlab_deploy_key_id";
    const KEY_GITLAB_GROUPS = "gitlab_groups";
    const KEY_GITLAB_ILIAS_PROJECT_ID = "gitlab_ilias_project_id";
    const KEY_GITLAB_ILIAS_VERSIONS = "gitlab_ilias_versions";
    const KEY_GITLAB_MEMBERS_GROUP_ID = "gitlab_members_group_id";
    const KEY_GITLAB_PLUGINS = "gitlab_plugins";
    const KEY_GITLAB_PLUGINS_GROUP_ID = "gitlab_plugins_group_id";
    const KEY_GITLAB_PROJECTS = "gitlab_projects";
    const KEY_GITLAB_URL = "gitlab_url";
    const KEY_GITLAB_USERS = "gitlab_users";
    const KEY_ROLES = "roles";
    const LANG_MODULE = ConfigCtrl::LANG_MODULE;


    /**
     * ConfigFormGUI constructor
     *
     * @param ConfigCtrl $parent
     */
    public function __construct(ConfigCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            default:
                return self::srProjectHelper()->config()->getValue($key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(ConfigCtrl::CMD_UPDATE_CONFIGURE, $this->txt("save"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "gitlab"                          => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            self::KEY_GITLAB_URL              => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            self::KEY_GITLAB_ACCESS_TOKEN     => [
                self::PROPERTY_CLASS    => ilPasswordInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                "setRetype"             => false
            ],
            self::KEY_GITLAB_CLIENTS_GROUP_ID => [
                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            self::KEY_GITLAB_DEPLOY_KEY_ID    => [
                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            self::KEY_GITLAB_ILIAS_PROJECT_ID => [
                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            self::KEY_GITLAB_MEMBERS_GROUP_ID => [
                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            self::KEY_GITLAB_PLUGINS_GROUP_ID => [
                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],

            "github"                      => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            self::KEY_GITHUB_ORGANISATION => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            self::KEY_GITHUB_ACCESS_TOKEN => [
                self::PROPERTY_CLASS    => ilPasswordInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                "setRetype"             => false
            ],
            self::KEY_GITHUB_USER         => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],

            "others"        => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            self::KEY_ROLES => [
                self::PROPERTY_CLASS    => MultiSelectSearchNewInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_OPTIONS  => self::srProjectHelper()->ilias()->roles()->getAllRoles()
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("configuration"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            default:
                self::srProjectHelper()->config()->setValue($key, $value);
                break;
        }
    }
}
