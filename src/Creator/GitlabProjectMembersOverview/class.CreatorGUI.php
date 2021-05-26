<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview;

require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\Form\CreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\Task\CreatorTask;

/**
 * Class CreatorGUI
 *
 * @package           srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\CreatorGUI: ilUIPluginRouterGUI
 */
class CreatorGUI extends AbstractGitlabCreatorGUI
{

    const LANG_MODULE = "gitlab_project_members_overview";
    const START_CMD = self::CMD_CREATE;


    /**
     * @inheritDoc
     */
    protected function getCreatorFormBuilder() : AbstractCreatorFormBuilder
    {
        $form = new CreatorFormBuilder($this);

        return $form;
    }


    /**
     * @inheritDoc
     */
    protected function getTaskClass() : string
    {
        return CreatorTask::class;
    }


    /**
     * @inheritDoc
     */
    protected function shouldDownloadOutput() : bool
    {
        return true;
    }
}
