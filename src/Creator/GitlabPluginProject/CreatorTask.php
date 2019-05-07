<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject;

// BackgroundTasks Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\SrProjectHelper\Config\Config;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractGitlabCreatorTask {

	/**
	 * @inheritdoc
	 */
	protected function getSteps(array $data): array {
		/**
		 * @var Project|null
		 */
		$project = null;

		return [
			function () use (&$data, &$project)/*: void*/ {
				$project = $this->createProject($data["name"], Config::getField(Config::KEY_GITLAB_PLUGINS_GROUP_ID), "master");
			},
			function () use (&$project)/*: void*/ {
				$this->createBranch($project, "develop", "master");
			},
			function () use (&$project)/*: void*/ {
				$this->protectBranch($project, "master");
			},
			function () use (&$project)/*: void*/ {
				$project = $this->setDefaultBranch($project, "master");
			},
			function () use (&$data, &$project)/*: void*/ {
				$this->setMaintainer($project, $data["maintainer_user_id"]);
			},
			function () use (&$project)/*: void*/ {
				$this->useDeployKey($project, Config::getField(Config::KEY_GITLAB_DEPLOY_KEY_ID));
			}
		];
	}
}
