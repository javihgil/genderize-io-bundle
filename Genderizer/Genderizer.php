<?php

namespace Jhg\GenderizeIoBundle\Genderizer;

use Jhg\GenderizeIoBundle\Genderizer\CacheHandler\CachedResult;
use Jhg\GenderizeIoBundle\Genderizer\CacheHandler\CacheHandlerInterface;
use Jhg\GenderizeIoBundle\Genderizer\Exception\NoValidCachedResultException;
use Jhg\GenderizeIoClient\HttpClient\GenderizeClient;
use Jhg\GenderizeIoClient\Genderizer\Exception\CountryNotValidException;
use Jhg\GenderizeIoClient\Genderizer\Exception\LanguageNotValidException;
use Jhg\GenderizeIoClient\Model\Name;
use Symfony\Component\Intl\Intl;

/**
 * Class Genderizer
 *
 * @package Jhg\GenderizeIoClient\Genderizer
 * @author Ruben Harms <info@rubenharms.nl>
 */
class Genderizer extends \Jhg\GenderizeIoClient\Genderizer\Genderizer
{
    /**
     * @var CacheHandlerInterface
     */
    protected $cacheHandler = null;

    /**
     * @var integer
     */
    protected $cacheExpiryTime = (3600 * 24 * 90);

    /**
     * @var boolean
     */
    protected $cacheResults = true;

    /**
     * @var boolean
     */
    protected $apiKey = null;

    /**
     * @param string|array $nameOrNames
     * @param string|null $country
     * @param string|null $language
     * @param int $hydration
     *
     * @return Name|array
     *
     * @throws CountryNotValidException
     * @throws LanguageNotValidException
     */
    public function recognize($nameOrNames, $country = null, $language = null, $hydration = self::HYDRATE_OBJECT)
    {
        if ($country !== null && !isset($this->validCountries[strtoupper($country)])) {
            throw new CountryNotValidException(sprintf('Country %s is not valid', strtoupper($country)));
        }

        if ($language !== null && !isset($this->validLanguages[strtolower($language)])) {
            throw new LanguageNotValidException(sprintf('Language %s is not valid', strtolower($language)));
        }

        $query = [
            'name' => $nameOrNames,
        ];

        if ($this->apiKey !== null) {
            $query['apikey'] = $this->apiKey;
        }

        if ($country !== null) {
            $query['country_id'] = $country;
        }

        if ($language !== null) {
            $query['language_id'] = $language;
        }


        if (is_array($nameOrNames)) {
            $collection = [];
            $nameArray = $nameOrNames;
            foreach ($nameArray as $key => $name) {
                if ($cached = $this->isCached(array_merge(['name' => $name], $query))) {
                    unset($nameArray[$key]);
                    $collection[] = $cached->toArray();
                }
            }

            if (count($nameArray) > 0)
                $collection += $this->genderize($query);

            return $this->hydrate($collection, $hydration);

        }

        if (!$cached = $this->isCached($query)) {
            $results = $this->genderizeClient->genderize($query);
            $this->cacheResults($results);
        } else
            $results = $cached;


        return $this->hydrate($results, $hydration);

    }

    /**
     * @param $query
     * @return array
     */
    protected function genderize($query)
    {
        $results = $this->genderizeClient->genderize($query);
        $this->cacheResults($results);
        return $results;
    }

    /**
     * @param $results
     */
    protected function cacheResults($results)
    {

        if (!$this->cacheResults) return;

        if (!is_array($results) || empty($data[0]))
            $results = array($results);

        foreach ($results as $result) {
            $this->cacheHandler->cacheResult($result);
        }
    }

    /**
     * @param $query
     * @return array|void
     * @throws NoValidCachedResultException
     */
    protected function isCached($query)
    {


        if (!$this->cacheResults) return;

        if (($cached = $this->cacheHandler->isCached($query, $this->cacheExpiryTime))) {
            if (!($cached instanceof CachedResult))
                throw new NoValidCachedResultException();
            return $cached->toArray();
        }


    }

    /**
     * @param $data
     * @param int $hydration
     * @return array|Name
     */
    protected function hydrate($data, $hydration = self::HYDRATE_OBJECT)
    {
        if ($hydration == self::HYDRATE_OBJECT) {
            if (is_array($data) && !empty($data[0])) {
                $collection = [];
                foreach ($data as $nameData) {
                    $collection[] = Name::factory($nameData);
                }
                // multiple query
                return $collection;
            } else {
                // single query
                return Name::factory($data);
            }
        } else {
            // multiple or single query
            return $data;
        }
    }

    /**
     * @return CacheHandlerInterface
     */
    public function getCacheHandler()
    {
        return $this->cacheHandler;
    }

    /**
     * @param CacheHandlerInterface $cacheHandler
     */
    public function setCacheHandler($cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @return int
     */
    public function getCacheExpiryTime()
    {
        return $this->cacheExpiryTime;
    }

    /**
     * @param int $cacheExpiryTime
     */
    public function setCacheExpiryTime($cacheExpiryTime)
    {
        $this->cacheExpiryTime = $cacheExpiryTime;
    }

    /**
     * @return boolean
     */
    public function isCacheResults()
    {
        return $this->cacheResults;
    }

    /**
     * @param boolean $cacheResults
     */
    public function setCacheResults($cacheResults)
    {
        $this->cacheResults = $cacheResults;
    } //90 days

}
