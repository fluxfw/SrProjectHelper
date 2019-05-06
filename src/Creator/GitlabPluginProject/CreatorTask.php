<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject;

// BackgroundTasks Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use Gitlab\Model\Project;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Creator\Gitlab\AbstractGitlabCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractGitlabCreatorTask {

	const STEPS = [ "createProject", "createDevelopBranch", "protectMasterBranch", "setDefaultMasterBranch", "setMaintainer", "useDeployKey" ];


	/**
	 *
	 */
	protected function createProject()/*: void*/ {
		$this->project = Project::create(self::gitlab(), $this->data["name"], [
			"default_branch" => "master",
			"namespace_id" => Config::getField(Config::KEY_GITLAB_PLUGINS_GROUP_ID),
			"path" => $this->data["name"],
			"visibility" => "internal"
		]);
	}


	/**
	 *
	 */
	protected function createDevelopBranch()/*: void*/ {
		$this->project->createBranch("develop", "master");
	}


	/**
	 *
	 */
	protected function protectMasterBranch()/*: void*/ {
		self::gitlab()->repositories()->protectBranch2($this->project->id, "master", [
			"allowed_to_merge" => true,
			"allowed_to_push" => false,
			"merge_access_level" => 40,
			"push_access_level" => 0
		]);
	}


	/**
	 *
	 */
	protected function setDefaultMasterBranch()/*: void*/ {
		$this->project = $this->project->update([
			"default_branch" => "master"
		]);
	}


	/**
	 *
	 */
	protected function setMaintainer()/*: void*/ {
		$this->project->addMember($this->data["maintainer_user_id"], 40);
	}
}
