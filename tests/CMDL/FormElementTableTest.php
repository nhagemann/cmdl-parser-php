<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TableFormElementDefinition;
use PHPUnit\Framework\TestCase;

class FormElementTableTest extends TestCase
{
    public function testParsing()
    {
        // @var TableFormElementDefinition $formElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('Table = table (A,B)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TableFormElementDefinition', $formElementDefinition);

        $this->assertEquals('Table', $formElementDefinition->getLabel());
        $this->assertEquals('table', $formElementDefinition->getName());
        $this->assertFalse($formElementDefinition->isMandatory());
        $this->assertFalse($formElementDefinition->isUnique());
        $this->assertEquals([ 1 => 'A', 2 => 'B' ], $formElementDefinition->getColumnHeadings());
        $this->assertEquals([], $formElementDefinition->getWidths());

        // @var TableFormElementDefinition $formElementDefinition
        $formElementDefinition = Parser::parseFormElementDefinition('Table = table (A,B) (10,10,50)');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TableFormElementDefinition', $formElementDefinition);
        $this->assertEquals([ 1 => 'A', 2 => 'B' ], $formElementDefinition->getColumnHeadings());
        $this->assertEquals([ 1 => '10', 2 => '10', 3 => '50' ], $formElementDefinition->getWidths());
    }
}
