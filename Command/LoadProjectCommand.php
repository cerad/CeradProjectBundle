<?php
namespace Cerad\Bundle\ProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadProjectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('cerad_project__project_load')
            ->setDescription('Load a project from yaml file')
            ->addArgument   ('slug', InputArgument::REQUIRED, 'Project Slug')
        ;
    }
    protected function getService  ($id)   { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectSlug= $input->getArgument('slug');
        
        echo sprintf("Load Project: %s\n",$projectSlug);
        
        $projectRepoInMemory = $this->getService('cerad_project__project_repository__in_memory');
        
        $projectInMemory = $projectRepoInMemory->findProjectBySlug($projectSlug);
        if (!$projectInMemory)
        {
            echo sprintf("*** Project %s not found.\n",$projectSlug);
            return;
        }
        $projectKey = $projectInMemory->getKey();
        
        echo sprintf("Load Project: %s %s\n",$projectSlug,$projectKey);
        
        $projectRepoDoctrine = $this->getService('cerad_project__project_repository__doctrine');
        
        $projectDoctrine = $projectRepoDoctrine->findProjectByKey($projectKey);
        if (!$projectDoctrine)
        {
            echo sprintf("Doctrine project not found.\n");
            $projectDoctrine = $projectRepoDoctrine->createProject();
            $projectDoctrine->setMeta($projectInMemory->getMeta());
            $projectRepoDoctrine->persist($projectDoctrine);
            $projectRepoDoctrine->flush();
            echo sprintf("Project added to doctrine.\n");
            return;
        }
        echo sprintf("Doctrine project was found.\n");
    }
 }
?>
