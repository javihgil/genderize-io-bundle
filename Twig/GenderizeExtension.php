<?php

namespace Jhg\GenderizeIoBundle\Twig;

use Jhg\GenderizeIoClient\Genderizer\Genderizer;

/**
 * Class GenderizeExtension
 * 
 * @package Jhg\GenderizeIoBundle\Twig
 */
class GenderizeExtension extends \Twig_Extension
{

    /**
     * @var Genderizer
     */
    protected $genderizer;

    /**
     * @param Genderizer $genderizer
     */
    public function __construct(Genderizer $genderizer)
    {
        $this->genderizer = $genderizer;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'genderize';
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            'gender' => new \Twig_SimpleFilter('gender', [$this, 'gender']),
            'genderInCountry' => new \Twig_SimpleFilter('genderInCountry', [$this, 'gender']),
            'genderInLanguage' => new \Twig_SimpleFilter('genderInLanguage', [$this, 'gender']),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'isMale' => new \Twig_SimpleFunction('isMale', [$this, 'isMale']),
            'isFemale' => new \Twig_SimpleFunction('isFemale', [$this, 'isFemale']),
        ];
    }

    /**
     * @param string $name
     * @param string $country
     * @param string $language
     *
     * @return string|null
     * @throws \Jhg\GenderizeIoClient\Exception\CountryNotValidException
     * @throws \Jhg\GenderizeIoClient\Exception\LanguageNotValidException
     */
    public function gender($name, $country = null, $language = null)
    {
        $nameObj = $this->genderizer->recognize($name, $country, $language);

        return $nameObj->getGender();
    }

    /**
     * @param string $name
     * @param string $country
     *
     * @return string|null
     * @throws \Jhg\GenderizeIoClient\Exception\CountryNotValidException
     */
    public function genderInCountry($name, $country)
    {
        $nameObj = $this->genderizer->recognize($name, $country);

        return $nameObj->getGender();
    }

    /**
     * @param string $name
     * @param string $language
     *
     * @return string|null
     * @throws \Jhg\GenderizeIoClient\Exception\LanguageNotValidException
     */
    public function genderInLanguage($name, $language)
    {
        $nameObj = $this->genderizer->recognize($name, null, $language);

        return $nameObj->getGender();
    }

    /**
     * @param string      $name
     * @param string|null $country
     * @param string|null $language
     *
     * @return bool
     * @throws \Jhg\GenderizeIoClient\Exception\CountryNotValidException
     * @throws \Jhg\GenderizeIoClient\Exception\LanguageNotValidException
     */
    public function isMale($name, $country = null, $language = null)
    {
        $nameObj = $this->genderizer->recognize($name, $country, $language);

        return $nameObj->isMale();
    }

    /**
     * @param string      $name
     * @param string|null $country
     * @param string|null $language
     *
     * @return bool
     * @throws \Jhg\GenderizeIoClient\Exception\CountryNotValidException
     * @throws \Jhg\GenderizeIoClient\Exception\LanguageNotValidException
     */
    public function isFemale($name, $country = null, $language = null)
    {
        $nameObj = $this->genderizer->recognize($name, $country, $language);

        return $nameObj->isFemale();
    }
}