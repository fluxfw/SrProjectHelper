<?php

namespace srag\Plugins\SrProjectHelper\Creator\Gitlab;

use ilSelectInputGUI;
use ilUtil;
use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorFormGUI;

/**
 * Class AbstractGitlabCreatorFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Gitlab
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractGitlabCreatorFormGUI extends AbstractCreatorFormGUI
{

    /**
     * @inheritdoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            case "maintainer_user":
                return self::ilias()->users()->getGitlabUserId();

            default:
                return parent::getValue($key);
        }
    }


    /**
     * @inheritdoc
     */
    protected function initFields()/*: void*/
    {
        parent::initFields();

        if (self::ilias()->users()->getUserId() === intval(SYSTEM_USER_ID) || empty(self::ilias()->users()->getGitlabUserId())) {
            ilUtil::sendInfo(nl2br(str_replace("\\n", "\n", $this->txt("mantainer_user_not_found")), false));
        }

        $this->fields += [
            "maintainer_user" => [
                self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                self::PROPERTY_OPTIONS  => ["" => ""] + array_map(function (array $user) : string {
                        return $user["name"];
                    }, self::ilias()->users()->getGitlabUsers()),
                self::PROPERTY_REQUIRED => true
            ]
        ];
    }
}
