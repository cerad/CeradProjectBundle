<?php
namespace Cerad\Bundle\ProjectBundle\Model;

class ProjectDates implements \Iterator
{
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
    protected function createItems()
    {
        $dates = $this->project->getRawDates();
        
        $items = array();
        foreach($dates as $date)
        {
            $projectDate = new ProjectDate($date);
            $items[$projectDate->getDate()] = $projectDate;
        }
        $this->items        = $items;
        $this->itemKeys     = array_keys($items);
        $this->itemCount    = count($items);
        $this->itemPosition = 0;
    }
    public function getItems() { return $this->items; }
    
    // Iterator
    public function current()
    {
        return $this->items[$this->itemKeys[$this->itemPosition]];
    }
    public function key()    { return $this->itemPosition; }
    public function next()   { $this->itemPosition++; }
    public function rewind() { $this->itemPosition = 0; }
    public function valid()  { return $this->itemPosition < $this->itemCount ? true : false; }
}
