<?php

namespace CMDL;

class CMDLParserException extends \Exception
{
    public const CMDL_FILE_NOT_FOUND           = 1;
    public const CMDL_VIEW_NOT_DEFINED         = 2;
    public const CMDL_CLIPPING_NOT_DEFINED     = 3;
    public const CMDL_UNKNOWN_DATATYPE         = 4;
    public const CMDL_UNKNOWN_FORMELEMENT_TYPE = 5;
    public const CMDL_FORMELEMENT_NOT_FOUND    = 6;
    public const CMDL_INVALID_OPTION_VALUE     = 7;
    public const CMDL_UNKNOWN_PROPERTY         = 8;
    public const CMDL_UNKNOWN_ANNOTATION       = 9;
    public const CMDL_MISSING_MANDATORY_PARAM  = 10;
}
