<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabClientProject;

require_once __DIR__ . "/../../../vendor/autoload.php";

use Gitlab\Model\Group;
use Gitlab\Model\Project;
use srag\Plugins\SrProjectHelper\Config\ConfigFormGUI;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabClientProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractGitlabCreatorTask
{

    /**
     * @inheritDoc
     */
    protected function getSteps(array $data) : array
    {
        /**
         * @var Group|null
         */
        $group = null;
        /**
         * @var Project|null
         */
        $project = null;
        /**
         * @var string
         */
        $temp_folder = null;
        /**
         * @var Project|null
         */
        $skin_project = null;
        /**
         * @var Project|null
         */
        $origins_project = null;

        return array_merge([
            function () use (&$data, &$group)/*: void*/ {
                $group = self::srProjectHelper()->gitlab()->createGroup($data["name"], self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_CLIENTS_GROUP_ID));
            },
            function () use (&$data, &$group, &$project)/*: void*/ {
                $project = self::srProjectHelper()
                    ->gitlab()
                    ->createProject("ILIAS", $group->id, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()
                    ->gitlab()
                    ->createBranch($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"], "master");
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()
                    ->gitlab()
                    ->setDefaultBranch($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$project)/*: void*/ {
                self::srProjectHelper()->gitlab()->removeBranch($project, "master");
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()->gitlab()->createBranch($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"],
                    self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()->gitlab()->createBranch($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["develop_name"],
                    self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()
                    ->gitlab()
                    ->protectDevelopBranch($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()
                    ->gitlab()
                    ->protectDevelopBranch($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()
                    ->gitlab()
                    ->protectDevelopBranch($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["develop_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                self::srProjectHelper()->gitlab()->setMaintainer($project, $data["maintainer_user"]);
            },
            function () use (&$project)/*: void*/ {
                self::srProjectHelper()->gitlab()->useDeployKey($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_DEPLOY_KEY_ID));
            },
            function () use (&$project)/*: void*/ {
                self::srProjectHelper()->gitlab()->setDisableEnableDeleteSourceBranchOptionByDefault($project);
            },
            function () use (&$data, &$temp_folder)/*: void*/ {
                $temp_folder = CLIENT_DATA_DIR . "/temp/" . uniqid($data["name"]);
            },
            function () use (&$temp_folder)/*: void*/ {
                self::srProjectHelper()->gitlab()->cleanTempFolder($temp_folder);
            },
            function () use (&$data, &$project, &$temp_folder)/*: void*/ {
                self::srProjectHelper()->gitlab()->cloneILIAS($temp_folder, $project, $data["ilias_version"]);
            },
            function () use (&$temp_folder)/*: void*/ {
                self::srProjectHelper()->gitlab()->notIgnoreCustomizingFolder($temp_folder);
            }
        ], array_map(function (string $plugin_name) use (&$temp_folder): callable {
            return function ()/*: void*/ use (&$temp_folder, &$plugin_name) {
                $plugin = self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_PLUGINS)[$plugin_name];

                if ($plugin) {
                    self::srProjectHelper()->gitlab()->addSubmodule($temp_folder, $plugin["repo_http"], $plugin["install_path"], $plugin["name"], "../../../Plugins");
                }
            };
        }, $data["plugins"]), $data["skin"] ? array_merge(self::srProjectHelper()->gitlab()->getStepsForNewPlugin("skin", function () use (&$group): int {
            return $group->id;
        }, $data["maintainer_user"], $skin_project, true), [
            function ()/*: void*/ use (&$temp_folder, &$skin_project) {
                self::srProjectHelper()->gitlab()->addSubmodule($temp_folder, $skin_project->http_url_to_repo, "Customizing/global/skin", "skin", "..");
            }
        ]) : [], $data["origins"] ? array_merge(self::srProjectHelper()->gitlab()->getStepsForNewPlugin("origins", function () use (&$group): int {
            return $group->id;
        }, $data["maintainer_user"], $origins_project, true), [
            function ()/*: void*/ use (&$temp_folder, &$origins_project) {
                self::srProjectHelper()->gitlab()->addSubmodule($temp_folder, $origins_project->http_url_to_repo, "Customizing/global/origins", "origins", "..");
            }
        ]) : [], [
            function () use (&$temp_folder)/*: void*/ {
                self::srProjectHelper()->gitlab()->push($temp_folder);
            },
            function () use (&$temp_folder)/*: void*/ {
                self::srProjectHelper()->gitlab()->cleanTempFolder($temp_folder);
            }
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function getOutput2() : string
    {
        return "";
    }
}
