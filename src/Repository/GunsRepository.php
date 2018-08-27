<?php
/**
 * User repository
 */

namespace Repository;

use Doctrine\DBAL\Connection;


/**
 * Class UserRepository.
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
     * TagRepository constructor.
     *
     * @param \Doctrine\DBAL\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function findAllGuns()
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->select('*')->from('guns');

        return $queryBuilder->execute()->fetchAll();
   }

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