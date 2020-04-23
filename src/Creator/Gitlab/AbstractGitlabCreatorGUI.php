<?php

namespace srag\Plugins\SrProjectHelper\Creator\Gitlab;

use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\Form\AbstractGitlabCreatorFormBuilder;

/**
 * Class AbstractGitlabCreatorGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Gitlab
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractGitlabCreatorGUI extends AbstractCreatorGUI
{

    /**
     * @inheritDoc
     *
     * @return AbstractGitlabCreatorFormBuilder
     */
    protected abstract function getCreatorFormBuilder() : AbstractCreatorFormBuilder;


    /**
     * @inheritDoc
     */
    protected abstract function getTaskClass() : string;
}
