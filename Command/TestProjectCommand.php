<?php
namespace Cerad\Bundle\ProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Cerad\Bundle\ProjectBundle\Model\ProjectPlan;

class TestProjectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName       ('cerad_project__project_test');
        $this->setDescription('Project Tests');
        $this->addArgument   ('param', InputArgument::REQUIRED, 'Project Param');
    }
    protected function getService  ($id)   { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectParam = $input->getArgument('param');
        
        $projectRepo = $this->getService('cerad_project__project_repository__doctrine');
        
        $project = $projectRepo->findProject($projectParam);
        
        if (!$project) 
        {
            echo sprintf("*** Doctrine Project '%s' not found.\n",$projectParam);
            return;
        }
        echo sprintf("Project %d %s %s %s\n",$project->getId(),$project->getSlug(),$project->getKey(),$project->getName());
        
    }
 }
?>
