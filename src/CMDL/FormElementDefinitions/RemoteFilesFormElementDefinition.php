<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class RemoteFilesFormElementDefinition extends FilesFormElementDefinition
{

    protected $elementType = 'remote-files';

    protected $repositoryUrl = null;

    protected $maxValueLength = 256;


    public function setRepositoryUrl($repositoryUrl)
    {
        $this->repositoryUrl = $repositoryUrl;
    }


    public function getRepositoryUrl()
    {
        return $this->repositoryUrl;
    }

}
