<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class RemoteImageFormElementDefinition extends ImageFormElementDefinition
{

    protected $elementType = 'remote-image';

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
