<?php

namespace CMDL;

use CMDL\Annotations\ContentTypeNameAnnotation;
use CMDL\Annotations\ContentTypeStatusAnnotation;
use CMDL\Annotations\ContentTypeSubtypesAnnotation;
use CMDL\Annotations\CustomAnnotation;
use CMDL\Annotations\DataTypeDescriptionAnnotation;
use CMDL\Annotations\DataTypeLanguagesAnnotation;
use CMDL\Annotations\DataTypeSortableAnnotation;
use CMDL\Annotations\DataTypeTimeShiftableAnnotation;
use CMDL\Annotations\DataTypeTitleAnnotation;
use CMDL\Annotations\DataTypeWorkspacesAnnotation;
use CMDL\Annotations\FormElementCollectionHiddenPropertiesAnnotation;
use CMDL\Annotations\FormElementDefaultValueAnnotation;
use CMDL\Annotations\FormElementHelpAnnotation;
use CMDL\Annotations\FormElementHintAnnotation;
use CMDL\Annotations\FormElementInfoAnnotation;
use CMDL\Annotations\FormElementPlaceholderAnnotation;
use CMDL\Annotations\InsertAnnotation;
use CMDL\FormElementDefinitions\CheckboxFormElementDefinition;
use CMDL\FormElementDefinitions\CMDLFormElementDefinition;
use CMDL\FormElementDefinitions\ColorFormElementDefinition;
use CMDL\FormElementDefinitions\CustomFormElementDefinition;
use CMDL\FormElementDefinitions\DateFormElementDefinition;
use CMDL\FormElementDefinitions\EmailFormElementDefinition;
use CMDL\FormElementDefinitions\FileFormElementDefinition;
use CMDL\FormElementDefinitions\GeolocationFormElementDefinition;
use CMDL\FormElementDefinitions\HeadlineFormElementDefinition;
use CMDL\FormElementDefinitions\HTMLFormElementDefinition;
use CMDL\FormElementDefinitions\ImageFormElementDefinition;
use CMDL\FormElementDefinitions\LinkFormElementDefinition;
use CMDL\FormElementDefinitions\MarkdownFormElementDefinition;
use CMDL\FormElementDefinitions\MultiReferenceFormElementDefinition;
use CMDL\FormElementDefinitions\MultiSelectionFormElementDefinition;
use CMDL\FormElementDefinitions\NumberFormElementDefinition;
use CMDL\FormElementDefinitions\PasswordFormElementDefinition;
use CMDL\FormElementDefinitions\PrintFormElementDefinition;
use CMDL\FormElementDefinitions\RangeFormElementDefinition;
use CMDL\FormElementDefinitions\ReferenceFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteFileFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteImageFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteMultiReferenceFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteMultiSelectionFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteReferenceFormElementDefinition;
use CMDL\FormElementDefinitions\RemoteSelectionFormElementDefinition;
use CMDL\FormElementDefinitions\RichtextFormElementDefinition;
use CMDL\FormElementDefinitions\SectionEndFormElementDefinition;
use CMDL\FormElementDefinitions\SectionStartFormElementDefinition;
use CMDL\FormElementDefinitions\SelectionFormElementDefinition;
use CMDL\FormElementDefinitions\SequenceFormElementDefinition;
use CMDL\FormElementDefinitions\SourceCodeFormElementDefinition;
use CMDL\FormElementDefinitions\TabEndFormElementDefinition;
use CMDL\FormElementDefinitions\TableFormElementDefinition;
use CMDL\FormElementDefinitions\TabNextFormElementDefinition;
use CMDL\FormElementDefinitions\TabStartFormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\TimeFormElementDefinition;
use CMDL\FormElementDefinitions\TimestampFormElementDefinition;

class Parser
{
    public static $superProperties = array('name', 'status', 'subtype', 'position', 'parent');

    public static function parseCMDLFile(string $filename, ?string $dataTypeName = null, ?string $dataTypeTitle = null, string $dataType = 'content'): DataTypeDefinition
    {
        if (realpath($filename)) {
            $s = file_get_contents($filename);
            if ($s) {
                return self::parseCMDLString($s, $dataTypeName, $dataTypeTitle, $dataType);
            }
        }

        throw new CMDLParserException('', CMDLParserException::CMDL_FILE_NOT_FOUND);
    }

