<?php

namespace Cerad\Bundle\ProjectBundle\Doctrine\EntityRepository;

use Cerad\Bundle\CoreBundle\Doctrine\EntityRepository as BaseRepository;

class ProjectRepository extends BaseRepository
{
    public function createProject($params = array()) { return $this->createEntity($params); }
    
    /* ===================================================
     * id, key, slug, slugPrefix
     * Get fancier later and do a query
     */
    public function findProject($param)
    {
        $qb = $this->createQueryBuilder('project');
        
        $where = <<<EOT
(project.id   = :param) OR 
(project.key  = :param) OR
(project.slug = :param) OR
(project.slugPrefix = :param AND project.status = :active)
EOT;
        $qb->andWhere($where); 
        
        $qb->setParameter('param', trim($param));
        $qb->setParameter('active','Active');
        
        $items = $qb->getQuery()->getResult();
        
        if (count($items) != 1) return null;
        
        return $items[0];        
    }
    public function findProjectById($param)
    {
        return $param ? $this->findOneBy(array('id' => $param)) : null;
    }
    public function findProjectByKey($param)
    {
        return $param ? $this->findOneBy(array('key' => $param)) : null;
    }
    public function findProjectBySlug($param)
    {
        return $param ? $this->findOneBy(array('slug' => $param)) : null;
    }
    public function findProjectBySlugPrefix($param)
    {
        return $param ? $this->findOneBy(array('slugPrefix' => $param, 'status' => 'Active')) : null;
    }
}

