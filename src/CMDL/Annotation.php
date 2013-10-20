<?php

namespace CMDL;

use CMDL\ContentTypeDefinition;

class Annotation
{

    protected $annotationType = null;

    protected $contentTypeDefinition = null;
    protected $params = array();
    protected $lists = array();


    public function __construct(ContentTypeDefinition $contentTypeDefinition, $params = array(), $lists = array())
    {
        $this->contentTypeDefinition = $contentTypeDefinition;
        $this->params                = $params;
        $this->lists                 = $lists;
    }


    public function apply()
    {
        return $this->contentTypeDefinition;
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