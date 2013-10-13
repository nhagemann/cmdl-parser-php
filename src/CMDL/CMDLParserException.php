<?php
namespace CMDL;

class CMDLParserException extends \Exception
{

    const CMDL_FILE_NOT_FOUND           = 1;
    const CMDL_VIEW_NOT_DEFINED         = 2;
    const CMDL_INSERTION_NOT_DEFINED    = 3;
    const CMDL_UNKNOWN_FORMELEMENT_TYPE = 4;
    const CMDL_UNKNOWN_FIELD_TYPE       = 5;
    const CMDL_INVALID_OPTION_VALUE     = 6;

}