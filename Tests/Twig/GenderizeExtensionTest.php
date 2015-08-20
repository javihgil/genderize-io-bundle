<?php

namespace Jhg\GenderizeIoBundle\Tests\Twig;

use Jhg\GenderizeIoBundle\Twig\GenderizeExtension;
use Jhg\GenderizeIoClient\Model\Name;
use \Mockery as m;

/**
 * Class GenderizeExtensionTest
 * 
 * @package Jhg\GenderizeIoBundle\Tests\Twig
 */
class GenderizeExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var m\MockInterface
     */
    protected $genderizerMock;

    /**
     * Generates mocks
     */
    public function setup()
    {
        $this->genderizerMock = m::mock('Jhg\GenderizeIoClient\Genderizer\Genderizer');
    }

    /**
     * Tests getName method
     */
    public function testGetName()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $this->assertEquals('genderize', $extension->getName());
    }

    /**
     * Tests getFilters method
     */
    public function testGetFilters()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $filters = $extension->getFilters();

        $this->assertTrue(is_array($filters));
        $this->assertTrue(3 == sizeof($filters));

        foreach ($filters as $filter) {
            $this->assertInstanceOf('\Twig_SimpleFilter', $filter);
        }
    }

    /**
     * Tests getFunctions method
     */
    public function testGetFunctions()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $functions = $extension->getFunctions();

        $this->assertTrue(is_array($functions));
        $this->assertTrue(2 == sizeof($functions));

        foreach ($functions as $function) {
            $this->assertInstanceOf('\Twig_SimpleFunction', $function);
        }
    }

    /**
     * Tests gender method
     */
    public function testGender()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $this->genderizerMock->shouldReceive('recognize')->andReturn(Name::factory(['name'=>'John', 'gender'=>'male']));
        $gender = $extension->gender('John', 'gb', 'en');

        $this->assertEquals('male', $gender);
    }

    /**
     * Tests genderInCountry method
     */
    public function testGenderInCountry()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $this->genderizerMock->shouldReceive('recognize')->andReturn(Name::factory(['name'=>'John', 'gender'=>'male']));
        $gender = $extension->genderInCountry('John', 'gb');

        $this->assertEquals('male', $gender);
    }

    /**
     * Tests genderInLanguage method
     */
    public function testGenderInLanguage()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $this->genderizerMock->shouldReceive('recognize')->andReturn(Name::factory(['name'=>'John', 'gender'=>'male']));
        $gender = $extension->genderInLanguage('John', 'en');

        $this->assertEquals('male', $gender);
    }

    /**
     * Tests isMale method
     */
    public function testIsMale()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $this->genderizerMock->shouldReceive('recognize')->andReturn(Name::factory(['name'=>'John', 'gender'=>'male']));
        $this->assertTrue($extension->isMale('John', 'gb', 'en'));
    }

    /**
     * Tests isFemale method
     */
    public function testIsFemale()
    {
        $extension = new GenderizeExtension($this->genderizerMock);
        $this->genderizerMock->shouldReceive('recognize')->andReturn(Name::factory(['name'=>'John', 'gender'=>'female']));
        $this->assertTrue($extension->isFemale('John', 'gb', 'en'));
    }
}