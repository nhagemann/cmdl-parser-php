<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class FileFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'file';

    protected $path = '/';

    protected $fileTypes = [];


    public function setFileTypes($fileTypes)
    {
        $this->fileTypes = $fileTypes;
    }//end setFileTypes()


    public function getFileTypes()
    {
        return $this->fileTypes;
    }//end getFileTypes()


    public function setPath($path)
    {
        $this->path = $path;
    }//end setPath()


    public function getPath()
    {
        return $this->path;
    }//end getPath()
}//end class
