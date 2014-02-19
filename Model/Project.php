<?php

namespace Cerad\Bundle\ProjectBundle\Model;

class Project
{
    protected $key;
    
    protected $slug;
    protected $slugPrefix;
    protected $role;
                
    protected $fed;     // AYSO
    protected $fedOrg;  // USSF_AL
    protected $fedRole; // AYSOV
    
    protected $name;
    protected $desc;
    
    protected $season;
    protected $sport;
    protected $domain;
    protected $domainSub;
    
    protected $status;
    
    protected $assignor;
    protected $dates;  // Array of dates
    
    protected $plan;
    protected $search;
    
    public function getId        () { return $this->id;         }
    public function getKey       () { return $this->key;        }
    public function getSlug      () { return $this->slug;       }
    public function getSlugPrefix() { return $this->slugPrefix; }

    public function getStatus() { return $this->status;   }
    
    public function getFed    () { return $this->fed;     }
    public function getFedOrg () { return $this->fedOrg;  }
    public function getFedRole() { return $this->fedRole; }
    
    public function getName() { return $this->name; }
    public function getDesc() { return $this->desc; }

    
    // Stored as arrays
    public function getSearch() { return $this->search; }
    
    public function __construct($meta = null)
    {   
        if ($meta) $this->setMeta($meta);
    }
    public function isActive() { return ('Active' == $this->status) ? true: false; }
    
    /* =======================================================
     * Assignore are value objects
     * Stored internally as arrays
     * Client needs to remember to do a setAssignor after changing data
     * 
     * Keep it real simple for now, 
     * Doing multiple getAssignor/setAssignor will cause data sync issues
     */
    public function getRawAssignor() { return $this->assignor; }
    public function getAssignor() 
    { 
        return new ProjectAssignor($this);
    }
    public function setAssignor(ProjectAssignor $assignor)
    {
        $this->assignor = $assignor->getData();
    }
    /* =======================================================
     * Dates is an array of ProjectDates
     * Should they be keyed or not?
     */
    public function getRawDates() { return $this->dates; }
    public function getDates()    { return new ProjectDates($this); }

    /* =======================================================
     * Plan is an array, no wrapping in an object for now but
     * Want to tweak it to merge in date information
     */
    public function getRawPlan() { return $this->plan; }
    public function getPlan()    { return new ProjectPlan($this); }
        
     /* =======================================================
     * The meta subsystem allows loading from yaml
     */
    protected $meta;
    public function getMeta() { return $this->meta; }
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
        
        foreach($meta as $name => $value)
        {
            $this->$name = $value;
        }   
    }
}
?>
