<?php
namespace Cerad\Bundle\ProjectBundle\Model;

// Not currently used
interface ProjectRepositoryInterface
{
    public function find($id);
    
    public function findAll();
    public function findAllByStatus($status);
    
    public function findOneBySlug($slug);
}
?>
