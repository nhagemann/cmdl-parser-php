<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class PasswordFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'password';

    protected $type = 'md5-salted';


    public function __construct($name, $params = array(), $lists = array())
    {
        if (isset($params[0]))
        {
            $this->setType($params[0]);
        }

        parent::__construct($name, $params, $lists);
    }


    public function setType($type)
    {
        if (in_array($type, array( 'plaintext', 'md5', 'md5-salted', 'sha1', 'sha1-salted' )))
        {
            $this->type = $type;
        }
        else
        {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of plaintext, md5, md5-salted, sha1, sha1-salted.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }

    }


    public function getType()
    {
        return $this->type;
    }

}
