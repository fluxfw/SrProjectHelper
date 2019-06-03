<?php

namespace srag\Plugins\SrProjectHelper\Creator;

use ILIAS\BackgroundTasks\Implementation\Bucket\BasicBucket;
use ilSrProjectHelperPlugin;
use ilUtil;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class AbstractCreatorGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorGUI {

	use DICTrait;
	use SrProjectHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
	const CMD_CREATE = "create";
	const CMD_FORM = "form";
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const LANG_MODULE = "";


	/**
	 * AbstractCreatorGUI constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		if (!self::access()->currentUserHasRole()) {
			die();
		}

		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch (strtolower($next_class)) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_CREATE:
					case self::CMD_FORM:
						$this->{$cmd}();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 */
	protected function form()/*: void*/ {
		$form = $this->getCreatorForm();

		self::output()->output($form, true);
	}


	/**
	 *
	 */
	protected function create()/*: void*/ {
		$form = $this->getCreatorForm();

		if (!$form->storeForm()) {
			self::output()->output($form, true);

			return;
		}

		$data = $form->getData();

		$this->buildAndRunTask($data);

		ilUtil::sendSuccess(self::plugin()->translate("created", static::LANG_MODULE, [ $data["name"] ]), true);

		self::dic()->ctrl()->redirect($this, self::CMD_FORM);
	}


	/**
	 * @inheritdoc
	 */
	protected function buildAndRunTask(array $data)/*: void*/ {
		$bucket = new BasicBucket();

		$bucket->setUserId(self::ilias()->users()->getUserId());

		$task = self::dic()->backgroundTasks()->taskFactory()->createTask($this->getTaskClass(), [ json_encode($data) ]);

		$bucket->setTask($task);
		$bucket->setTitle(self::plugin()->translate("task_title", static::LANG_MODULE, [ $data["name"] ]));

		self::dic()->backgroundTasks()->taskManager()->run($bucket);
	}


	/**
	 * @return AbstractCreatorFormGUI
	 */
	protected abstract function getCreatorForm(): AbstractCreatorFormGUI;


	/**
	 * @return string
	 */
	protected abstract function getTaskClass(): string;
}
