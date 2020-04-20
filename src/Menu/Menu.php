<?php

namespace srag\Plugins\SrProjectHelper\Menu;

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Creator\GithubRepository\CreatorGUI as GithubRepositoryCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\CreatorGUI as GitlabClientProjectCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\CreatorGUI as GitlabPluginProjectCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\CreatorGUI as GitlabProjectMembersOverviewGUI;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Menu
 *
 * @package srag\Plugins\SrProjectHelper\Menu
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @since   ILIAS 5.4
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{

    use DICTrait;
    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;


    /**
     * @inheritDoc
     */
    public function getStaticTopItems() : array
    {
        return [
            $this->mainmenu->topParentItem($this->if->identifier(ilSrProjectHelperPlugin::PLUGIN_ID))->withTitle(self::plugin()->translate("menu_title"))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })->withVisibilityCallable(function () : bool {
                    return self::srProjectHelper()->currentUserHasRole();
                })
        ];
    }


    /**
     * @inheritDoc
     */
    public function getStaticSubItems() : array
    {
        $parent = $this->getStaticTopItems()[0];

        return [
            GitlabClientProjectCreatorGUI::getMenuItem($this->if, $parent),
            GitlabPluginProjectCreatorGUI::getMenuItem($this->if, $parent),
            GithubRepositoryCreatorGUI::getMenuItem($this->if, $parent),
            GitlabProjectMembersOverviewGUI::getMenuItem($this->if, $parent)
        ];
    }
}
