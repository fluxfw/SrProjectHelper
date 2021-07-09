<?php

namespace srag\Plugins\SrProjectHelper\Menu;

use ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractBaseItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ILIAS\UI\Component\Symbol\Icon\Standard;
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
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{

    use DICTrait;
    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;


    /**
     * @inheritDoc
     */
    public function getStaticSubItems() : array
    {
        $parent = $this->getStaticTopItems()[0];

        return [
            $this->symbol(GitlabClientProjectCreatorGUI::getMenuItem($this->if, $parent)),
            $this->symbol(GitlabPluginProjectCreatorGUI::getMenuItem($this->if, $parent)),
            $this->symbol(GithubRepositoryCreatorGUI::getMenuItem($this->if, $parent)),
            $this->symbol(GitlabProjectMembersOverviewGUI::getMenuItem($this->if, $parent))
        ];
    }


    /**
     * @inheritDoc
     */
    public function getStaticTopItems() : array
    {
        return [
            $this->symbol($this->mainmenu->topParentItem($this->if->identifier(ilSrProjectHelperPlugin::PLUGIN_ID))->withTitle(self::plugin()->translate("menu_title"))
                ->withAvailableCallable(function () : bool {
                    return self::plugin()->getPluginObject()->isActive();
                })->withVisibilityCallable(function () : bool {
                    return self::srProjectHelper()->currentUserHasRole();
                }))
        ];
    }


    /**
     * @param AbstractBaseItem $entry
     *
     * @return AbstractBaseItem
     */
    protected function symbol(AbstractBaseItem $entry) : AbstractBaseItem
    {
        return $entry->withSymbol(self::dic()->ui()->factory()->symbol()->icon()->standard(Standard::CMPS, ilSrProjectHelperPlugin::PLUGIN_NAME)->withIsOutlined(true));
    }
}
