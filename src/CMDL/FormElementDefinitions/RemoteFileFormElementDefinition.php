<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class RemoteFileFormElementDefinition extends FileFormElementDefinition
{

    protected $elementType = 'remote-file';

    protected $repositoryUrl = null;


    public function setRepositoryUrl($repositoryUrl)
    {
        $this->repositoryUrl = $repositoryUrl;
    }


    public function getRepositoryUrl()
    {
        return $this->repositoryUrl;
    }

}
