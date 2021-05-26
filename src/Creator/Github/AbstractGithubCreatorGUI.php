<?php

namespace srag\Plugins\SrProjectHelper\Creator\Github;

use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorGUI;
use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Github\Form\AbstractGithubCreatorFormBuilder;

/**
 * Class AbstractGithubCreatorGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Github
 */
abstract class AbstractGithubCreatorGUI extends AbstractCreatorGUI
{

    /**
     * @inheritDoc
     *
     * @return AbstractGithubCreatorFormBuilder
     */
    protected abstract function getCreatorFormBuilder() : AbstractCreatorFormBuilder;


    /**
     * @inheritDoc
     */
    protected abstract function getTaskClass() : string;
}
