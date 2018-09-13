<?php

namespace Repository;

use Doctrine\DBAL\Connection;


/**
 * Class GunsRepository
 * @package Repository
 */
class GunsRepository
{
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
     * @return array
     * Wyświetlenie listy wszystkich broni
     */
    public function findAllGuns()
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('*')->from('guns');

        return $queryBuilder->execute()->fetchAll();
   }

    /**
     * @param $id
     * @return mixed
     * Wyświetlenie broni o danym id
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
     * @param $id
     * @return \Doctrine\DBAL\Driver\Statement|int
     * Usunięcie broni o danym id
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
}