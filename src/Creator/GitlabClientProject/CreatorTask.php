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
class CreatorTask extends AbstractGitlabCreatorTask {

	/**
	 * @inheritdoc
	 */
	protected function getSteps(array $data): array {
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
				$this->protectBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
			},
			function () use (&$data, &$project)/*: void*/ {
				$this->setMaintainer($project, $data["maintainer_user_id"]);
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
		], array_map(function (string $plugin): callable {
			return function ()/*: void*/ use (&$temp_folder, &$plugin) {
				$this->addPluginsAsSubmodules($temp_folder, $plugin);
			};
		}, $data["plugins"]), [
			function () use (&$temp_folder)/*: void*/ {
				$this->push($temp_folder);
			},
			function () use (&$temp_folder)/*: void*/ {
				$this->cleanTempFolder($temp_folder);
			},
			function () use (&$data, &$project)/*: void*/ {
				$this->createBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"], Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["custom_name"]);
			},
			function () use (&$data, &$project)/*: void*/ {
				$this->protectBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"]);
			},
			function () use (&$data, &$project)/*: void*/ {
				$this->createBranch($project, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["develop_name"], Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$data["ilias_version"]]["staging_name"]);
			}
		]);
	}
}
