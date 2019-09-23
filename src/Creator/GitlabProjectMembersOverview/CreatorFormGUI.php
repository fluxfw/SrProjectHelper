<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview;

use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorFormGUI;

/**
 * Class CreatorFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorFormGUI extends AbstractGitlabCreatorFormGUI
{

    const LANG_MODULE = CreatorGUI::LANG_MODULE;


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->data["name"] = self::plugin()->translate("task_title", static::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()
    {

    }
}
