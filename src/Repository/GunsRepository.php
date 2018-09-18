<?php

namespace Repository;

use Doctrine\DBAL\Connection;


/**
 * Class GunsRepository
 * @package Repository
 */
class GunsRepository
{

    const NUM_ITEMS = 10;

    /**
     * Doctrine DBAL connection.
     *
     * @var \Doctrine\DBAL\Connection $db
     */
    protected $db;

    /**
     * GunsRepository constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Wyświetlenie listy wszystkich broni
     * @param int $page
     * @return array
     */
    public function findAllGuns($page = 1)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('*')->from('guns');

        $queryBuilder->setFirstResult(($page - 1) * static::NUM_ITEMS)
            ->setMaxResults(static::NUM_ITEMS);

        $pagesNumber = $this->countAllPages();

        $paginator = [
            'page' => ($page < 1 || $page > $pagesNumber) ? 1 : $page,
            'max_results' => static::NUM_ITEMS,
            'pages_number' => $pagesNumber,
            'data' => $queryBuilder->execute()->fetchAll(),
        ];
        return $paginator;
   }

    /**
     * Wyświetlenie broni o danym id
     * @param $id
     * @return mixed
     */
    public function findGunById($id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('guns')
            ->where('id =:gunId')
            ->setParameter('gunId', $id);

        return $queryBuilder->execute()->fetch();
    }

    /**
     * Usunięcie broni o danym id
     * @param $id
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    public function deleteGunById($id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->delete('guns')
            ->where('id =:gunId')
            ->setParameter('gunId', $id);

        return $queryBuilder->execute();
    }

    /**
     * Count all pages.
     *
     * @return int Result
     */
    public function countAllPages()
    {
        $pagesNumber = 1;

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(DISTINCT id) AS total_results')
            ->from('guns')
            ->setMaxResults(1);

        $result = $queryBuilder->execute()->fetch();

        if ($result) {
            $pagesNumber =  ceil($result['total_results'] / static::NUM_ITEMS);
        } else {
            $pagesNumber = 1;
        }

        return $pagesNumber;
    }
}