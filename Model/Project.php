<?php
namespace Cerad\Bundle\ProjectBundle\Model;

/* =====================================================
 * TODO: Change fedId and fedRoleId to fed and fedRole
 */
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
    
    protected $info;
    protected $basic;
    protected $search;
    
    public function getId        () { return $this->id;         }
    public function getKey       () { return $this->key;        }
    public function getSlug      () { return $this->slug;       }
    public function getSlugPrefix() { return $this->slugPrefix; }

    public function getStatus  () { return $this->status;   }
    
    public function getFed     () { return $this->fed;     }
    public function getFedRole () { return $this->fedRole; }
    
    public function getDesc () { return $this->desc;  }
    public function getTitle() { return $this->title; }
    
    public function getSubmit()   { return $this->submit; }
    public function getPrefix()   { return $this->prefix; }
    public function getAssignor() { return $this->assignor; }
    
    // Stored as arrays
    public function getInfo  () { return $this->info;  } // Maybe pull from member variables
    public function getBasic () { return $this->basic; }
    public function getSearch() { return $this->search; }
    
    public function __construct($meta = null)
    {   
        if ($meta) $this->setMeta($meta);
    }
    public function isActive() { return ('Active' == $this->status) ? true: false; }
    
    /* =======================================================
     * The meta subsystem allows loading from yaml
     */
    protected $meta;
    public function getMeta() { return $this->meta; }
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
        
        $info = $meta['info'];
        
        foreach($info as $propName => $propValue)
        {
            $this->$propName = $propValue;
        }
        foreach($meta as $name => $value)
        {
            $this->$name = $value;
        }   
    }
}
?>
