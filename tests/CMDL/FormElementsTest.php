<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\Util;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\HeadlineFormElementDefinition;
use CMDL\FormElementDefinitions\SectionStartFormElementDefinition;
use CMDL\FormElementDefinitions\SectionEndFormElementDefinition;

class FormElementsTest extends \PHPUnit_Framework_TestCase
{

    public function testTextfield()
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

}