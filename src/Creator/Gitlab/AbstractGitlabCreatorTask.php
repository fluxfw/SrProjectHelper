<?php

namespace srag\Plugins\SrProjectHelper\Creator\Gitlab;

use Gitlab\Model\Group;
use Gitlab\Model\Project;
use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Observer;
use ILIAS\BackgroundTasks\Value;
use srag\Plugins\SrProjectHelper\Config\Config;
use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorTask;

/**
 * Class AbstractGitlabCreatorTaskAbstractGitlabCreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Gitlab
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractGitlabCreatorTask extends AbstractCreatorTask {

	/**
	 * @var string[]
	 *
	 * @abstract
	 */
	const STEPS = [];
	/**
	 * @var Group|null
	 */
	protected $group = null;
	/**
	 * @var Project|null
	 */
	protected $project = null;


	/**
	 * @inheritdoc
	 */
	public function run(array $input, Observer $observer): Value {
		$this->setData($input);

		foreach (static::STEPS as $i => $step) {
			$this->{$step}();

			$observer->notifyPercentage($this, intval(($i + 1) / count(static::STEPS) * 100));
		}

		$output = new StringValue();
		$output->setValue("");

		return $output;
	}


	/**
	 *
	 */
	protected function useDeployKey()/*: void*/ {
		$this->project->enableDeployKey(Config::getField(Config::KEY_GITLAB_DEPLOY_KEY_ID));
	}
}
