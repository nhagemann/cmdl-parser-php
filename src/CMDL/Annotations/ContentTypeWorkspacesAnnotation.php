<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class ContentTypeWorkspacesAnnotation extends Annotation
{

    protected $annotationType = 'workspaces';


    public function apply()
    {

        if (!$this->hasList(1))
        {
            throw new CMDLParserException('Missing mandatory list for annotation @workspaces.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        $this->contentTypeDefinition->setWorkspaces($this->getList(1));

        return $this->contentTypeDefinition;
    }

}
