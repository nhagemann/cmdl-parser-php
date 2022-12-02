<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class DataTypeLanguagesAnnotation extends Annotation
{
    protected $annotationType = 'languages';


    public function apply()
    {

        if ($this->hasParam(1)) {
            if ($this->getParam(1) != 'none') {
                throw new CMDLParserException('Invalid parameter value ' . $this->getParam(1) . ' for annotation @languages.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
            }

            $this->dataTypeDefinition->setLanguages(['default' => 'None']);
        } else {
            if (!$this->hasList(1)) {
                throw new CMDLParserException('Missing mandatory values list for annotation @languages.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
            }

            $this->dataTypeDefinition->setLanguages($this->getList(1));
        }

        return $this->dataTypeDefinition;
    }
}
