<?php

namespace CMDL;

use CMDL\DataTypeDefinition;
use CMDL\CMDLParserException;


class ConfigTypeDefinition extends DataTypeDefinition
{

    protected $operations = array( 'list', 'get', 'update', 'revision' );

    protected $timeShiftable = false;


    public function setOperations($operations)
    {
        foreach ($operations as $operation)
        {
            if (!in_array($operation, array( 'list', 'get', 'update', 'revision' )))
            {
                throw new CMDLParserException('Invalid operation settings ("' . $operation . '") for config type ' . rtrim(' ' . $this->getName()) . '. Must be one of list,get,update,revision.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
            }
        }

        $this->operations = $operations;
    }


    public function hasListOperation()
    {
        return in_array('list', $this->operations);
    }


    public function hasGetOperation()
    {
        return in_array('get', $this->operations);
    }


    public function hasUpdateOperation()
    {
        return in_array('update', $this->operations);
    }


    public function hasRevisionOperations()
    {
        return in_array('revision', $this->operations);
    }


    public function setTimeShiftable($timeShiftable)
    {
        $this->timeShiftable = $timeShiftable;
    }


    public function isTimeShiftable()
    {
        return $this->timeShiftable;
    }
}
