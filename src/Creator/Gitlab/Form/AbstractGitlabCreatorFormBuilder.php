<?php

namespace srag\Plugins\SrProjectHelper\Creator\Gitlab\Form;

use srag\Plugins\SrProjectHelper\Creator\Form\AbstractCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorGUI;

/**
 * Class AbstractGitlabCreatorFormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Gitlab\Form
 */
abstract class AbstractGitlabCreatorFormBuilder extends AbstractCreatorFormBuilder
{

    /**
     * @inheritDoc
     *
     * @param AbstractGitlabCreatorGUI $parent
     */
    public function __construct(AbstractGitlabCreatorGUI $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        if (self::srProjectHelper()->ilias()->users()->getUserId() === intval(SYSTEM_USER_ID) || empty(self::srProjectHelper()->ilias()->users()->getGitlabUserId())) {
            $this->messages[] = self::dic()->ui()->factory()->messageBox()->info(nl2br(self::plugin()->translate("mantainer_user_not_found"), false));
        }

        return parent::render();
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = parent::getData();

        $data["maintainer_user"] = self::srProjectHelper()->ilias()->users()->getGitlabUserId();

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = parent::getFields();

        $fields += [
            "maintainer_user" => self::dic()->ui()->factory()->input()->field()->select(self::plugin()->translate("maintainer_user", $this->parent::LANG_MODULE),
                ["" => ""] + array_map(function (array $user) : string {
                    return $user["name"];
                }, self::srProjectHelper()->ilias()->users()->getGitlabUsers()))->withRequired(true)
        ];

        return $fields;
    }
}
