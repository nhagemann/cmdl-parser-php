<?php

namespace Tests\CMDL;

use PHPUnit\Framework\TestCase;
use CMDL\Parser;
use CMDL\Util;

class NamingPatternTest extends TestCase
{
    public function testNamingAssertion()
    {
        $cmdl = 'Name
        First Name
        Second Name';

        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLString($cmdl);

        $this->assertFalse($contentTypeDefinition->hasNamingPattern());

        $cmdl = 'Name
        First Name
        Last Name
        @name "{{lastname}}, {{firstname}}"';

        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLString($cmdl);

        $this->assertTrue($contentTypeDefinition->hasNamingPattern());

        $this->assertEquals('{{lastname}}, {{firstname}}', $contentTypeDefinition->getNamingPattern());
    }


    public function testApplyPattern()
    {
        $properties = [
            'lastname'  => 'Hagemann',
            'firstname' => 'Nils',
        ];

        $pattern = '{{lastname}}, {{firstname}}';

        $this->assertEquals('Hagemann, Nils', Util::applyNamingPattern($properties, $pattern));

        $properties = [ 'lastname' => 'Hagemann' ];

        $pattern = '{{lastname}}, {{firstname}}';

        $this->assertEquals('Hagemann, ', Util::applyNamingPattern($properties, $pattern));
    }
}
