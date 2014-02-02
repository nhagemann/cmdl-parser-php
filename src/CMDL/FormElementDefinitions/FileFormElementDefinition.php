<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class FileFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'file';

    protected $path = '/';

    protected $fileTypes = array();


    public function setFileTypes($fileTypes)
    {
        $this->fileTypes = $fileTypes;
    }


    public function getFileTypes()
    {
        return $this->fileTypes;
    }


    public function setPath($path)
    {
        $this->path = $path;
    }


    public function getPath()
    {
        return $this->path;
    }

}
