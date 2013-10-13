<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nils
 * Date: 10/10/13
 * Time: 9:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CMDL;

class FormElementDefinition
{

    protected $name = null;

    protected $label = null;

    protected $mandatory = false;
    protected $unique = false;

    protected $params = array();
    protected $lists = array();


    public function __construct($name = null, $params = array(), $lists = array())
    {
        $this->setName($name);
        $this->params = $params;
        $this->lists  = $lists;
    }


    public function setName($name)
    {
        $this->name = $name;
    }


    public function getName()
    {
        return $this->name;
    }


    public function setLabel($label)
    {
        $this->label = $label;
    }


    public function getLabel()
    {
        return $this->label;
    }


    public function markMandatory()
    {
        $this->mandatory = true;
    }


    public function isMandatory()
    {
        return (boolean)$this->mandatory;
    }


    public function markUnique()
    {
        $this->unique = true;
    }


    public function isUnique()
    {
        return (boolean)$this->unique;
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