<?php

namespace srag\Plugins\SrProjectHelper\Creator\Gitlab;

use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\Form\AbstractGitlabCreatorFormBuilder;

/**
 * Class AbstractGitlabCreatorGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Gitlab
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
