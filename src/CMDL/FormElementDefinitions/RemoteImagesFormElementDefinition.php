<?php

namespace CMDL\FormElementDefinitions;


class RemoteImagesFormElementDefinition extends ImagesFormElementDefinition
{

    protected $elementType = 'remote-images';

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
