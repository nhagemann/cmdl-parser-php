<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
use CMDL\FormElementDefinition;

class SynchronizedPropertiesTest extends \PHPUnit_Framework_TestCase
{

    public function testUnknownProperty()
    {
        $cmdl = '>>>CMDL
property1
property2
property3
@synchronized-properties (propertya)
        <<<CMDL';

        $this->setExpectedException('CMDL\CMDLParserException');
        $contentTypeDefinition = Parser::parseCMDLString($cmdl);


            var_dump ($contentTypeDefinition->getSynchronizedProperties());
    }

    public function testGlobalSynchronizedProperties()
    {
        $cmdl = '>>>CMDL
property1
property2
property3
@synchronized-properties (property1)
        <<<CMDL';

        $contentTypeDefinition = Parser::parseCMDLString($cmdl);

        $synchronizedProperties = $contentTypeDefinition->getSynchronizedProperties();

        $this->assertContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES]);
    }

    public function testWorkspacesSynchronizedProperties()
    {
        $cmdl = '>>>CMDL
property1
property2
property3
@synchronized-properties (property1) workspaces
        <<<CMDL';

        $contentTypeDefinition = Parser::parseCMDLString($cmdl);

        $synchronizedProperties = $contentTypeDefinition->getSynchronizedProperties();

        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL]);
        $this->assertContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES]);
    }

    public function testLanguagesSynchronizedProperties()
    {
        $cmdl = '>>>CMDL
property1
property2
property3
@synchronized-properties (property1) languages
        <<<CMDL';

        $contentTypeDefinition = Parser::parseCMDLString($cmdl);

        $synchronizedProperties = $contentTypeDefinition->getSynchronizedProperties();

        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES]);
        $this->assertContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES]);
    }

    public function testScopeCombination1SynchronizedProperties()
    {
        $cmdl = '>>>CMDL
property1
property2
property3
@synchronized-properties (property1) workspaces
@synchronized-properties (property1) languages
        <<<CMDL';

        $contentTypeDefinition = Parser::parseCMDLString($cmdl);

        $synchronizedProperties = $contentTypeDefinition->getSynchronizedProperties();

        $this->assertContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES]);
    }

    public function testScopeCombination2SynchronizedProperties()
    {
        $cmdl = '>>>CMDL
property1
property2
property3
@synchronized-properties (property1,property2) workspaces
@synchronized-properties (property1)
        <<<CMDL';

        $contentTypeDefinition = Parser::parseCMDLString($cmdl);

        $synchronizedProperties = $contentTypeDefinition->getSynchronizedProperties();

        $this->assertContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES]);
        $this->assertNotContains('property1',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES]);
        $this->assertContains('property2',$synchronizedProperties[ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES]);
    }
}