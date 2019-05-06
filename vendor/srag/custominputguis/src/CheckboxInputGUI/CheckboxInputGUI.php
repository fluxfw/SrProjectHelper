<?php

namespace srag\CustomInputGUIs\SrProjectHelper\SrGitlabHelper\SrProjectHelper\CheckboxInputGUI;

use ilCheckboxInputGUI;
use ilTableFilterItem;
use srag\DIC\SrProjectHelper\SrGitlabHelper\SrProjectHelper\DICTrait;

/**
 * Class CheckboxInputGUI
 *
 * @package srag\CustomInputGUIs\SrProjectHelper\SrGitlabHelper\SrProjectHelper\CheckboxInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CheckboxInputGUI extends ilCheckboxInputGUI implements ilTableFilterItem {

	use DICTrait;
}
