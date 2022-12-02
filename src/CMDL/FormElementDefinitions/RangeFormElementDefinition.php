<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

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
    }//end __construct()


    /**
     * @param null $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }//end setMax()


    /**
     * @return null
     */
    public function getMax()
    {
        return $this->max;
    }//end getMax()


    /**
     * @param null $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }//end setMin()


    /**
     * @return null
     */
    public function getMin()
    {
        return $this->min;
    }//end getMin()


    /**
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }//end setStep()


    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }//end getStep()
}//end class
