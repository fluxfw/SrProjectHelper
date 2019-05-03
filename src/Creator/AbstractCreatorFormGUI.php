<?php

namespace srag\Plugins\SrGitlabHelper\Creator;

use ilSrGitlabHelperPlugin;
use srag\CustomInputGUIs\SrGitlabHelper\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class AbstractCreatorFormGUI
 *
 * @package srag\Plugins\SrGitlabHelper\Creator
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorFormGUI extends PropertyFormGUI {

	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	/**
	 * @var array
	 */
	protected $data = [];


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		switch ($key) {
			default:
				if (isset($this->data[$key])) {
					return $this->data[$key];
				}
				break;
		}

		return null;
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$this->addCommandButton(AbstractCreatorGUI::CMD_CREATE, $this->txt("create"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("srgitlabhelper_form");
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {
		$this->setTitle($this->txt("title"));
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			default:
				$this->data[$key] = $value;
				break;
		}
	}


	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}
}
