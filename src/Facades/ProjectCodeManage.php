<?php


namespace Qylinfly\Permission\Facades;


class ProjectCodeManage
{

    public $project_code = 'star';
    public function setCode($type){
        $this->project_code = $type;
    }
    public function code(){
        return $this->project_code;
    }
}
