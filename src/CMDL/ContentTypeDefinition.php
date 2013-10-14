<?php

namespace CMDL;

use CMDL\CMDLParserException;
use CMDL\ClippingDefinition;
use CMDL\InsertionDefinition;

class ContentTypeDefinition
{

    protected $cmdl = null;
    protected $clippings = array();
    protected $insertions = array();

    public function setCMDL($s)
    {
        $this->cmdl =$s;
    }

    public function getCMDL()
    {
        return $this->cmdl;
    }

    public function getClippingDefinition($name = 'default')
    {
        if (array_key_exists($name,$this->clippings))
        {
            return $this->clippings[$name];
        }

        throw new CMDLParserException('',CMDLParserException::CMDL_CLIPPING_NOT_DEFINED);
    }

    public function addClippingDefinition(ClippingDefinition $definition)
    {
        $this->clippings[$definition->getName()]= $definition;
    }


    public function getInsertionDefinition($name)
    {
        if (array_key_exists($name,$this->insertions))
        {
            return $this->insertions[$name];
        }

        throw new CMDLParserException('',CMDLParserException::CMDL_INSERTION_NOT_DEFINED);
    }

    public function addInsertionDefinition(InsertionDefinition $definition)
    {
        $this->insertions[$definition->getName()]= $definition;
    }

}