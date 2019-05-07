<?php

namespace srag\CustomInputGUIs\SrProjectHelper;

use ILIAS\UI\Implementation\Component\Chart\ProgressMeter\Factory as ProgressMeterFactoryCore;
use srag\CustomInputGUIs\SrProjectHelper\LearningProgressPieUI\LearningProgressPieUI;
use srag\CustomInputGUIs\SrProjectHelper\ProgressMeter\Implementation\Factory as ProgressMeterFactory;
use srag\CustomInputGUIs\SrProjectHelper\ViewControlModeUI\ViewControlModeUI;
use srag\DIC\SrProjectHelper\DICTrait;

/**
 * Class CustomInputGUIs
 *
 * @package srag\CustomInputGUIs\SrProjectHelper
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class CustomInputGUIs {

	use DICTrait;
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance()/*: self*/ {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * CustomInputGUIs constructor
	 */
	private function __construct() {

	}


	/**
	 * @return LearningProgressPieUI
	 */
	public function learningProgressPie() {
		return new LearningProgressPieUI();
	}


	/**
	 * @return ProgressMeterFactoryCore|ProgressMeterFactory
	 *
	 * @since ILIAS 5.4
	 */
	public function progressMeter() {
		if (self::version()->is54()) {
			return new ProgressMeterFactoryCore();
		} else {
			return new ProgressMeterFactory();
		}
	}


	/**
	 * @return ViewControlModeUI
	 */
	public function viewControlMode() {
		return new ViewControlModeUI();
	}
}
