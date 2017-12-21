<?php

namespace Jhg\GenderizeIoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenderizeResult
 *
 * @ORM\Table(name="genderize_result", indexes={
 *  @ORM\Index(name="queryFromCache", columns={"name", "countryId", "languageId", "lastUpdatedAt" }),
 *  @ORM\Index(name="getExisting", columns={"name", "countryId", "languageId" })
 * })
 * @ORM\Entity()
 */
class GenderizeResult
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="probability", type="decimal", nullable=true)
     */
    private $probability;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=true)
     */
    private $count;

    /**
     * @var string
     *
     * @ORM\Column(name="countryId", type="string", length=2, nullable=true)
     */
    private $countryId;

    /**
     * @var string
     *
     * @ORM\Column(name="languageId", type="string", length=2, nullable=true)
     */
    private $languageId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdatedAt", type="datetime")
     */
    private $lastUpdatedAt;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return GenderizeResult
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return GenderizeResult
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set probability
     *
     * @param string $probability
     *
     * @return GenderizeResult
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Get probability
     *
     * @return string
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return GenderizeResult
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set countryId
     *
     * @param string $countryId
     *
     * @return GenderizeResult
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return string
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set languageId
     *
     * @param string $languageId
     *
     * @return GenderizeResult
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }

    /**
     * Get languageId
     *
     * @return string
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Set lastUpdatedAt
     *
     * @param \DateTime $lastUpdatedAt
     *
     * @return GenderizeResult
     */
    public function setLastUpdatedAt($lastUpdatedAt)
    {
        $this->lastUpdatedAt = $lastUpdatedAt;

        return $this;
    }

    /**
     * Get lastUpdatedAt
     *
     * @return \DateTime
     */
    public function getLastUpdatedAt()
    {
        return $this->lastUpdatedAt;
    }
}

