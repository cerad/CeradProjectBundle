<?php

namespace Cerad\Bundle\ProjectBundle\Model;

// Second try at making a value object
class ProjectAssignor
{
    protected $name;
    protected $email;
    protected $phone;
    protected $submit;
    protected $prefix; // For emails
    
    // Too bad can't use arrays in class constants.
    // Suppose could use reflection
    protected $map = array('name','email','phone','submit','prefix');
    
    public function __construct($data = null)
    {
        if (!$data) return;
        
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
    public function getName  () { return $this->name;   }
    public function getEmail () { return $this->email;  }
    public function getPhone () { return $this->phone;  }
    public function getSubmit() { return $this->submit; }
    public function getPrefix() { return $this->prefix; }
    
    public function setName  ($value) { $this->name   = $value; return $this; }
    public function setEmail ($value) { $this->email  = $value; return $this; }
    public function setPhone ($value) { $this->phone  = $value; return $this; }
    public function setSubmit($value) { $this->submit = $value; return $this; }
    public function setPrefix($value) { $this->prefix = $value; return $this; }
    
}