    public static function parseCMDLString($cmdl, $dataTypeName = null, $dataTypeTitle = null, $dataType = 'content')
    {
        switch ($dataType) {
            case 'content':
                $dataTypeDefinition = new ContentTypeDefinition();
                break;
            case 'config':
                $dataTypeDefinition = new ConfigTypeDefinition();
                break;
            case 'data':
                $dataTypeDefinition = new DataTypeDefinition();
                break;
            default:
                throw new CMDLParserException(
                    'Unknown data type ' . $dataType . '. Must be one of content,config,data.',
                    CMDLParserException::CMDL_UNKNOWN_DATATYPE
                );
        }

        $dataTypeDefinition->setCMDL($cmdl);

        $currentFormElementDefinitionCollection = new ViewDefinition('default', $dataTypeDefinition);
        $dataTypeDefinition->addViewDefinition($currentFormElementDefinitionCollection);

        $cmdl = preg_split('/$\R?^/m', $cmdl);

        $sectionOpened   = false;
        $tabOpened       = false;
        $currentTabLabel = '';

        foreach ($cmdl as $line) {
            $line = trim($line);

            if (isset($line[0])) {
                switch ($line[0]) {
                    case '@': // annotation
                        $dataTypeDefinition = self::parseAnnotation(
                            $dataTypeDefinition,
                            $currentFormElementDefinitionCollection,
                            $line
                        );

                        break;
                    case '#': // comment
                        break;
                    case ' ': // ignore empty lines
                    case '':
                        break;
                    case '=': // start of a view definition
                        if ($tabOpened) { // tab has not been closed
                            self::closeTab($currentFormElementDefinitionCollection, $currentTabLabel);
                            $tabOpened = false;
                        }

                        $viewName = Util::generateValidIdentifier(trim($line, '-'));

                        if ($viewName == 'default') {
                            // get the already created and added definition of view "default"
                            $currentFormElementDefinitionCollection = $dataTypeDefinition->getViewDefinition('default');
                        } else {
                            $currentFormElementDefinitionCollection = new ViewDefinition($viewName);
                            $dataTypeDefinition->addViewDefinition($currentFormElementDefinitionCollection);
                        }
                        break;
                    case '+': // start of an clipping definition
                        if ($tabOpened) { // tab has not been closed
                            self::closeTab($currentFormElementDefinitionCollection, $currentTabLabel);
                            $tabOpened = false;
                        }

                        $clippingName                           = Util::generateValidIdentifier($line);
                        $currentFormElementDefinitionCollection = new ClippingDefinition($clippingName);
                        $dataTypeDefinition->addClippingDefinition($currentFormElementDefinitionCollection);
                        break;
                    case '[':
                        if (substr($line, 0, 3) == '[[[') { // it's a tab
                            $currentTabLabel = rtrim(substr($line, 3), ']');

                            if ($tabOpened === true) { // There's already an open tab
                                $formElementDefinition = new TabNextFormElementDefinition();
                            } else {
                                $formElementDefinition = new TabStartFormElementDefinition();
                            }

                            $formElementDefinition->setLabel($currentTabLabel);
                            $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                            $tabOpened = true;
                        } elseif (substr($line, 0, 2) == '[[') { // it's a section
                            if ($sectionOpened === true) { // There's still an open section -> close it first
                                $formElementDefinition = new SectionEndFormElementDefinition();
                                $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                            }

                            $formElementDefinition = new SectionStartFormElementDefinition();

                            if (substr($line, -1) == '+') {
                                $line = substr($line, 0, -1);
                                $formElementDefinition->setOpened(true);
                            }

                            $formElementDefinition->setLabel(rtrim(substr($line, 2), ']'));
                            $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                            $sectionOpened = true;
                        } else // it's a headline
                        {
                            $formElementDefinition = new HeadlineFormElementDefinition();
                            $formElementDefinition->setLabel(rtrim(substr($line, 1), ']'));
                            $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                        }

                        break;
                    case ']':
                        if (substr($line, 0, 3) == ']]]') { // it's a tab
                            self::closeTab($currentFormElementDefinitionCollection, $currentTabLabel);
                            $tabOpened       = false;
                            $currentTabLabel = '';
                        } elseif ($sectionOpened === true) {
                            $formElementDefinition = new SectionEndFormElementDefinition();
                            $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                            $sectionOpened = false;
                        }

                        break;
                    case '>':
                        $formElementDefinition = new PrintFormElementDefinition();
                        $p                     = strpos($line, ' ');
                        $display               = '';
                        if ($p) {
                            $display = trim(substr($line, $p));
                        }
                        $formElementDefinition->setDisplay($display);
                        $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
                        break;
                    default:
                        $formElementDefinition = self::parseFormElementDefinition($line);

                        $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);

                        break;
                }
            }
        }

