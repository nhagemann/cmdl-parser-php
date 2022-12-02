<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\InsertFormElementDefinition;
use PHPUnit\Framework\TestCase;

class InsertAnnotationTest extends TestCase
{
    public function testInsertAnnotation()
    {

        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-08.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formelements = $viewDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formelements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\InsertFormElementDefinition', $formelements[3]);

        // @var $formElement InsertFormElementDefinition
        $formElement = $formelements[3];
        $this->assertEquals('insert1', $formElement->getClippingName());

        $this->assertContains('a', $contentTypeDefinition->getProperties());
        $this->assertContains('d', $contentTypeDefinition->getProperties());
        $this->assertNotContains('j', $contentTypeDefinition->getProperties());

        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-09.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formelements = $viewDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formelements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\InsertFormElementDefinition', $formelements[3]);

        // @var $formElement InsertFormElementDefinition
        $formElement = $formelements[3];
        $this->assertEquals('insert1', $formElement->getClippingName(1));
        $this->assertEquals('insert2', $formElement->getClippingName(2));
        $this->assertEquals(null, $formElement->getClippingName(3));
        $this->assertEquals('a', $formElement->getPropertyName());

        $this->assertContains('a', $contentTypeDefinition->getProperties());
        $this->assertContains('d', $contentTypeDefinition->getProperties());
        $this->assertContains('j', $contentTypeDefinition->getProperties());

        $this->assertTrue($contentTypeDefinition->hasProperty('a'));
        $this->assertTrue($contentTypeDefinition->hasProperty('d'));
        $this->assertTrue($contentTypeDefinition->hasProperty('j'));

        $this->assertTrue($viewDefinition->hasProperty('a'));
        $this->assertTrue($viewDefinition->hasProperty('d'));
        $this->assertTrue($viewDefinition->hasProperty('j'));
    }//end testInsertAnnotation()


    public function testMultipleInserts()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLString(
            '
        
        a
        @insert a (1:i1,2:i2)
        +++i1+++
        b
        +++i2+++
        c
        
        '
        );

        $this->assertContains('a', $contentTypeDefinition->getProperties());
        $this->assertContains('b', $contentTypeDefinition->getProperties());
        $this->assertContains('c', $contentTypeDefinition->getProperties());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $this->assertContains('i1', $viewDefinition->getNamesOfEventuallyInsertedClippings());
        $this->assertContains('i2', $viewDefinition->getNamesOfEventuallyInsertedClippings());

        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLString(
            '
        
        a
        @insert a (1:i1)
        @insert a (1:i2)
        +++i1+++
        b
        +++i2+++
        c
        
        '
        );

        $this->assertContains('a', $contentTypeDefinition->getProperties());
        $this->assertContains('b', $contentTypeDefinition->getProperties());
        $this->assertContains('c', $contentTypeDefinition->getProperties());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $this->assertContains('i1', $viewDefinition->getNamesOfEventuallyInsertedClippings());
        $this->assertContains('i2', $viewDefinition->getNamesOfEventuallyInsertedClippings());
    }//end testMultipleInserts()


    public function testWorkspacesAndLanguagesRestrictions()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLString(
            '
        
        a
        @insert a (1:i1) (default) ()
        +++i1+++
        b        
        '
        );

        $this->assertContains('a', $contentTypeDefinition->getProperties());
        $this->assertContains('b', $contentTypeDefinition->getProperties());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formelements = $viewDefinition->getFormElementDefinitions();

        /*
         * @var InsertFormElementDefinition $insertFormElement
         */
        $insertFormElement = $formelements[1];
        $this->assertContains('default', $insertFormElement->getWorkspaces());
        $this->assertTrue($insertFormElement->hasWorkspacesRestriction());
        $this->assertFalse($insertFormElement->hasLanguagesRestriction());

        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLString(
            '
        
        a
        @insert i1 (default) ()
        +++i1+++
        b        
        '
        );

        $this->assertContains('a', $contentTypeDefinition->getProperties());
        $this->assertContains('b', $contentTypeDefinition->getProperties());

        /*
         * @var InsertFormElementDefinition $insertFormElement
         */
        $insertFormElement = $formelements[1];
        $this->assertContains('default', $insertFormElement->getWorkspaces());
        $this->assertTrue($insertFormElement->hasWorkspacesRestriction());
        $this->assertFalse($insertFormElement->hasLanguagesRestriction());
    }//end testWorkspacesAndLanguagesRestrictions()
}//end class
