<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;
use CMDL\FormElementDefinitions\InsertFormElementDefinition;
use CMDL\DataTypeDefinition;

class CustomAnnotation extends Annotation
{

    protected $annotationType = 'custom';

    protected $type;


    public function __construct(DataTypeDefinition $dataTypeDefinition, $currentFormElementDefinitionCollection, $params = array(), $lists = array(), $numericalLists = array())
    {
        if (isset($params[0]))
        {
            $this->setType($params[0]);
        }
        else
        {
            throw  new CMDLParserException('Missing mandatory parameter "type" of annotation ' . $this->annotationType, CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        $remainingParams = array();

        foreach ($params as $k => $v)
        {
            $remainingParams[$k - 1] = $v;
        }

        parent::__construct($dataTypeDefinition, $currentFormElementDefinitionCollection, $remainingParams, $lists, $numericalLists);
    }


    public function apply()
    {

        $this->dataTypeDefinition->addCustomAnnotation($this);

        return $this->dataTypeDefinition;
    }


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}
