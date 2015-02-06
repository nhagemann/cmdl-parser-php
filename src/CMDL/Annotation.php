<?php

namespace CMDL;

use CMDL\DataTypeDefinition;
use CMDL\FormElementDefinitionCollection;

class Annotation
{

    protected $annotationType = null;

    /**
     * @var DataTypeDefinition|null
     */
    protected $dataTypeDefinition = null;
    /**
     * @var FormElementDefinitionCollection|null
     */
    protected $currentFormElementDefinitionCollection = null;
    protected $params = array();
    protected $lists = array();


    public function __construct(DataTypeDefinition $dataTypeDefinition, $currentFormElementDefinitionCollection, $params = array(), $lists = array())
    {
        $this->dataTypeDefinition = $dataTypeDefinition;
        $this->currentFormElementDefinitionCollection = $currentFormElementDefinitionCollection;
        $this->params                = $params;
        $this->lists                 = $lists;
    }


    public function apply()
    {
        return $this->dataTypeDefinition;
    }


    public function hasParam($nr)
    {
        return isset($this->params[$nr - 1]);
    }


    public function hasList($nr)
    {
        return isset($this->lists[$nr - 1]);
    }


    public function getParam($nr)
    {

        if ($this->hasParam($nr))
        {
            return $this->params[$nr - 1];
        }

        return null;
    }


    public function getList($nr)
    {

        if ($this->hasList($nr))
        {
            return $this->lists[$nr - 1];
        }

        return null;
    }

}
