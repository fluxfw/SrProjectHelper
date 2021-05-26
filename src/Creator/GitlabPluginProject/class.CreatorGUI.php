<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject;

require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\Form\CreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\Task\CreatorTask;

/**
 * Class CreatorGUI
 *
 * @package           srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\CreatorGUI: ilUIPluginRouterGUI
 */
class CreatorGUI extends AbstractGitlabCreatorGUI
{

    const LANG_MODULE = "gitlab_plugin_project";


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
        return false;
    }
}
