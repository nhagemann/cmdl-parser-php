<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;
use CMDL\ContentTypeAnnotation;

class ContentTypeStatusAnnotation extends ContentTypeAnnotation
{
    protected $annotationType = 'status';


    public function apply()
    {

        if ($this->hasParam(1)) {
            if ($this->getParam(1) != 'none') {
                throw new CMDLParserException('Invalid parameter value ' . $this->getParam(1) . ' for annotation @status.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
            }
        } else {
            if (!$this->hasList(1)) {
                throw new CMDLParserException('Missing mandatory values list for annotation @status.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
            }

            $this->dataTypeDefinition->setStatusList($this->getList(1));
        }

        return $this->dataTypeDefinition;
    }
}
