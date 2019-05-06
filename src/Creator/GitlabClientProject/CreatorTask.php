<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabClientProject;

// BackgroundTasks Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Observer;
use ILIAS\BackgroundTasks\Value;
use srag\Plugins\SrGitlabHelper\Creator\AbstractCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrGitlabHelper\Creator\GitlabClientProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractCreatorTask {

	/**
	 * @inheritdoc
	 */
	public function run(array $input, Observer $observer): Value {
		$this->setData($input);

		$output = new StringValue();
		$output->setValue("");

		return $output;
	}
}
