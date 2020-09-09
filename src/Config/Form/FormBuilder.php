<?php

namespace srag\Plugins\SrProjectHelper\Config\Form;

use ilSrProjectHelperPlugin;
use srag\CustomInputGUIs\SrProjectHelper\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\SrProjectHelper\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\SrProjectHelper\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\Plugins\SrProjectHelper\Config\ConfigCtrl;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class FormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Config\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use SrProjectHelperTrait;

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
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;


    /**
     * @inheritDoc
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
    protected function getButtons() : array
    {
        $buttons = [
            ConfigCtrl::CMD_UPDATE_CONFIGURE => self::plugin()->translate("save", ConfigCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "gitlab" => [
                self::KEY_GITLAB_URL              => self::srProjectHelper()->config()->getValue(self::KEY_GITLAB_URL),
                self::KEY_GITLAB_ACCESS_TOKEN     => self::srProjectHelper()->config()->getValue(self::KEY_GITLAB_ACCESS_TOKEN),
                self::KEY_GITLAB_CLIENTS_GROUP_ID => self::srProjectHelper()->config()->getValue(self::KEY_GITLAB_CLIENTS_GROUP_ID),
                self::KEY_GITLAB_DEPLOY_KEY_ID    => self::srProjectHelper()->config()->getValue(self::KEY_GITLAB_DEPLOY_KEY_ID),
                self::KEY_GITLAB_ILIAS_PROJECT_ID => self::srProjectHelper()->config()->getValue(self::KEY_GITLAB_ILIAS_PROJECT_ID),
                self::KEY_GITLAB_MEMBERS_GROUP_ID => self::srProjectHelper()->config()->getValue(self::KEY_GITLAB_MEMBERS_GROUP_ID),
                self::KEY_GITLAB_PLUGINS_GROUP_ID => self::srProjectHelper()->config()->getValue(self::KEY_GITLAB_PLUGINS_GROUP_ID)
            ],
            "github" => [
                self::KEY_GITHUB_ORGANISATION => self::srProjectHelper()->config()->getValue(self::KEY_GITHUB_ORGANISATION),
                self::KEY_GITHUB_ACCESS_TOKEN => self::srProjectHelper()->config()->getValue(self::KEY_GITHUB_ACCESS_TOKEN),
                self::KEY_GITHUB_USER         => self::srProjectHelper()->config()->getValue(self::KEY_GITHUB_USER)
            ],
            "others" => [
                self::KEY_ROLES => self::srProjectHelper()->config()->getValue(self::KEY_ROLES)
            ]
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "gitlab" => self::dic()->ui()->factory()->input()->field()->section([
                self::KEY_GITLAB_URL              => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_GITLAB_URL, ConfigCtrl::LANG_MODULE))->withRequired(true),
                self::KEY_GITLAB_ACCESS_TOKEN     => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->password(self::plugin()->translate(self::KEY_GITLAB_ACCESS_TOKEN, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true),
                self::KEY_GITLAB_CLIENTS_GROUP_ID => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->numeric(self::plugin()->translate(self::KEY_GITLAB_CLIENTS_GROUP_ID, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true),
                self::KEY_GITLAB_DEPLOY_KEY_ID    => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->numeric(self::plugin()->translate(self::KEY_GITLAB_DEPLOY_KEY_ID, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true),
                self::KEY_GITLAB_ILIAS_PROJECT_ID => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->numeric(self::plugin()->translate(self::KEY_GITLAB_ILIAS_PROJECT_ID, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true),
                self::KEY_GITLAB_MEMBERS_GROUP_ID => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->numeric(self::plugin()->translate(self::KEY_GITLAB_MEMBERS_GROUP_ID, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true),
                self::KEY_GITLAB_PLUGINS_GROUP_ID => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->numeric(self::plugin()->translate(self::KEY_GITLAB_PLUGINS_GROUP_ID, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true)
            ], self::plugin()->translate("gitlab", ConfigCtrl::LANG_MODULE)),
            "github" => self::dic()->ui()->factory()->input()->field()->section([
                self::KEY_GITHUB_ORGANISATION => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->text(self::plugin()->translate(self::KEY_GITHUB_ORGANISATION, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true),
                self::KEY_GITHUB_ACCESS_TOKEN => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->password(self::plugin()->translate(self::KEY_GITHUB_ACCESS_TOKEN, ConfigCtrl::LANG_MODULE))
                    ->withRequired(true),
                self::KEY_GITHUB_USER         => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_GITHUB_USER, ConfigCtrl::LANG_MODULE))->withRequired(true)
            ], self::plugin()->translate("github", ConfigCtrl::LANG_MODULE)),
            "others" => self::dic()->ui()->factory()->input()->field()->section([
                self::KEY_ROLES => ($roles = (new InputGUIWrapperUIInputComponent(new MultiSelectSearchNewInputGUI(self::plugin()
                    ->translate(self::KEY_ROLES, ConfigCtrl::LANG_MODULE))))->withByline(self::plugin()
                    ->translate(self::KEY_ROLES . "_info", ConfigCtrl::LANG_MODULE))->withRequired(true))
            ], self::plugin()->translate("others", ConfigCtrl::LANG_MODULE))
        ];
        $roles->getInput()->setOptions(self::srProjectHelper()->ilias()->roles()->getAllRoles());

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("configuration", ConfigCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        self::srProjectHelper()->config()->setValue(self::KEY_GITLAB_URL, strval($data["gitlab"][self::KEY_GITLAB_URL]));
        self::srProjectHelper()->config()->setValue(self::KEY_GITLAB_ACCESS_TOKEN, $data["gitlab"][self::KEY_GITLAB_ACCESS_TOKEN]->toString());
        self::srProjectHelper()->config()->setValue(self::KEY_GITLAB_CLIENTS_GROUP_ID, intval($data["gitlab"][self::KEY_GITLAB_CLIENTS_GROUP_ID]));
        self::srProjectHelper()->config()->setValue(self::KEY_GITLAB_DEPLOY_KEY_ID, intval($data["gitlab"][self::KEY_GITLAB_DEPLOY_KEY_ID]));
        self::srProjectHelper()->config()->setValue(self::KEY_GITLAB_ILIAS_PROJECT_ID, intval($data["gitlab"][self::KEY_GITLAB_ILIAS_PROJECT_ID]));
        self::srProjectHelper()->config()->setValue(self::KEY_GITLAB_MEMBERS_GROUP_ID, intval($data["gitlab"][self::KEY_GITLAB_MEMBERS_GROUP_ID]));
        self::srProjectHelper()->config()->setValue(self::KEY_GITLAB_PLUGINS_GROUP_ID, intval($data["gitlab"][self::KEY_GITLAB_PLUGINS_GROUP_ID]));
        self::srProjectHelper()->config()->setValue(self::KEY_GITHUB_ORGANISATION, strval($data["github"][self::KEY_GITHUB_ORGANISATION]));
        self::srProjectHelper()->config()->setValue(self::KEY_GITHUB_ACCESS_TOKEN, $data["github"][self::KEY_GITHUB_ACCESS_TOKEN]->toString());
        self::srProjectHelper()->config()->setValue(self::KEY_GITHUB_USER, strval($data["github"][self::KEY_GITHUB_USER]));
        self::srProjectHelper()->config()->setValue(self::KEY_ROLES, (array) $data["others"][self::KEY_ROLES]);
    }
}
