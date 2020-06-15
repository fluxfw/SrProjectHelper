<?php

namespace srag\Plugins\SrProjectHelper\Github;

use Github\Client;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrProjectHelper\Github
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;
    /**
     * @var Client
     */
    protected $client;


    /**
     * Repository constructor
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
     * @return Client
     */
    public function client() : Client
    {
        if ($this->client === null) {
            $this->client = new Client();

            $this->client->authenticate(self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITHUB_ACCESS_TOKEN), null, Client::AUTH_URL_TOKEN);
        }

        return $this->client;
    }


    /**
     * @param string $name
     */
    public function createRepository(string $name)/*:void*/
    {
        $this->client()->repositories()->create($name, "", "", true, self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITHUB_ORGANISATION), false, false, true, null, false, false);
    }
}
