<?php

namespace CMDL;

class CMDLParserException extends \Exception
{
    const CMDL_FILE_NOT_FOUND           = 1;
    const CMDL_VIEW_NOT_DEFINED         = 2;
    const CMDL_CLIPPING_NOT_DEFINED     = 3;
    const CMDL_UNKNOWN_DATATYPE         = 4;
    const CMDL_UNKNOWN_FORMELEMENT_TYPE = 5;
    const CMDL_FORMELEMENT_NOT_FOUND    = 6;
    const CMDL_INVALID_OPTION_VALUE     = 7;
    const CMDL_UNKNOWN_PROPERTY         = 8;
    const CMDL_UNKNOWN_ANNOTATION       = 9;
    const CMDL_MISSING_MANDATORY_PARAM  = 10;
}
