<?php

namespace srag\Plugins\SrProjectHelper\Creator\Gitlab;

use ilNumberInputGUI;
use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorFormGUI;

/**
 * Class AbstractGitlabCreatorFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Gitlab
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractGitlabCreatorFormGUI extends AbstractCreatorFormGUI {

	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		parent::initFields();

		$this->fields += [
			"maintainer_user_id" => [
				self::PROPERTY_CLASS => ilNumberInputGUI::class,
				self::PROPERTY_REQUIRED => true
			]
		];
	}
}
