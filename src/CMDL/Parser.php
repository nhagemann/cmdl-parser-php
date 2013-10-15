<?php

namespace CMDL;

use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ClippingDefinition;
use CMDL\InsertionDefinition;
use CMDL\FormElementDefinition;

use CMDL\FormElementDefinitions\PrintFormElementDefinition;
use CMDL\FormElementDefinitions\HeadlineFormElementDefinition;
use CMDL\FormElementDefinitions\SectionStartFormElementDefinition;
use CMDL\FormElementDefinitions\SectionEndFormElementDefinition;

use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\FormElementDefinitions\RichtextFormElementDefinition;
use CMDL\FormElementDefinitions\MarkdownFormElementDefinition;
use CMDL\FormElementDefinitions\HTMLFormElementDefinition;
use CMDL\FormElementDefinitions\CMDLFormElementDefinition;

use CMDL\FormElementDefinitions\PasswordFormElementDefinition;

use CMDL\FormElementDefinitions\NumberFormElementDefinition;

use CMDL\FormElementDefinitions\CheckboxFormElementDefinition;
use CMDL\FormElementDefinitions\SelectionFormElementDefinition;
use CMDL\FormElementDefinitions\MultiSelectionFormElementDefinition;
use CMDL\FormElementDefinitions\ReferenceFormElementDefinition;
use CMDL\FormElementDefinitions\MultiReferenceFormElementDefinition;

use CMDL\FormElementDefinitions\RemoteSelectionFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteMultiSelectionFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteReferenceFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteMultiReferenceFormElementDefinition;

use CMDL\FormElementDefinitions\TimestampFormElementDefinition;
use CMDL\FormElementDefinitions\DateFormElementDefinition;

use CMDL\FormElementDefinitions\FileFormElementDefinition;
use CMDL\FormElementDefinitions\FilesFormElementDefinition;
use CMDL\FormElementDefinitions\ImageFormElementDefinition;
use CMDL\FormElementDefinitions\ImagesFormElementDefinition;

use CMDL\FormElementDefinitions\RemoteFileFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteFilesFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteImageFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteImagesFormElementDefinition;

use CMDL\FormElementDefinitions\TableFormElementDefinition;
use CMDL\FormElementDefinitions\ColorpickerFormElementDefinition;
use CMDL\FormElementDefinitions\GeolocationFormElementDefinition;

use CMDL\FormElementDefinitions\SequenceFormElementDefinition;

