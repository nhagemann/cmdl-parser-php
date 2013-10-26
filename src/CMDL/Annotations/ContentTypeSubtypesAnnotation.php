<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class ContentTypeSubtypesAnnotation extends Annotation
{

    protected $annotationType = 'subtypes';


    public function apply()
    {

        if ($this->hasParam(1))
        {
            if ($this->getParam(1) != 'none')
            {
                throw new CMDLParserException('Invalid parameter value '.$this->getParam(1) .' for annotation @subtypes.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
            }
        }
        else
        {
            if (!$this->hasList(1))
            {
                throw new CMDLParserException('Missing mandatory values list for annotation @subtypes.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
            }
            $this->contentTypeDefinition->setSubtypes($this->getList(1));
        }

        return $this->contentTypeDefinition;
    }

}
