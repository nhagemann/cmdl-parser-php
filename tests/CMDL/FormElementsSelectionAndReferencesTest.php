<?php

namespace Tests\CMDL;

use CMDL\FormElementDefinitions\MultiReferenceFormElementDefinition;
use CMDL\FormElementDefinitions\ReferenceFormElementDefinition;
use CMDL\Parser;
use CMDL\Util;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use PHPUnit\Framework\TestCase;

class FormElementsSelectionAndReferencesTest extends TestCase
{
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


    public function testRepositoryReference()
    {
        /*
         * @var ReferenceFormElementDefinition $formElementDefinition
         */
        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = reference news');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\ReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('dropdown', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('default', $formElementDefinition->getWorkspace());
        $this->assertEquals('name', $formElementDefinition->getOrder());
        $this->assertFalse($formElementDefinition->hasRepositoryName());

        /*
         * @var ReferenceFormElementDefinition $formElementDefinition
         */
        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = reference a.news');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\ReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('dropdown', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('default', $formElementDefinition->getWorkspace());
        $this->assertEquals('name', $formElementDefinition->getOrder());
        $this->assertTrue($formElementDefinition->hasRepositoryName());

        /*
         * @var MultiReferenceFormElementDefinition $formElementDefinition
         */
        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = multireference news');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\MultiReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('list', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('default', $formElementDefinition->getWorkspace());
        $this->assertEquals('name', $formElementDefinition->getOrder());
        $this->assertFalse($formElementDefinition->hasRepositoryName());

        /*
         * @var MultiReferenceFormElementDefinition $formElementDefinition
         */
        $formElementDefinition = Parser::parseFormElementDefinition('prop1 = multireference a.news');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\MultiReferenceFormElementDefinition', $formElementDefinition);
        $this->assertEquals('list', $formElementDefinition->getType());
        $this->assertEquals('news', $formElementDefinition->getContentType());
        $this->assertEquals('default', $formElementDefinition->getWorkspace());
        $this->assertEquals('name', $formElementDefinition->getOrder());
        $this->assertTrue($formElementDefinition->hasRepositoryName());
    }
}
