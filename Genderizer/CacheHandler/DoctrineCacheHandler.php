<?php


namespace Jhg\GenderizeIoBundle\Genderizer\CacheHandler;

use Doctrine\ORM\QueryBuilder;
use Jhg\GenderizeIoBundle\Entity\GenderizeResult;
use Jhg\GenderizeIoClient\Model\Name;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\VarDumper;

class DoctrineCacheHandler implements CacheHandlerInterface
{

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * DoctrineCacheHandler constructor.
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function transformData($data)
    {
        if (empty($data['country_id'])) $data['country_id'] = null;
        if (empty($data['language_id'])) $data['language_id'] = null;
        if (empty($data['probability'])) $data['probability'] = null;
        if (empty($data['count'])) $data['count'] = null;

        return $data;
    }

    /**
     * @param $query
     * @param $expiryTime
     * @return CachedResult
     */
    public function isCached($query, $expiryTime)
    {
        $query = $this->transformData($query);

        if ($result = $this->queryCache($query['name'], $query['country_id'], $query['language_id'], $expiryTime))
            return new CachedResult($result->getName(), $result->getGender(), $result->getProbability(), $result->getCount(), $result->getCountryId(), $result->getLanguageId());

    }

    /**
     * @param $name
     * @param $countryId
     * @param $languageId
     * @param null $expiryTime
     * @return mixed
     */
    public function queryCache($name, $countryId, $languageId, $expiryTime = null)
    {
        $em = $this->doctrine->getManager();

        /**
         * @var QueryBuilder $qb
         */
        $qb = $em->getRepository('GenderizeIoBundle:GenderizeResult')->createQueryBuilder('q');
        $qb->where('q.name = :name')->setParameter('name', ucwords(strtolower($name)));

        if ($countryId) $qb->andWhere('q.countryId = :countryId')->setParameter('countryId', $countryId);
        else $qb->andWhere('q.countryId IS NULL');


        if ($languageId) $qb->andWhere('q.languageId = :languageId')->setParameter('languageId', $languageId);
        else                $qb->andWhere('q.languageId IS NULL');

        if ($expiryTime) {
            $date = new \DateTime();
            $date->setTimestamp(time() - $expiryTime);

            $qb->andWhere('q.lastUpdatedAt > :expiryTime')->setParameter('expiryTime', $date->format('Y-m-d H:i:s'));
        }

        $qb->setMaxResults(1);

        $res = $qb->getQuery()->getResult();

        if (!empty($res))
            return $res[0];

    }

    /**
     * @param $result
     */
    public function cacheResult($result)
    {
        $em = $this->doctrine->getManager();
        $result = $this->transformData($result);

        if (!($cache = $this->queryCache($result['name'], $result['country_id'], $result['language_id']))) {
            $cache = new GenderizeResult();

            if (!empty($result['country_id'])) $cache->setCountryId($result['country_id']);
            if (!empty($result['language_id'])) $cache->setLanguageId($result['language_id']);

            $cache->setName(ucwords(strtolower($result['name'])));

            $em->persist($cache);
        }

        $cache->setLastUpdatedAt(new \DateTime())->setProbability($result['probability'])->setCount($result['count'])->setGender($result['gender']);

        $em->flush();
    }

}