<?php

namespace srag\Plugins\SrProjectHelper\Creator\GithubRepository;

require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Github\AbstractGithubCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\GithubRepository\Form\CreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GithubRepository\Task\CreatorTask;

/**
 * Class CreatorGUI
 *
 * @package           srag\Plugins\SrProjectHelper\Creator\GithubRepository
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrProjectHelper\Creator\GithubRepository\CreatorGUI: ilUIPluginRouterGUI
 */
class CreatorGUI extends AbstractGithubCreatorGUI
{

    const LANG_MODULE = "github_repository";


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
