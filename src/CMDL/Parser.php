<?php

namespace CMDL;

use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
use CMDL\InsertionDefinition;
use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\HeadlineFormElementDefinition;
use CMDL\FormElementDefinitions\SectionStartFormElementDefinition;
use CMDL\FormElementDefinitions\SectionEndFormElementDefinition;

use CMDL\Util;

class Parser
{

    public static function parseCMDLFile($filename)
    {
        if (realpath($filename))
        {
            $s = file_get_contents($filename);
            if ($s)
            {
                return self::parseCMDLString($s);
            }
        }

        throw new CMDLParserException('', CMDLParserException::CMDL_FILE_NOT_FOUND);
    }


    public static function parseCMDLString($cmdl)
    {
        $contentTypeDefinition = new ContentTypeDefinition();
        $contentTypeDefinition->setCMDL($cmdl);

        $currentFormElementDefinitionCollection = new ViewDefinition('default');
        $contentTypeDefinition->addViewDefinition($currentFormElementDefinitionCollection);

        $cmdl = explode(PHP_EOL, $cmdl);

        $sectionOpened = false;

        foreach ($cmdl AS $line)
        {
            if (isset($line[0]))
            {
                switch ($line[0])
                {
                    case '#': // comment
                        break;
                    case ' ': // ignore empty lines
                    case '':
                        break;
                    case '-': // start of a view definition
                        $viewName = Util::generateValidIdentifier($line);

                        if ($viewName == 'default')
                        {
                            // get the already created and added definition of view "default"
                            $currentFormElementDefinitionCollection = $contentTypeDefinition->getViewDefinition('default');
                        }
                        else
                        {
                            $currentFormElementDefinitionCollection = new ViewDefinition($viewName);
                            $contentTypeDefinition->addViewDefinition($currentFormElementDefinitionCollection);
                        }
                        break;
                    case '+': // start of an insertion definition
                        $insertionName = Util::generateValidIdentifier($line);
                        $currentFormElementDefinitionCollection = new InsertionDefinition($insertionName);
                        $contentTypeDefinition->addInsertionDefinition($currentFormElementDefinitionCollection);
                        break;
                    case '[':

                        if (substr($line, 1, 1) == '[') // it's a section
                        {

                            if ($sectionOpened == true) // There's still an open section -> close it first
                            {
                                $formElementDefinition = new SectionEndFormElementDefinition();
                                $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                            }

                            $formElementDefinition = new SectionStartFormElementDefinition();

                            if (substr($line, -1) == '+')
                            {
                                $line = substr($line, 0, -1);
                                $formElementDefinition->setOpened(true);
                            }

                            $formElementDefinition->setLabel(rtrim(substr($line, 2), ']'));
                            $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                            $sectionOpened = true;

                        }
                        else // it's a headline
                        {
                            $formElementDefinition = new HeadlineFormElementDefinition();
                            $formElementDefinition->setLabel(rtrim(substr($line, 1), ']'));
                            $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                        }

                        break;
                    case ']':
                        if ($sectionOpened == true)
                        {
                            $formElementDefinition = new SectionEndFormElementDefinition();
                            $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                        }

                        break;
                    default:

                        $formElementDefinition = self::parseFormElementDefinition($line);

                        $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);

                        break;

                }
            }
        }

        return $contentTypeDefinition;
    }


    public static function parseFormElementDefinition($line)
    {
        $line = trim($line);

        $p = strpos($line, '=');

        if ($p) // There could be no additional definition
        {
            $title      = trim(substr($line, 0, $p));
            $onTheRight = trim(substr($line, $p + 1));
        }
        else
        {
            $title      = trim($line);
            $onTheRight = '';
        }

        //
        // check for distinct property name
        //

        $name = Util::getTextBetweenChars($line, '{', '}');
        if ($name)
        {
            $name = $name[0];
            $line = Util::removeTextBetweenCharsIncludingDelimiters($line, '{', '}');
        }
        else
        {
            $name = Util::generateValidIdentifier($title);
        }
        //
        // extract type and qualifier
        //

        $type              = 'textfield';
        $typeWithQualifier = $type;
        $params            = array();
        $lists             = array();

        if ($onTheRight != '')
        {
            $p = strpos($onTheRight, ' ');

            if ($p) // type could be the only content of $onetheright (i.e. no parameters given)
            {
                $typeWithQualifier = substr($onTheRight, 0, $p);
                $onTheRight        = substr($onTheRight, $p + 1);

                $lists      = Util::extractLists($onTheRight);
                $params     = Util::extractParams($onTheRight);

            }
            else
            {
                $typeWithQualifier = $onTheRight;
                $onTheRight        = '';
            }

            $typeWithQualifier = Util::generateValidIdentifier($typeWithQualifier, '!*');
            $type              = Util::generateValidIdentifier($typeWithQualifier);

        }

        switch ($type)
        {
            case 'textfield':
                $formElementDefinition = new TextfieldFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setSize($params[0]);
                }
                break;
            default:
                throw new CMDLParserException('', CMDLParserException::CMDL_UNKNOWN_FIELD_TYPE);

                break;
        }

        $formElementDefinition->setLabel($title);

        if (strstr($typeWithQualifier, '*') !== false)
        {
            $formElementDefinition->markMandatory();

        }
        if (strstr($typeWithQualifier, '!') !== false)
        {
            $formElementDefinition->markUnique();
        }

        return $formElementDefinition;
    }
}