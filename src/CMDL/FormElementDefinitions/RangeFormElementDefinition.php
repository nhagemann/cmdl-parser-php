<?php

namespace CMDL\FormElementDefinitions;

use CMDL\CMDLParserException;
use CMDL\FormElementDefinition;

class RangeFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'range';

    protected $min = null;

    protected $max = null;

    protected $step = 1;

    public function __construct($name, $params = [], $lists = [])
    {
        if (isset($params[0])) {
            $this->setMin($params[0]);
        }

        if (isset($params[1])) {
            $this->setMax($params[1]);
        }

        if (isset($params[2])) {
            $this->setStep($params[2]);
        }

        if ($this->min === null) {
            throw  new CMDLParserException('Missing mandatory parameter min for form element range.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        if ($this->max === null) {
            throw  new CMDLParserException('Missing mandatory parameter max for form element range.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        parent::__construct($name, $params, $lists);
    }

    /**
     * @param null $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @return null
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param null $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @return null
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }
}
