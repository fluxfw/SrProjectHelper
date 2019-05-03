<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;
use srag\RemovePluginDataConfirm\SrGitlabHelper\AbstractRemovePluginDataConfirm;

/**
 * Class SrGitlabHelperRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy SrGitlabHelperRemoveDataConfirm: ilUIPluginRouterGUI
 */
class SrGitlabHelperRemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
}
