<?php

namespace srag\Plugins\SrProjectHelper\Access;

use ilObjUser;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Users
 *
 * @package srag\Plugins\SrProjectHelper\Access
 */
final class Users
{

    use DICTrait;
    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Users constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @param string $email
     *
     * @return bool
     */
    public function existsUserByEmail(string $email) : bool
    {
        return (count(ilObjUser::getUserLoginsByEmail($email)) > 0);
    }


    /**
     * @return int|null
     */
    public function getGitlabUserId() : ?int
    {
        return key(array_filter($this->getGitlabUsers(), function (array $user) : bool {
            return ($user["email"] === self::dic()->user()->getEmail());
        }));
    }


    /**
     * @return array
     */
    public function getGitlabUsers() : array
    {
        return array_filter(self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_USERS), function (array $user) : bool {
            return $this->existsUserByEmail($user["email"]);
        });
    }


    /**
     * @return int
     */
    public function getUserId() : int
    {
        $user_id = self::dic()->user()->getId();

        // Fix login screen
        if ($user_id === 0 && boolval(self::dic()->settings()->get("pub_section"))) {
            $user_id = ANONYMOUS_USER_ID;
        }

        return intval($user_id);
    }
}
