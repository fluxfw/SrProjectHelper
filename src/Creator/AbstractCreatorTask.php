<?php

namespace srag\Plugins\SrProjectHelper\Creator;

use ILIAS\BackgroundTasks\Implementation\Tasks\AbstractJob;
use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Observer;
use ILIAS\BackgroundTasks\Types\SingleType;
use ILIAS\BackgroundTasks\Types\Type;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class AbstractCreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorTask extends AbstractJob {

	use DICTrait;
	use SrProjectHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
	/**
	 * @var array
	 */
	protected $data = [];
	/**
	 * @var Observer|null
	 */
	protected $observer = null;


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
		return 300;
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


	/**
	 * @param array    $input
	 * @param Observer $observer
	 */
	protected function setData(array $input, Observer $observer)/*: void*/ {
		$this->data = json_decode($input[0]->getValue(), true);

		$this->observer = $observer;
	}
}
