<?php

namespace Jhg\GenderizeIoBundle\Genderizer\CacheHandler;
use Jhg\GenderizeIoBundle\Genderizer\Exception\NoValidGenderException;

/**
 * Class CachedResult
 *
 * @package Jhg\GenderizeIoBundle\Genderizer\CacheHandler
 * @author Ruben Harms <info@rubenharms.nl>
 */
class CachedResult
{
    const MALE = 'male';
    const FEMALE = 'female';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var float
     */
    protected $probability;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $language;

    public function __construct($name, $gender, $probability, $count, $country = null, $language = null)
    {
        if ($gender && $gender != self::MALE && $gender != self::FEMALE) {
            throw new NoValidGenderException();
        }

        $this->name = $name;
        $this->gender = $gender;
        $this->probability = $probability;
        $this->count = $count;
        $this->country = $country;
        $this->language = $language;
    }

    public function toArray()
    {

        return

            [
                'name' => $this->name,
                'gender' => $this->gender,
                'probability' => $this->probability,
                'count' => $this->count,
                'country_id' => $this->country,
                'language_id' => $this->language
            ];
    }
}