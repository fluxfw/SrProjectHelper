<?php

namespace srag\Plugins\SrProjectHelper\Gitlab\Client;

use Gitlab\Client as GitlabClient;

/**
 * Class Client
 *
 * @package srag\Plugins\SrProjectHelper\Gitlab\Client
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
    public function projects() : Projects
    {
        return new Projects($this);
    }


    /**
     * @inheritDoc
     */
    public function repositories() : Repositories
    {
        return new Repositories($this);
    }
}
