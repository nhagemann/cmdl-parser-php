<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ClippingDefinition;
use CMDL\FormElementDefinition;

class ParsingTest extends \PHPUnit_Framework_TestCase
{

    public function testFileNotFoundException()
    {
        $this->setExpectedException('CMDL\CMDLParserException');
        Parser::parseCMDLFile('tests/input/test-00.cmdl');
    }


    public function testFileFound()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $s = $contentTypeDefinition->getCMDL();

        $this->assertStringEqualsFile('tests/input/test-01.cmdl', $s);
        $this->assertInstanceOf('CMDL\ContentTypeDefinition', $contentTypeDefinition);

    }


    public function testClippingNotDefined()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $this->setExpectedException('CMDL\CMDLParserException');

        $contentTypeDefinition->getClippingDefinition('none');

    }


    public function testClippingsDefined()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');
        $this->assertInstanceOf('CMDL\ClippingDefinition', $clippingDefinition);
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('insert');
        $this->assertInstanceOf('CMDL\ClippingDefinition', $clippingDefinition);
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('edit');
        $this->assertInstanceOf('CMDL\ClippingDefinition', $clippingDefinition);

    }


    public function testFieldsReturned()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');

        /* @var FormElementDefinition */
        $formElementDefinition = $clippingDefinition->getFormElementDefinition('name');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);
    }


    public function testHeadlinesExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-02.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');

        $formElements = $clippingDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[4]);

    }


    public function testSectionsExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-03.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');

        $formElements = $clippingDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[4]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[5]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[6]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[7]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[8]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[9]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[9]);

    }


    public function testInsertionsExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-04.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');
        $fields             = $clippingDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('edit');
        $fields             = $clippingDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        /* @var InsertionDefinition */
        $insertionDefinition = $contentTypeDefinition->getInsertionDefinition('insert1');
        $fields              = $insertionDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        /* @var InsertionDefinition */
        $insertionDefinition = $contentTypeDefinition->getInsertionDefinition('insert2');
        $fields              = $insertionDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);
    }


    public function testTabsExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-06.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');

        $formElements = $clippingDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabStartFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[4]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[5]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[6]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[7]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[8]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[9]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[10]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabEndFormElementDefinition', $formElements[11]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabStartFormElementDefinition', $formElements[12]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[13]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[14]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabEndFormElementDefinition', $formElements[15]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[16]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[17]);

    }

}