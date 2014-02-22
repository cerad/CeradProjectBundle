<?php
namespace Cerad\Bundle\ProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Cerad\Bundle\ProjectBundle\Model\ProjectPlan;

use Symfony\Component\Yaml\Yaml;

class LoadProjectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName       ('cerad_project__project__load');
        $this->setDescription('Load a project from yaml file');
        $this->addArgument   ('file', InputArgument::REQUIRED, 'Project File');
    }
    protected function getService  ($id)   { return $this->getContainer()->get($id); }
    protected function getParameter($name) { return $this->getContainer()->getParameter($name); }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $projectMeta = Yaml::parse(file_get_contents($file));
        $projectKey  = $projectMeta['key'];
        
        $projectRepo = $this->getService('cerad_project__project_repository__doctrine');
        
        $project = $projectRepo->findProjectByKey($projectKey);
        if (!$project) $project = $projectRepo->createProject();
        
        $project->setMeta($projectMeta);
        $projectRepo->persist($project);
        $projectRepo->flush();
        
        echo sprintf("Loaded %s %s\n",$file,$project->getSlug());
    }
    protected function executeSlug(InputInterface $input, OutputInterface $output)
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
        else
        {
            sprintf("Doctrine project was found.\n");
            $projectDoctrine->setMeta($projectInMemory->getMeta());
            $projectRepoDoctrine->flush();
        }
        // Dates access
        $dates = $projectDoctrine->getDates();
        foreach($dates as $date)
        {
            echo sprintf("Date %s %s %d %s\n",$date->getDate(),$date->getLabel(),$date->getLodging(),$date->getAvail());
        }
        // Assignor access
        $assignor = $projectDoctrine->getAssignor();
        echo sprintf("Assignor: %s\n",$assignor->getName());
        
        $assignor->setName('ART HUNDIAK');
        $projectDoctrine->setAssignor($assignor);
        $projectRepoDoctrine->flush();
        
        $plan = $projectDoctrine->getPlan();
        $lodging = $plan[ProjectPlan::PlanLodging];
        foreach($lodging['items'] as $key => $item)
        {
            echo sprintf("Lodge %s %s %s\n",$key,$item['date'],$item['label']);
        }
        $avail = $plan[ProjectPlan::PlanAvailability];
        foreach($avail['items'] as $key => $item)
        {
            echo sprintf("Avail %s %s %s\n",$key,$item['date'],$item['label']);
          //print_r($item['choices']);
        }
        foreach($plan as $key => $item)
        {
            echo sprintf("Plan Item %-20s %s\n",$key,$item['type']);
        }
        echo sprintf("Plan Item Count %d\n",count($plan));
        
        $refereeLevel = $plan['refereeLevel'];
        echo sprintf("Referee Level '%s' %s\n",$refereeLevel['label'],$refereeLevel['default']);
        
        $comfortLevelCenter = $plan['comfortLevelCenter'];
        echo sprintf("Comfort Level Center '%s' %s\n",$comfortLevelCenter['label'],$comfortLevelCenter['default']);
    }
 }
?>
