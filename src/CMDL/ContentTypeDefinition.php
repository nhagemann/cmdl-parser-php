<?php

namespace CMDL;

use CMDL\CMDLParserException;
use CMDL\ViewDefinition;
use CMDL\InsertionDefinition;

class ContentTypeDefinition
{

    protected $cmdl = null;
    protected $views = array();
    protected $insertions = array();

    public function setCMDL($s)
    {
        $this->cmdl =$s;
    }

    public function getCMDL()
    {
        return $this->cmdl;
    }

    public function getViewDefinition($name = 'default')
    {
        if (array_key_exists($name,$this->views))
        {
            return $this->views[$name];
        }

        throw new CMDLParserException('',CMDLParserException::CMDL_VIEW_NOT_DEFINED);
    }

    public function addViewDefinition(ViewDefinition $definition)
    {
        $this->views[$definition->getName()]= $definition;
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