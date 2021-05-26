<?php

namespace srag\Plugins\SrProjectHelper\Creator\Github\Form;

use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Github\AbstractGithubCreatorGUI;

/**
 * Class AbstractGithubCreatorFormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Github\Form
 */
abstract class AbstractGithubCreatorFormBuilder extends AbstractCreatorFormBuilder
{

    /**
     * @inheritDoc
     *
     * @param AbstractGithubCreatorGUI $parent
     */
    public function __construct(AbstractGithubCreatorGUI $parent)
    {
        parent::__construct($parent);
    }
}
