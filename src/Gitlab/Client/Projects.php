<?php

namespace srag\Plugins\SrProjectHelper\Gitlab\Client;

use Gitlab\Api\Projects as GitlabProjects;

/**
 * Class Projects
 *
 * @package srag\Plugins\SrProjectHelper\Gitlab\Client
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Projects extends GitlabProjects
{

    /**
     * @param string $project_id
     * @param array  $params
     *
     * @return mixed
     */
    public function mirror(string $project_id, array $params)
    {
        return $this->post('projects/' . $this->encodePath($project_id) . '/remote_mirrors', $params);
    }
}
