<?php
namespace Cerad\Bundle\ProjectBundle\InMemory;

use Symfony\Component\Yaml\Yaml;

use Cerad\Bundle\ProjectBundle\Model\Project;
//  Cerad\Bundle\ProjectBundle\Model\ProjectRepositoryInterface;

class ProjectRepository // implements ProjectRepositoryInterface
{
    protected $projects;
    
    public function __construct($files)
    {
        $projects = array();
        foreach($files as $file)
        {
            $meta = Yaml::parse(file_get_contents($file));
            
            $project = new Project();
            $project->setMeta($meta);
            $projects[$project->getKey()] = $project;
        }
        $this->projects = $projects;
    }
    public function find($id)
    {
        return isset($this->projects[$id]) ? $this->projects[$id] : null;
    }
    public function findAll()
    {
        return $this->projects;        
    }
    public function findAllByStatus($status)
    {
        $projects = array();
        foreach($this->projects as $project)
        {
            if ($status == $project->getStatus()) $projects[$project->getId()] = $project;
        }
        return $projects;
    }
    /* ======================================================
     * This will match either the full slug or an active slugPrefix
     */
    public function findProjectBySlug($slug)
    {
        $slug = trim(strtolower($slug));
        if (!$slug) return null;
        
        foreach($this->projects as $project)
        {
            $slugProject = strtolower($project->getSlug());
            
            if ($slug == $slugProject) return $project;
            
            if (!$project->isActive()) break;

            $slugPrefix = strtolower($project->getSlug());
            
            if ($slug == $slugPrefix) return $project;
        }
        return null;
     }
}

?>
