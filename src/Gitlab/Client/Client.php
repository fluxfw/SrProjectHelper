<?php

namespace srag\Plugins\SrProjectHelper\Gitlab\Client;

use Gitlab\Client as GitlabClient;

/**
 * Class Client
 *
 * @package srag\Plugins\SrProjectHelper\Gitlab\Client
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Client extends GitlabClient
{

    /**
     * @inheritDoc
     */
    public static function create(/*string*/ $url) : self
    {
        $client = new self();

        $client->setUrl($url);

        return $client;
    }


    /**
     * @inheritDoc
     */
    public function repositories() : Repositories
    {
        return new Repositories($this);
    }
}
