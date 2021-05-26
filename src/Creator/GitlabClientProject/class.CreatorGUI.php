<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabClientProject;

require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\Form\CreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\Task\CreatorTask;

/**
 * Class CreatorGUI
 *
 * @package           srag\Plugins\SrProjectHelper\Creator\GitlabClientProject
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\CreatorGUI: ilUIPluginRouterGUI
 */
class CreatorGUI extends AbstractGitlabCreatorGUI
{

    const LANG_MODULE = "gitlab_client_project";


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
