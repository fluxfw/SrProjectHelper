<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject;

// BackgroundTasks Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use Gitlab\Model\Project;
use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Observer;
use ILIAS\BackgroundTasks\Value;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Creator\AbstractCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractCreatorTask {

	/**
	 * @var Project|null
	 */
	protected $project = null;


	/**
	 * @inheritdoc
	 */
	public function run(array $input, Observer $observer): Value {
		$this->setData($input);

		$steps = [ "createProject", "createDevelopBranch", "protectMasterBranch", "setDefaultMasterBranch", "setMaintainer", "useDeployKey" ];

		foreach ($steps as $i => $step) {
			$this->{$step}();

			$observer->notifyPercentage($this, intval(($i + 1) / count($steps) * 100));
		}

		$output = new StringValue();
		$output->setValue("");

		return $output;
	}


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


	/**
	 *
	 */
	protected function useDeployKey()/*: void*/ {
		$this->project->enableDeployKey(Config::getField(Config::KEY_GITLAB_DEPLOY_KEY_ID));
	}
}
