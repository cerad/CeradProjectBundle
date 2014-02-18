<?php

namespace Cerad\Bundle\ProjectBundle\Model;

// Second try at making a value object
class ProjectDate
{
    protected $date;  //'2014-04-05'
    protected $label;
    protected $avail;
    protected $search;
    protected $lodging;
    
    // Too bad can't use arrays in class constants.
    // Suppose could use reflection
    protected $map = array('date','label','avail','search','lodging');
    
    public function __construct(array $data)
    {
        foreach($this->map as $key)
        {
            $this->$key = isset($data[$key]) ? $data[$key] : null;
        }
    }
    public function getData()
    {
        $data = array();
        foreach($this->map as $key) { $data[$key] = $this->$key; }
        return $data;
    }
    public function getDate   () { return $this->date;    }
    public function getLabel  () { return $this->label;   }
    public function getAvail  () { return $this->avail;   }
    public function getSearch () { return $this->search;  }
    public function getLodging() { return $this->lodging; }
    
    public function setDate   ($value) { $this->date    = $value; return $this; }
    public function setLabel  ($value) { $this->label   = $value; return $this; }
    public function setAvail  ($value) { $this->avail   = $value; return $this; }
    public function setSearch ($value) { $this->search  = $value; return $this; }
    public function setLodging($value) { $this->lodging = $value; return $this; }
}