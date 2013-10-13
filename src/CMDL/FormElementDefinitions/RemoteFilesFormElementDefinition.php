<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class RemoteFilesFormElementDefinition extends FilesFormElementDefinition
{

    protected $elementType = 'remote-files';

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
