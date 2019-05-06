<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabClientProject;

// BackgroundTasks Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use Gitlab\Model\Group;
use Gitlab\Model\Project;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Creator\Gitlab\AbstractGitlabCreatorTask;
use srag\Plugins\SrGitlabHelper\Gitlab\Api;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrGitlabHelper\Creator\GitlabClientProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractGitlabCreatorTask {

	const STEPS = [
		"createGroup",
		"createProject",
		"createCustomBranch",
		"setDefaultCustomBranch",
		"setMaintainer",
		"removeMasterBranch",
		"cleanTempFolder",
		"cloneILIAS",
		"notIgnoreCustomizingFolder",
		"addPluginsAsSubmodules",
		"cleanTempFolder",
		"createStagingBranch",
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
	protected function cloneILIAS()/*: void*/ {
		$this->temp_folder = CLIENT_DATA_DIR . "/temp/" . uniqid($this->data["name"]);

		$result = [];
		exec("git clone -b " . escapeshellarg(Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"]) . " "
			. escapeshellarg(Api::tokenRepoUrl($this->project->http_url_to_repo)) . " " . escapeshellarg($this->temp_folder) . " 2>&1", $result);

		return;
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

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " push 2>&1", $result);
	}


	/**
	 *
	 */
	protected function notIgnoreCustomizingFolder()/*: void*/ {
		return;
		file_put_contents($this->temp_folder
			. "/.gitignore", str_replace("\n/Customizing/global", "\n#/Customizing/global", file_get_contents($this->temp_folder . "/.gitignore")));

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " add . 2>&1", $result);

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " commit -m " . escapeshellarg("Not ignore Customizing/global") . " 2>&1", $result);

		$result = [];
		exec("git -C " . escapeshellarg($this->temp_folder) . " push 2>&1", $result);
	}


	/**
	 *
	 */
	protected function addPluginsAsSubmodules()/*: void*/ {
		foreach ($this->data["plugins"] as $plugin) {
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

				$result = [];
				exec("git -C " . escapeshellarg($this->temp_folder) . " push 2>&1", $result);
			}
		}
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
	protected function setDefaultCustomBranch()/*: void*/ {
		$this->project = $this->project->update([
			"default_branch" => Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["custom_name"]
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
	protected function removeMasterBranch()/*: void*/ {
		$this->project->branch("master")->delete();
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
	protected function createDevelopBranch()/*: void*/ {
		$this->project->createBranch(Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["develop_name"], Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)[$this->data["ilias_version"]]["staging_name"]);
	}
}
