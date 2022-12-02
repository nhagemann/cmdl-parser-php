<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\Util;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use PHPUnit\Framework\TestCase;

class FormElementsTest extends TestCase
{
    public function testTextfieldDefinition()
    {
        // @var TextfieldFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('Title');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Title', $formElementDefinition->getLabel());
        $this->assertEquals('title', $formElementDefinition->getName());
        $this->assertEquals('L', $formElementDefinition->getSize());
        $this->assertFalse($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());

        // @var TextfieldFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('Überschrift = textfield* XL');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Überschrift', $formElementDefinition->getLabel());
        $this->assertEquals('ueberschrift', $formElementDefinition->getName());
        $this->assertEquals('XL', $formElementDefinition->getSize());
        $this->assertTrue($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());

        // @var TextfieldFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('Überschrift = textfield*! XL {headline}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Überschrift', $formElementDefinition->getLabel());
        $this->assertEquals('headline', $formElementDefinition->getName());
        $this->assertEquals('XL', $formElementDefinition->getSize());
        $this->assertTrue($formElementDefinition->isMandatory());
        $this->assertTrue($formElementDefinition->isUnique());
    }//end testTextfieldDefinition()


    public function testLinkDefinition()
    {
        // @var LinkFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('URL = link');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\LinkFormElementDefinition', $formElementDefinition);
    }//end testLinkDefinition()


    public function testTextareaDefinition()
    {
        // @var TextareaFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('Abstract = textarea* 15 L {text1}');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextareaFormElementDefinition', $formElementDefinition);
        $this->assertEquals('Abstract', $formElementDefinition->getLabel());
        $this->assertEquals('text1', $formElementDefinition->getName());
        $this->assertEquals(15, $formElementDefinition->getRows());
        $this->assertEquals('L', $formElementDefinition->getSize());
        $this->assertTrue($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());
    }//end testTextareaDefinition()


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
    }//end testTextareaDescendantsDefinition()


    public function testColorDefinition()
    {
        // @var ColorFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('background = color (white:fff, black:000)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\ColorFormElementDefinition', $formElementDefinition);
    }//end testColorDefinition()


    public function testRangeDefinition()
    {
        // @var RangeFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('temperature = range 18 30 0.5');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\RangeFormElementDefinition', $formElementDefinition);
    }//end testRangeDefinition()


    public function testDateTimeDefinition()
    {
        // @var TimestampFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('start = timestamp');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TimestampFormElementDefinition', $formElementDefinition);
        // @var DateFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('start = date');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\DateFormElementDefinition', $formElementDefinition);
        // @var TimeFormElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('start = time');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TimeFormElementDefinition', $formElementDefinition);
    }//end testDateTimeDefinition()


    public function testCustomFormElementDefinition()
    {
        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = custom video youtube (360p,720p,1080p)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\CustomFormElementDefinition', $formElementDefinition);

        $this->assertEquals('video', $formElementDefinition->getType());
        $this->assertEquals('youtube', $formElementDefinition->getParam(1));
        $this->assertCount(3, $formElementDefinition->getList(1));
    }//end testCustomFormElementDefinition()


    public function testPrintFormElementDefinition()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLString('> Hello world!');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formElements = $viewDefinition->getFormElementDefinitions();

        $formElementDefinition = $formElements[0];

        $this->assertInstanceOf('CMDL\FormElementDefinitions\PrintFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Hello world!', $formElementDefinition->getDisplay());
    }//end testPrintFormElementDefinition()
}//end class
