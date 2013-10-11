<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\Util;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\FormElementDefinitions\RichtextFormElementDefinition;
use CMDL\FormElementDefinitions\HeadlineFormElementDefinition;
use CMDL\FormElementDefinitions\SectionStartFormElementDefinition;
use CMDL\FormElementDefinitions\SectionEndFormElementDefinition;

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
    }

}