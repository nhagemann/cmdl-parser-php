<?php

namespace CMDL\FormElementDefinitions;

use CMDL\CMDLParserException;

class SourceCodeFormElementDefinition extends TextareaFormElementDefinition
{
    protected $elementType = 'sourcecode';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;

    protected $type = null;

    public function __construct($name, $params = [], $lists = [])
    {
        if (!isset($params[0])) {
            throw new CMDLParserException('Missing mandatory parameter type for form element sourcecode.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        $this->setType($params[0]);

        if (isset($params[1])) {
            $this->setRows($params[1]);
        }

        if (isset($params[2])) {
            $this->setSize($params[2]);
        }

        parent::__construct($name, $params, $lists);
    }

    public function setType($type)
    {
        $this->type = strtolower($type);
    }

    public function getType()
    {
        return $this->type;
    }
}
