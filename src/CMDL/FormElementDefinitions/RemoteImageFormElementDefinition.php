<?php

namespace CMDL\FormElementDefinitions;

class RemoteImageFormElementDefinition extends ImageFormElementDefinition
{
    protected $elementType = 'remote-image';

    protected $repositoryUrl = null;


    public function setRepositoryUrl($repositoryUrl)
    {
        $this->repositoryUrl = $repositoryUrl;
    }//end setRepositoryUrl()


    public function getRepositoryUrl()
    {
        return $this->repositoryUrl;
    }//end getRepositoryUrl()
}//end class
