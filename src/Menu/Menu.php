<?php

namespace srag\Plugins\SrGitlabHelper\Menu;

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ilSrGitlabHelperPlugin;
use ilUIPluginRouterGUI;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Creator\GitlabClientProject\CreatorGUI as GitlabClientProjectCreatorGUI;
use srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject\CreatorGUI as GitlabPluginProjectCreatorGUI;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class Menu
 *
 * @package srag\Plugins\SrGitlabHelper\Menu
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @since   ILIAS 5.4
 */
class Menu extends AbstractStaticPluginMainMenuProvider {

	use DICTrait;
	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;


	/**
	 * @inheritdoc
	 */
	public function getStaticTopItems(): array {
		return [
			self::dic()->globalScreen()->mainmenu()->topParentItem(self::dic()->globalScreen()->identification()->plugin(self::plugin()
				->getPluginObject(), $this)->identifier(ilSrGitlabHelperPlugin::PLUGIN_ID))->withTitle(ilSrGitlabHelperPlugin::PLUGIN_NAME)
				->withAvailableCallable(function (): bool {
					return self::plugin()->getPluginObject()->isActive();
				})->withVisibilityCallable(function (): bool {
					return self::access()->currentUserHasRole();
				})
		];
	}


	/**
	 * @inheritdoc
	 */
	public function getStaticSubItems(): array {
		$parent = $this->getStaticTopItems()[0];

		return [
			self::dic()->globalScreen()->mainmenu()->link(self::dic()->globalScreen()->identification()->plugin(self::plugin()
				->getPluginObject(), $this)->identifier(ilSrGitlabHelperPlugin::PLUGIN_ID . "_create_gitlab_client_project"))
				->withParent($parent->getProviderIdentification())->withTitle(self::plugin()
					->translate("title", GitlabClientProjectCreatorGUI::LANG_MODULE))->withAction(self::dic()->ctrl()->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					GitlabClientProjectCreatorGUI::class
				], GitlabClientProjectCreatorGUI::CMD_FORM)),
			self::dic()->globalScreen()->mainmenu()->link(self::dic()->globalScreen()->identification()->plugin(self::plugin()
				->getPluginObject(), $this)->identifier(ilSrGitlabHelperPlugin::PLUGIN_ID . "_create_gitlab_plugin_project"))
				->withParent($parent->getProviderIdentification())->withTitle(self::plugin()
					->translate("title", GitlabPluginProjectCreatorGUI::LANG_MODULE))->withAction(self::dic()->ctrl()->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					GitlabPluginProjectCreatorGUI::class
				], GitlabPluginProjectCreatorGUI::CMD_FORM))
		];
	}
}
