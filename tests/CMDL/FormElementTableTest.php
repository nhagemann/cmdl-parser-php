<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
use CMDL\FormElementDefinition;

use CMDL\FormElementDefinitions\TableFormElementDefinition;

class FormElementTableTest extends \PHPUnit_Framework_TestCase
{

    public function testParsing()
    {
        /* @var TableFormElementDefinition $formElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('Table = table (A,B)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TableFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Table', $formElementDefinition->getLabel());
        $this->assertEquals('table', $formElementDefinition->getName());
        $this->assertFalse($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());
        $this->assertEquals(array( 1 => 'A', 2 => 'B' ), $formElementDefinition->getColumnHeadings());
        $this->assertEquals(array(), $formElementDefinition->getWidths());

        /* @var TableFormElementDefinition $formElementDefinition */
        $formElementDefinition = Parser::parseFormElementDefinition('Table = table (A,B) (10,10,50)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TableFormElementDefinition', $formElementDefinition);
        $this->assertEquals(array( 1 => 'A', 2 => 'B' ), $formElementDefinition->getColumnHeadings());
        $this->assertEquals(array( 1 => '10', 2 => '10', 3 => '50' ), $formElementDefinition->getWidths());

    }

}