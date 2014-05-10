<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\Util;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;

class FormElementsTest extends \PHPUnit_Framework_TestCase
{

    public function testTextfieldDefinition()
    {
        /* @var TextfieldFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('Title');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Title', $formElementDefinition->getLabel());
        $this->assertEquals('title', $formElementDefinition->getName());
        $this->assertEquals('L', $formElementDefinition->getSize());
        $this->assertFalse($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());

        /* @var TextfieldFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('Überschrift = textfield* XL');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Überschrift', $formElementDefinition->getLabel());
        $this->assertEquals('ueberschrift', $formElementDefinition->getName());
        $this->assertEquals('XL', $formElementDefinition->getSize());
        $this->assertTrue($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());

        /* @var TextfieldFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('Überschrift = textfield*! XL {headline}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Überschrift', $formElementDefinition->getLabel());
        $this->assertEquals('headline', $formElementDefinition->getName());
        $this->assertEquals('XL', $formElementDefinition->getSize());
        $this->assertTrue($formElementDefinition->isMandatory());
        $this->assertTrue($formElementDefinition->isUnique());
    }


    public function testLinkDefinition()
    {
        /* @var LinkFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('URL = link');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\LinkFormElementDefinition', $formElementDefinition);
    }


    public function testTextareaDefinition()
    {
        /* @var TextareaFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('Abstract = textarea* 15 L {text1}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextareaFormElementDefinition', $formElementDefinition);
        $this->assertEquals('Abstract', $formElementDefinition->getLabel());
        $this->assertEquals('text1', $formElementDefinition->getName());
        $this->assertEquals(15, $formElementDefinition->getRows());
        $this->assertEquals('L', $formElementDefinition->getSize());
        $this->assertTrue($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());
    }


    public function testTextareaDescendantsDefinition()
    {
        $formElementDefinition = Parser::parseFormElementDefinition('Abstract = richtext* 15 L {text1}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\RichtextFormElementDefinition', $formElementDefinition);

        $formElementDefinition = Parser::parseFormElementDefinition('Abstract = markdown* 15 L {text1}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\MarkdownFormElementDefinition', $formElementDefinition);

        $formElementDefinition = Parser::parseFormElementDefinition('Abstract = html* 15 L {text1}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HTMLFormElementDefinition', $formElementDefinition);

        $formElementDefinition = Parser::parseFormElementDefinition('Abstract = cmdl* 15 L {text1}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\CMDLFormElementDefinition', $formElementDefinition);

        $formElementDefinition = Parser::parseFormElementDefinition('Template = sourcecode html 15 L {text1}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SourceCodeFormElementDefinition', $formElementDefinition);
        $this->assertEquals('html', $formElementDefinition->getType());
    }


    public function testSelectionsAndReferencesDefinition()
    {
        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = selection (1:a,2:b,3:c)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SelectionFormElementDefinition', $formElementDefinition);
        $this->assertEquals('dropdown', $formElementDefinition->getType());
        $this->assertCount(3, $formElementDefinition->getOptions());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = selection radio (1:a,2:b,3:c)');
        $this->assertEquals('radio', $formElementDefinition->getType());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = multiselection (1:a,2:b,3:c)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\MultiSelectionFormElementDefinition', $formElementDefinition);
        $this->assertEquals('list', $formElementDefinition->getType());
        $this->assertCount(3, $formElementDefinition->getOptions());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = reference news');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\ReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('dropdown', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('default', $formElementDefinition->getWorkspace());
        $this->assertEquals('name', $formElementDefinition->getOrder());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = multireference news checkbox live date');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\MultiReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('checkbox', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('live', $formElementDefinition->getWorkspace());
        $this->assertEquals('date', $formElementDefinition->getOrder());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = remote-selection http://www.example.org./json/customer');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\RemoteSelectionFormElementDefinition', $formElementDefinition);
        $this->assertEquals('dropdown', $formElementDefinition->getType());
        $this->assertEquals('http://www.example.org./json/customer', $formElementDefinition->getUrl());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = remote-multiselection http://www.example.org./json/customer');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\RemoteMultiSelectionFormElementDefinition', $formElementDefinition);
        $this->assertEquals('list', $formElementDefinition->getType());
        $this->assertEquals('http://www.example.org./json/customer', $formElementDefinition->getUrl());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = remote-reference http://www.example.org./repo news dropdown live date');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\RemoteReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('http://www.example.org./repo', $formElementDefinition->getUrl());
        $this->assertEquals('dropdown', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('live', $formElementDefinition->getWorkspace());
        $this->assertEquals('date', $formElementDefinition->getOrder());

        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = remote-multireference http://www.example.org./repo news list live date');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\RemoteMultiReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('http://www.example.org./repo', $formElementDefinition->getUrl());
        $this->assertEquals('list', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('live', $formElementDefinition->getWorkspace());
        $this->assertEquals('date', $formElementDefinition->getOrder());
    }


    public function testColorDefinition()
    {
        /* @var ColorFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('background = color (white:fff, black:000)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\ColorFormElementDefinition', $formElementDefinition);
    }


    public function testRangeDefinition()
    {
        /* @var RangeFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('temperature = range 18 30 0.5');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\RangeFormElementDefinition', $formElementDefinition);
    }


    public function testDateTimeDefinition()
    {
        /* @var TimestampFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('start = timestamp');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TimestampFormElementDefinition', $formElementDefinition);
        /* @var DateFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('start = date');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\DateFormElementDefinition', $formElementDefinition);
        /* @var TimeFormElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('start = time');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TimeFormElementDefinition', $formElementDefinition);
    }


    public function testCustomFormElementDefinition()
    {
        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = custom video youtube (360p,720p,1080p)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\CustomFormElementDefinition', $formElementDefinition);

        $this->assertEquals('video', $formElementDefinition->getType());
        $this->assertEquals('youtube', $formElementDefinition->getParam(1));
        $this->assertCount(3, $formElementDefinition->getList(1));

    }


    public function testPrintFormElementDefinition()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('> Hello world!');

        /* @var ViewDefinition */
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formElements = $viewDefinition->getFormElementDefinitions();

        $formElementDefinition = $formElements[0];

        $this->assertInstanceOf('CMDL\FormElementDefinitions\PrintFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Hello world!', $formElementDefinition->getDisplay());

    }
}