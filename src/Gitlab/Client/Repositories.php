<?php

namespace srag\Plugins\SrProjectHelper\Gitlab\Client;

use Gitlab\Api\Repositories as GitlabRepositories;

/**
 * Class Repositories
 *
 * @package srag\Plugins\SrProjectHelper\Gitlab\Client
 */
class Repositories extends GitlabRepositories
{

    /**
     * @param int    $project_id
     * @param string $branch_name
     * @param array  $parameters
     *
     * @return mixed
     */
    public function protectBranch2(int $project_id, string $branch_name, array $parameters = [])
    {
        return $this->unprotectBranch($project_id, $branch_name)
            && $this->post($this->getProjectPath($project_id, "protected_branches"), ["name" => $branch_name] + $parameters);
    }
}
