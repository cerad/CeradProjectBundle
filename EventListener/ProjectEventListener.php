<?php
namespace Cerad\Bundle\ProjectBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Cerad\Bundle\CoreBundle\Events\ProjectEvents;

use Cerad\Bundle\CoreBundle\Event\Project\FindByEvent;

class ProjectEventListener extends ContainerAware implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array
        (
            ProjectEvents::FindProjectById   => array('onFindProjectById'   ),
            ProjectEvents::FindProjectByKey  => array('onFindProjectByKey'  ),
            ProjectEvents::FindProjectBySlug => array('onFindProjectBySlug' ),
        );
    }
    protected $projectRepositoryServiceId;
    
    public function __construct($projectRepositoryServiceId)
    {
        $this->projectRepositoryServiceId = $projectRepositoryServiceId;
    }
    protected function getProjectRepository()
    {
        return $this->container->get($this->projectRepositoryServiceId);
    }
    public function onFindProjectBySlug(FindByEvent $event)
    {
        $project = $this->getProjectRepository()->findOneBySlug($event->getParam());
        if ($project)
        {
             $event->setProject($project);
             $event->stopPropagation();
        }
    }
    public function onFindProjectByKey(Event $event)
    {
        $project = $this->getProjectRepository()->findOneByKey($event->getParam());
        if ($project)
        {
             $event->setProject($project);
             $event->stopPropagation();
        }
    }
    public function onFindProjectById(Event $event)
    {
        $project = $this->getProjectRepository()->find($event->getParam());
        if ($project)
        {
             $event->setProject($project);
             $event->stopPropagation();
        }
    }
}