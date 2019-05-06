<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject;

// ilCtrlMainMenu Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\SrGitlabHelper\Creator\AbstractCreatorFormGUI;
use srag\Plugins\SrGitlabHelper\Creator\AbstractCreatorGUI;

/**
 * Class CreatorGUI
 *
 * @package           srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject\CreatorGUI: ilUIPluginRouterGUI
 */
class CreatorGUI extends AbstractCreatorGUI {

	const LANG_MODULE = "gitlab_plugin_project";


	/**
	 * @inheritdoc
	 */
	protected function getCreatorForm(): AbstractCreatorFormGUI {
		$form = new CreatorFormGUI($this);

		return $form;
	}


	/**
	 * @inheritdoc
	 */
	protected function getTaskClass(): string {
		return CreatorTask::class;
	}
}
