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


    public function __construct($name=null)
    {
        $this->setName($name);
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

}