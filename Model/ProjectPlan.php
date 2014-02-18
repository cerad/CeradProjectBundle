<?php
namespace Cerad\Bundle\ProjectBundle\Model;

use Symfony\Component\Yaml\Yaml;

/* ==================================================
 * Seems to want to become a configurator
 */
class ProjectPlan implements \Iterator, \Countable, \ArrayAccess
{
    const PlanLodging      = 'lodging';
    const PlanAvailability = 'availability';
    
    protected $project;
    
    protected $items;
    protected $itemKeys;
    protected $itemCount;
    protected $itemPosition; // foreach
     
    public function __construct(Project $project)
    {
        $this->project = $project;
        
        $this->createItems();
    }
    protected function processLodging(&$items,$dates)
    {
        if (!isset($items[self::PlanLodging]));
        
        $lodging = array('type' => 'collection', 'items' => array());
        foreach($dates as $date)
        {
            if ($date->getLodging())
            {
                $label = $date->getLabel();
                $lodging['items'][$label] = array
                (
                    'type'    => 'radio',
                    'label'   => sprintf('Lodging %s Night',$label),
                    'date'    => $date->getDate(),
                    'default' => 'No',
                    'choices' => array('No' => 'No', 'Yes' => 'Yes'),
                );
            }
        }
        $items[self::PlanLodging] = $lodging;
    }
    protected function processAvailability(&$items,$dates)
    {
        if (!isset($items[self::PlanAvailability]));
        
        $data = array('type' => 'collection', 'items' => array());
        foreach($dates as $date)
        {
            if ($date->getAvail())
            {
                switch($date->getAvail())
                {
                case 'AllDay':
                    $choices = array
                    (
                        'None'          => 'None',
                        'AllDay'        => 'All Day', 
                        'MorningOnly'   => 'Morning Only', 
                        'AfternoonOnly' => 'Afternoon Only', 
                        'NotSure'       => 'Not Sure',
                    );
                    break;
                default:
                    $choices = array
                    (
                        'None'     => 'None',
                        'Evening'  => 'Kickoff 5PM', 
                        'NotSure'  => 'Not Sure',
                    );
                }
                $label = $date->getLabel();
                $data['items'][$label] = array
                (
                    'type'    => 'select',
                    'label'   => sprintf('Availability %s',$label),
                    'date'    => $date->getDate(),
                    'default' => 'None',
                    'choices' => $choices,
                );
            }
        }
        $items[self::PlanAvailability] = $data;
    }
    public function createItems()
    {
        $items = $this->project->getRawPlan(); // print_r($items); die();
        $dates = $this->project->getDates();
        
        $this->processLodging     ($items,$dates);
        $this->processAvailability($items,$dates);

        // TODO: Make filename configurable
        $itemDefaults = Yaml::parse(file_get_contents(__DIR__ . '/ProjectPlan.yml'));
        foreach($itemDefaults as $key => $itemDefault)
        {
            if (array_key_exists($key,$items))
            {
                $itemData = is_array($items[$key]) ? $items[$key] : array();
                $items[$key] = array_merge($itemDefault,$itemData);
            }
        }
        $this->items     = $items;
        $this->itemKeys  = array_keys($items);
        $this->itemCount = count($items);
        $this->itemPosition = 0;
    }
    public function getItems()
    {
      //if (!$this->items) $this->createItems();
        
        return $this->items;
    }
    /* =======================================================
     * Iterator - rewind is called first for foreach
     */
    public function rewind() 
    {
      //if (!$this->items) $this->createItems();
        
        $this->itemPosition = 0;
    }
    public function current()
    {
        return $this->items[$this->itemKeys[$this->itemPosition]];
    }
    public function key()  
    { 
        return $this->itemKeys[$this->itemPosition];   
    }
    public function next() 
    {
        $this->itemPosition++; 
    }
    public function valid()  
    { 
        return $this->itemPosition < $this->itemCount ? true : false;
    }
    public function count() { return $this->itemCount; }
    
    /* ======================================================
     * ArrayAccess
     */
    public function offsetExists($offset) { return isset($this->items[$offset]) ? true : false; }
    
    public function offsetGet($offset) { return $this->items[$offset]; }
    
    public function offsetSet($offset,$value) { $this->items[$offset] = $value; }
    
    public function offsetUnset($offset) { unset($this->items[$offset]); }
}
