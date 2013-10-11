<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nils
 * Date: 10/10/13
 * Time: 9:54 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class TextfieldFormElementDefinition extends FormElementDefinition
{

    protected $size = 'L';


    public function setSize($size)
    {
        if (in_array($size, array( 'S', 'M', 'L', 'XL', 'XXL' )))
        {
            $this->size = $size;
        }
        else
        {
            throw  new CMDLParserException('', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }

    }


    public function getSize()
    {
        return $this->size;
    }

}
