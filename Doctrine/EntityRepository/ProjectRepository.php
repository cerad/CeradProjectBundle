<?php

namespace Cerad\Bundle\ProjectBundle\Doctrine\EntityRepository;

use Cerad\Bundle\CoreBundle\Doctrine\EntityRepository as BaseRepository;

class ProjectRepository extends BaseRepository
{
    public function createProject($params = array()) { return $this->createEntity($params); }
    
    public function findProjectByKey($key)
    {
        return $key ? $this->findOneBy(array('key' => $key)) : null;
    }
}