        if ($dataTypeName != '') {
            $dataTypeDefinition->setName($dataTypeName);
        }
        if ($dataTypeTitle != '') {
            $dataTypeDefinition->setTitle($dataTypeTitle);
        }

        if ($tabOpened) { // tab has not been closed
            self::closeTab($currentFormElementDefinitionCollection, $currentTabLabel);
        }

        return $dataTypeDefinition;
    }

    protected static function closeTab(FormElementDefinitionCollection $currentFormElementDefinitionCollection, $currentTabLabel)
    {
        $formElementDefinition = new TabEndFormElementDefinition();
        $formElementDefinition->setLabel($currentTabLabel);
        $currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);
    }

    public static function parseFormElementDefinition($line)
    {
        $line = trim($line);
        $name = Util::getTextBetweenChars($line, '{', '}');
        $line = Util::removeTextBetweenCharsIncludingDelimiters($line, '{', '}');

        $p = strpos($line, '=');

        if ($p) { // There could be no additional definition
            $title      = trim(substr($line, 0, $p));
            $onTheRight = trim(substr($line, $p + 1));
        } else {
            $title      = trim($line);
            $onTheRight = '';
        }

        if ($name) {
            $name = $name[0];
        } else {
            $name = Util::generateValidIdentifier($title);
        }
        //
        // extract type and qualifier
        //

        $type              = 'textfield';
        $typeWithQualifier = $type;
        $params            = array();
        $lists             = array();

        if ($onTheRight != '') {
            $p = strpos($onTheRight, ' ');

            if ($p) { // type could be the only content of $onetheright (i.e. no parameters given)
                $typeWithQualifier = substr($onTheRight, 0, $p);
                $onTheRight        = substr($onTheRight, $p + 1);

                $lists  = Util::extractLists($onTheRight);
                $params = Util::extractParams($onTheRight);
            } else {
                $typeWithQualifier = $onTheRight;
            }

            $typeWithQualifier = Util::generateValidIdentifier($typeWithQualifier, '!*§-');
            $type              = Util::generateValidIdentifier($typeWithQualifier, '-');
        }

        switch ($type) {
            case 'textfield':
                $formElementDefinition = new TextfieldFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setSize($params[0]);
                }
                break;
            case 'link':
                $formElementDefinition = new LinkFormElementDefinition($name, $params, $lists);
                break;
            case 'email':
                $formElementDefinition = new EmailFormElementDefinition($name, $params, $lists);
                break;
            case 'textarea':
                $formElementDefinition = new TextareaFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'richtext':
                $formElementDefinition = new RichtextFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'markdown':
                $formElementDefinition = new MarkdownFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'html':
                $formElementDefinition = new HTMLFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'cmdl':
                $formElementDefinition = new CMDLFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setRows($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setSize($params[1]);
                }
                break;
            case 'sourcecode':
                $formElementDefinition = new SourceCodeFormElementDefinition($name, $params, $lists);
                break;
            case 'password':
                $formElementDefinition = new PasswordFormElementDefinition($name, $params, $lists);
                break;
            case 'number':
                $formElementDefinition = new NumberFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setDigits($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setUnit($params[1]);
                }
                break;
            case 'range':
                $formElementDefinition = new RangeFormElementDefinition($name, $params, $lists);
                break;
            case 'checkbox':
                $formElementDefinition = new CheckboxFormElementDefinition($name);
                if (isset($params[0])) {
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
                if (isset($params[0])) {
                    $formElementDefinition->setType($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setInit($params[1]);
                }
                break;
            case 'date':
                $formElementDefinition = new DateFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setType($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setInit($params[1]);
                }
                break;
            case 'time':
                $formElementDefinition = new TimeFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setType($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setInit($params[1]);
                }
                break;
            case 'file':
                $formElementDefinition = new FileFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setPath($params[0]);
                }
                if (isset($lists[0])) {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'image':
                $formElementDefinition = new ImageFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setPath($params[0]);
                }
                if (isset($lists[0])) {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'remote-file':
                $formElementDefinition = new RemoteFileFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setRepositoryUrl($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setPath($params[1]);
                }
                if (isset($lists[0])) {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'remote-image':
                $formElementDefinition = new RemoteImageFormElementDefinition($name);
                if (isset($params[0])) {
                    $formElementDefinition->setRepositoryUrl($params[0]);
                }
                if (isset($params[1])) {
                    $formElementDefinition->setPath($params[1]);
                }
                if (isset($lists[0])) {
                    $formElementDefinition->setFileTypes($lists[0]);
                }
                break;
            case 'table':
                $lists                 = Util::extractLists($onTheRight, true);
                $formElementDefinition = new TableFormElementDefinition($name, $params, $lists);
                break;
            case 'color':
                $formElementDefinition = new ColorFormElementDefinition($name, $params, $lists);
                break;
            case 'geolocation':
                $formElementDefinition = new GeolocationFormElementDefinition($name, $params, $lists);
                break;
            case 'sequence':
                $formElementDefinition = new SequenceFormElementDefinition($name);
                if (isset($lists[0])) {
                    $formElementDefinition->setInserts($lists[0]);
                }
                break;
            case 'custom':
                $formElementDefinition = new CustomFormElementDefinition($name, $params, $lists);
                break;
            default:
                throw new CMDLParserException(
                    'Unknown form element type ' . $type . '.',
                    CMDLParserException::CMDL_UNKNOWN_FORMELEMENT_TYPE
                );
        }

        $formElementDefinition->setLabel($title);

        if (strstr($typeWithQualifier, '*') !== false) {
            $formElementDefinition->markMandatory();
        }
        if (strstr($typeWithQualifier, '!') !== false) {
            $formElementDefinition->markUnique();
        }
        if (strstr($typeWithQualifier, '§') !== false) {
            $formElementDefinition->markProtected();
        }

        return $formElementDefinition;
    }

    public static function parseAnnotation(
        DataTypeDefinition $dataTypeDefinition,
        FormElementDefinitionCollection $currentFormElementDefinitionCollection,
        $line
    ) {
        $p = strpos($line, ' ');

        if ($p) {
            $annotationName = trim(substr($line, 1, $p));
            $onTheRight     = substr($line, $p + 1);

            $lists          = Util::extractLists($onTheRight);
            $params         = Util::extractParams($onTheRight);
            $numericalLists = Util::extractLists($onTheRight, true);
        } else {
            $annotationName = substr($line, 1);
            $lists          = array();
            $params         = array();
            $numericalLists = array();
        }

        switch ($annotationName) {
            case 'title':
                $annotation = new DataTypeTitleAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'description':
                $annotation = new DataTypeDescriptionAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'name':
                $annotation = new ContentTypeNameAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'languages':
                $annotation = new DataTypeLanguagesAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'status':
                $annotation = new ContentTypeStatusAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'subtypes':
                $annotation = new ContentTypeSubtypesAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'workspaces':
                $annotation = new DataTypeWorkspacesAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'sortable':
                $annotation = new DataTypeSortableAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'time-shiftable':
                $annotation = new DataTypeTimeShiftableAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'default-value':
                $annotation = new FormElementDefaultValueAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'help':
                $annotation = new FormElementHelpAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'hint':
                $annotation = new FormElementHintAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'info':
                $annotation = new FormElementInfoAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'placeholder':
                $annotation = new FormElementPlaceholderAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'hidden-properties':
                $annotation = new FormElementCollectionHiddenPropertiesAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'insert':
                $annotation = new InsertAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists
                );
                break;
            case 'custom':
                $annotation = new CustomAnnotation(
                    $dataTypeDefinition,
                    $currentFormElementDefinitionCollection,
                    $params,
                    $lists,
                    $numericalLists
                );
                break;

            default:
                throw new CMDLParserException(
                    'Unknown annotation ' . $annotationName . '.',
                    CMDLParserException::CMDL_UNKNOWN_ANNOTATION
                );
        }

        $dataTypeDefinition = $annotation->apply();

        return $dataTypeDefinition;
    }
}
