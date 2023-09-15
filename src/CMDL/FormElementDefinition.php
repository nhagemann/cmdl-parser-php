<?php

namespace CMDL;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class FormElementDefinition
{
    protected $elementType = null;

    protected $name = null;

    protected $label = null;

    protected $defaultValue = null;

    protected $help = null;

    protected $hint = null;

    protected $info = null;

    protected $placeholder = null;

    protected $mandatory = false;

    protected $unique = false;

    protected $protected = false;

    protected $params = [];

    protected $lists = [];

    protected $maxValueLength = 255;

    protected $insertedByInsert = false;

    public function __construct($name = null, $params = [], $lists = [])
    {
        $this->setName($name);
        $this->params = $params;
        $this->lists  = $lists;
    }

    public function getFormElementType()
    {
        return $this->elementType;
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

    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function markMandatory()
    {
        $this->mandatory = true;
    }

    public function isMandatory()
    {
        return (bool) $this->mandatory;
    }

    public function markUnique()
    {
        $this->unique = true;
    }

    public function isUnique()
    {
        return (bool) $this->unique;
    }

    public function markProtected()
    {
        $this->protected = true;
    }

    public function isProtected()
    {
        return (bool) $this->protected;
    }

    public function hasParam($nr)
    {
        return isset($this->params[($nr - 1)]);
    }

    public function hasList($nr)
    {
        return isset($this->lists[($nr - 1)]);
    }

    public function getLists()
    {
        return $this->lists;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($nr)
    {
        if ($this->hasParam($nr)) {
            return $this->params[($nr - 1)];
        }

        return null;
    }

    public function getList($nr)
    {
        if ($this->hasList($nr)) {
            return $this->lists[($nr - 1)];
        }

        return null;
    }

    public function getMaxValueLength()
    {
        return $this->maxValueLength;
    }

    public function setHelp($help)
    {
        $this->help = $help;
    }

    public function getHelp()
    {
        return $this->help;
    }

    public function setHint($hint)
    {
        $this->hint = $hint;
    }

    public function getHint()
    {
        return $this->hint;
    }

    public function setInfo($info)
    {
        $this->info = $info;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setInsertedByInsert($insertName)
    {
        $this->insertedByInsert = $insertName;
    }

    public function isInsertedByInsert()
    {
        return (bool) $this->insertedByInsert;
    }

    public function getInsertedByInsertName()
    {
        return $this->insertedByInsert;
    }
}
