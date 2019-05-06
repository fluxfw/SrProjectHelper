<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabClientProject;

// BackgroundTasks Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use Gitlab\Model\Group;
use Gitlab\Model\Project;
use srag\Plugins\SrProjectHelper\Config\Config;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorTask;
use srag\Plugins\SrProjectHelper\Gitlab\Api;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabClientProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractGitlabCreatorTask {

	const STEPS = [
		"createGroup",
		"createProject",
		"createCustomBranch",
		"setDefaultCustomBranch",
		"removeMasterBranch",
		"protectCustomBranch",
		"setMaintainer",
		"useDeployKey",
		"cleanTempFolder",
		"cloneILIAS",
		"notIgnoreCustomizingFolder",
		"addPluginsAsSubmodules",
		"push",
		"cleanTempFolder",
		"createStagingBranch",
		"protectStagingBranch",
		"createDevelopBranch"
	];
	const ILIAS_PROJECT_NAME = "ILIAS";
	/**
	 * @var string
	 */
	protected $temp_folder = "";


	/**
	 *
	 */
	protected function createGroup()/*: void*/ {
		$this->group = Group::fromArray(self::gitlab(), self::gitlab()->groups()
			->create($this->data["name"], $this->data["name"], "", "internal", null, null, Config::getField(Config::KEY_GITLAB_CLIENTS_GROUP_ID)));
	}


	/**
	 *
	 */
	protected function createProject()/*: void*/ {
		$this->project = Project::create(self::gitlab(), self::ILIAS_PROJECT_NAME, [
			"default_branch" => Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"],
			"namespace_id" => $this->group->id,
			"path" => self::ILIAS_PROJECT_NAME,
			"visibility" => "internal"
		]);
	}


	/**
	 *
	 */
	protected function createCustomBranch()/*: void*/ {
		$this->project->createBranch(Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"], "master");
	}


	/**
	 *
	 */
	protected function setDefaultCustomBranch()/*: void*/ {
		$this->project = $this->project->update([
			"default_branch" => Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"]
		]);
	}


	/**
	 *
	 */
	protected function removeMasterBranch()/*: void*/ {
		$this->project->branch("master")->unprotect();

		$this->project->branch("master")->delete();
	}


	/**
	 *
	 */
	protected function protectCustomBranch()/*: void*/ {
		self::gitlab()->repositories()
			->protectBranch2($this->project->id, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"], [
				"allowed_to_merge" => true,
				"allowed_to_push" => false,
				"merge_access_level" => 40,
				"push_access_level" => 0
			]);
	}


	/**
	 *
	 */
	protected function setMaintainer()/*: void*/ {
		$this->group->addMember($this->data["maintainer_user_id"], 40);
	}


	/**
	 *
	 */
	protected function cloneILIAS()/*: void*/ {
		$this->temp_folder = CLIENT_DATA_DIR . "/temp/" . uniqid($this->data["name"]);

		$result = [];
		exec("git clone -b " . escapeshellarg(Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"]) . " "
			. escapeshellarg(Api::tokenRepoUrl($this->project->http_url_to_repo)) . " " . escapeshellarg($this->temp_folder) . " 2>&1", $result);

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " remote add temp "
			. escapeshellarg(Api::tokenRepoUrl((new Project(Config::getField(Config::KEY_GITLAB_ILIAS_PROJECT_ID), self::gitlab()))->show()->http_url_to_repo))
			. " 2>&1", $result);

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " fetch temp 2>&1", $result);

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " merge " . escapeshellarg("temp/" . $this->data["ilias_version"]) . " 2>&1", $result);

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " remote remove temp 2>&1", $result);

		$this->push();
	}


	/**
	 *
	 */
	protected function notIgnoreCustomizingFolder()/*: void*/ {
		file_put_contents($this->temp_folder
			. "/.gitignore", str_replace("\n/Customizing/global", "\n#/Customizing/global", file_get_contents($this->temp_folder . "/.gitignore")));

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " add . 2>&1", $result);

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " commit -m " . escapeshellarg("Not ignore Customizing/global") . " 2>&1", $result);
	}


	/**
	 *
	 */
	protected function addPluginsAsSubmodules()/*: void*/ {
		foreach ($this->data["plugins"] as $i => $plugin) {
			$plugin = Config::getField(Config::KEY_GITLAB_PLUGINS)[$plugin];

			if ($plugin) {

				$result = [];
				exec("git -C " . escapeshellarg($this->temp_folder) . " submodule add -b master "
					. escapeshellarg(Api::tokenRepoUrl($plugin["repo_http"])) . " " . escapeshellarg($plugin["install_path"]) . " 2>&1", $result);

				$result = [];
				exec("git -C " . escapeshellarg($this->temp_folder) . " add . 2>&1", $result);

				$result = [];
				exec("git -C " . escapeshellarg($this->temp_folder) . " commit -m " . escapeshellarg($plugin["name"] . " plugin submodule")
					. " 2>&1", $result);

				file_put_contents($this->temp_folder . "/.gitmodules", str_replace(Api::tokenRepoUrl($plugin["repo_http"]), "../../../Plugins/"
					. $plugin["name"] . ".git", file_get_contents($this->temp_folder . "/.gitmodules")));

				$result = [];
				exec("git -C " . escapeshellarg($this->temp_folder) . " add . 2>&1", $result);

				$result = [];
				exec("git -C " . escapeshellarg($this->temp_folder) . " commit --amend -m " . escapeshellarg($plugin["name"] . " plugin submodule")
					. " 2>&1", $result);
			}

			$this->observer->notifyPercentage($this, intval(($this->current_step_index + (($i + 1) / count($this->data["plugins"])))
				/ count(static::STEPS) * 100));
		}
	}


	/**
	 *
	 */
	protected function push()/*: void*/ {
		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " push 2>&1", $result);
	}


	/**
	 *
	 */
	protected function cleanTempFolder()/*: void*/ {
		$result = [];
		exec("rm -rfd " . escapeshellarg($this->temp_folder), $result);
	}


	/**
	 *
	 */
	protected function createStagingBranch()/*: void*/ {
		$this->project->createBranch(Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["staging_name"], Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"]);
	}


	/**
	 *
	 */
	protected function protectStagingBranch()/*: void*/ {
		self::gitlab()->repositories()
			->protectBranch2($this->project->id, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["staging_name"], [
				"allowed_to_merge" => true,
				"allowed_to_push" => false,
				"merge_access_level" => 40,
				"push_access_level" => 0
			]);
	}


	/**
	 *
	 */
	protected function createDevelopBranch()/*: void*/ {
		$this->project->createBranch(Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["develop_name"], Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["staging_name"]);
	}
}
