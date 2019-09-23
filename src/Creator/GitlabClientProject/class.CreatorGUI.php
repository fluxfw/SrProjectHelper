<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabClientProject;

// ilCtrlMainMenu Bug
require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorFormGUI;
use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorGUI;

/**
 * Class CreatorGUI
 *
 * @package           srag\Plugins\SrProjectHelper\Creator\GitlabClientProject
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\CreatorGUI: ilUIPluginRouterGUI
 */
class CreatorGUI extends AbstractCreatorGUI
{

    const LANG_MODULE = "gitlab_client_project";


    /**
     * @inheritdoc
     */
    protected function getCreatorForm() : AbstractCreatorFormGUI
    {
        $form = new CreatorFormGUI($this);

        return $form;
    }


    /**
     * @inheritdoc
     */
    protected function getTaskClass() : string
    {
        return CreatorTask::class;
    }
}
