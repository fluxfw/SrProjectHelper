<?php

namespace srag\Plugins\SrGitlabHelper\Creator;

use ILIAS\BackgroundTasks\Implementation\Tasks\AbstractJob;
use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Types\SingleType;
use ILIAS\BackgroundTasks\Types\Type;
use ilSrGitlabHelperPlugin;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class AbstractCreatorTask
 *
 * @package srag\Plugins\SrGitlabHelper\Creator
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorTask extends AbstractJob {

	use DICTrait;
	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;


	/**
	 * @inheritdoc
	 */
	public function isStateless(): bool {
		return false;
	}


	/**
	 * @inheritdoc
	 */
	public function getExpectedTimeOfTaskInSeconds(): int {
		return 60;
	}


	/**
	 * @inheritdoc
	 */
	public function getInputTypes(): array {
		return [
			new SingleType(StringValue::class)
		];
	}


	/**
	 * @inheritdoc
	 */
	public function getOutputType(): Type {
		return new SingleType(StringValue::class);
	}
}
