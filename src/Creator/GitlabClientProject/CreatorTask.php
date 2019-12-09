<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabClientProject;

// BackgroundTasks Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use Gitlab\Model\Group;
use Gitlab\Model\Project;
use srag\Plugins\SrProjectHelper\Config\Config;
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
     * @inheritdoc
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
                $group = $this->createGroup($data["name"], Config::getField(Config::KEY_GITLAB_CLIENTS_GROUP_ID));
            },
            function () use (&$data, &$group, &$project)/*: void*/ {
                $project = $this->createProject("ILIAS", $group->id, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->createBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"], "master");
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->setDefaultBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$project)/*: void*/ {
                $this->removeBranch($project, "master");
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->createBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"],
                    Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->createBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["develop_name"],
                    Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->protectDevelopBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->protectDevelopBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->protectDevelopBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["develop_name"]);
            },
            function () use (&$data, &$project)/*: void*/ {
                $this->setMaintainer($project, $data["maintainer_user"]);
            },
            function () use (&$project)/*: void*/ {
                $this->useDeployKey($project, Config::getField(Config::KEY_GITLAB_DEPLOY_KEY_ID));
            },
            function () use (&$data, &$temp_folder)/*: void*/ {
                $temp_folder = CLIENT_DATA_DIR . "/temp/" . uniqid($data["name"]);
            },
            function () use (&$temp_folder)/*: void*/ {
                $this->cleanTempFolder($temp_folder);
            },
            function () use (&$data, &$project, &$temp_folder)/*: void*/ {
                $this->cloneILIAS($temp_folder, $project, $data["ilias_version"]);
            },
            function () use (&$temp_folder)/*: void*/ {
                $this->notIgnoreCustomizingFolder($temp_folder);
            }
        ], array_map(function (string $plugin_name) use (&$temp_folder): callable {
            return function ()/*: void*/ use (&$temp_folder, &$plugin_name) {
                $plugin = Config::getField(Config::KEY_GITLAB_PLUGINS)[$plugin_name];

                if ($plugin) {
                    $this->addSubmodule($temp_folder, $plugin["repo_http"], $plugin["install_path"], $plugin["name"], "../../../Plugins");
                }
            };
        }, $data["plugins"]), $data["skin"] ? array_merge($this->getStepsForNewPlugin("skin", function () use (&$group): int {
            return $group->id;
        }, $data["maintainer_user"], $skin_project, true), [
            function ()/*: void*/ use (&$temp_folder, &$skin_project) {
                $this->addSubmodule($temp_folder, $skin_project->http_url_to_repo, "Customizing/global/skin", "skin", "..");
            }
        ]) : [], $data["origins"] ? array_merge($this->getStepsForNewPlugin("origins", function () use (&$group): int {
            return $group->id;
        }, $data["maintainer_user"], $origins_project, true), [
            function ()/*: void*/ use (&$temp_folder, &$origins_project) {
                $this->addSubmodule($temp_folder, $origins_project->http_url_to_repo, "Customizing/global/origins", "origins", "..");
            }
        ]) : [], [
            function () use (&$temp_folder)/*: void*/ {
                $this->push($temp_folder);
            },
            function () use (&$temp_folder)/*: void*/ {
                $this->cleanTempFolder($temp_folder);
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