use CMDL\FormElementDefinitions\CustomFormElementDefinition;

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

        $currentFormElementDefinitionCollection = new ClippingDefinition('default');
        $contentTypeDefinition->addClippingDefinition($currentFormElementDefinitionCollection);

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
                    case '-': // start of a clipping definition
                        $clippingName = Util::generateValidIdentifier($line);

                        if ($clippingName == 'default')
                        {
                            // get the already created and added definition of clipping "default"
                            $currentFormElementDefinitionCollection = $contentTypeDefinition->getClippingDefinition('default');
                        }
                        else
                        {
                            $currentFormElementDefinitionCollection = new ClippingDefinition($clippingName);
                            $contentTypeDefinition->addClippingDefinition($currentFormElementDefinitionCollection);
                        }
                        break;
                    case '+': // start of an insertion definition
                        $insertionName                          = Util::generateValidIdentifier($line);
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
        $name = Util::getTextBetweenChars($line, '{', '}');
        $line = Util::removeTextBetweenCharsIncludingDelimiters($line, '{', '}');

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

        if ($name)
        {
            $name = $name[0];

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

                $lists  = Util::extractLists($onTheRight);
                $params = Util::extractParams($onTheRight);

            }
            else
            {
                $typeWithQualifier = $onTheRight;
            }

            $typeWithQualifier = Util::generateValidIdentifier($typeWithQualifier, '!*-');
            $type              = Util::generateValidIdentifier($typeWithQualifier, '-');

        }

        switch ($type)
        {
            case 'print':
                $formElementDefinition = new PrintFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setProperty($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setDisplay($params[1]);
                }
                break;
            case 'textfield':
                $formElementDefinition = new TextfieldFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setSize($params[0]);
                }
                break;
            case 'textarea':
                $formElementDefinition = new TextareaFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'richtext':
                $formElementDefinition = new RichtextFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'markdown':
                $formElementDefinition = new MarkdownFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'html':
                $formElementDefinition = new HTMLFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'cmdl':
                $formElementDefinition = new CMDLFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'password':
                $formElementDefinition = new PasswordFormElementDefinition($name,$params,$lists);
                break;
            case 'number':
                $formElementDefinition = new NumberFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setDigits($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setUnit($params[1]);
                }
                break;
            case 'checkbox':
                $formElementDefinition = new CheckboxFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setLegend($params[0]);
                }
                break;
            case 'selection':
                $formElementDefinition = new SelectionFormElementDefinition($name, $params, $lists);
                break;
            case 'multiselection':
                $formElementDefinition = new MultiSelectionFormElementDefinition($name, $params, $lists);
                break;
            case 'reference':
                $formElementDefinition = new ReferenceFormElementDefinition($name, $params, $lists);
                break;
            case 'multireference':
                $formElementDefinition = new MultiReferenceFormElementDefinition($name, $params, $lists);
                break;
            case 'remote-selection':
                $formElementDefinition = new RemoteSelectionFormElementDefinition($name, $params, $lists);
                break;
            case 'remote-multiselection':
                $formElementDefinition = new RemoteMultiSelectionFormElementDefinition($name, $params, $lists);
                break;
            case 'remote-reference':
                $formElementDefinition = new RemoteReferenceFormElementDefinition($name, $params, $lists);
                break;
            case 'remote-multireference':
                $formElementDefinition = new RemoteMultiReferenceFormElementDefinition($name, $params, $lists);
                break;
            case 'timestamp':
                $formElementDefinition = new TimestampFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setType($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setInit($params[1]);
                }
                break;
            case 'date':
                $formElementDefinition = new DateFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setType($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setInit($params[1]);
                }
                break;
            case 'file':
                $formElementDefinition = new FileFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setPath($params[0]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'files':
                $formElementDefinition = new FilesFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setPath($params[0]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'image':
                $formElementDefinition = new ImageFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setPath($params[0]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'images':
                $formElementDefinition = new ImagesFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setPath($params[0]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'remote-file':
                $formElementDefinition = new RemoteFileFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRepositoryUrl($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setPath($params[1]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'remote-files':
                $formElementDefinition = new RemoteFilesFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRepositoryUrl($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setPath($params[1]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'remote-image':
                $formElementDefinition = new RemoteImageFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRepositoryUrl($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setPath($params[1]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'remote-images':
                $formElementDefinition = new RemoteImagesFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setRepositoryUrl($params[0]);
                }
                if (isset($params[1]))
                {
                    $formElementDefinition->setPath($params[1]);
                }
                if (isset($lists[0]))
                {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'table':
                $formElementDefinition = new TableFormElementDefinition($name);
                if (isset($lists[0]))
                {
                    $formElementDefinition->setColumnHeadings($lists[0]);
                }
                if (isset($lists[1]))
                {
                    $formElementDefinition->setWidths($lists[1]);
                }
                break;
            case 'colorpicker':
                $formElementDefinition = new ColorpickerFormElementDefinition($name, $params, $lists);
                break;
            case 'geolocation':
                $formElementDefinition = new GeolocationFormElementDefinition($name, $params, $lists);
                break;
            case 'sequence':
                $formElementDefinition = new SequenceFormElementDefinition($name);
                if (isset($params[0]))
                {
                    $formElementDefinition->setInserts($lists[0]);
                }
                break;
            case 'custom':
                $formElementDefinition = new CustomFormElementDefinition($name, $params, $lists);
                break;
            default:
                throw new CMDLParserException('Unknown form element type ' . $type . '.', CMDLParserException::CMDL_UNKNOWN_FORMELEMENT_TYPE);

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