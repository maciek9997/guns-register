<?php

namespace Repository;

use Doctrine\DBAL\Connection;


/**
 * Class CollectionRepository
 * @package Repository
 */
class CollectionRepository
{
    /**
     * Doctrine DBAL connection.
     *
     * @var \Doctrine\DBAL\Connection $db
     */
    protected $db;

    /**
     * CollectionRepository constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param $userId
     * @param $gunId
     * Dodawanie danej broni do kolekcji danego użytkownika
     */
    public function addGun($userId, $gunId)
    {
        $data['user_id'] = $userId;
        $data['gun_id'] = $gunId;

        $this->db->insert('collections', $data);
   }

    /**
     * @param $userId
     * @return array
     * Wyszukanie broni z kolekcji danego użytkownika
     */
    public function findMyGuns($userId)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('guns', 'g')
            ->innerJoin('g', 'collections', 'c', 'c.gun_id = g.id')
            ->where('c.user_id =:userId')
            ->setParameter('userId', $userId);

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * @param $userId
     * @param $gunId
     * @return mixed
     * Wyszukanie czy dana broń już jest w kolekcji danego użytkownika
     */
    public function findisExist($userId,$gunId)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('collections')
            ->where('gun_id =:gunId')
            ->andWhere('user_id =:userId')
            ->setParameter('userId', $userId)
            ->setParameter('gunId', $gunId);

        return $queryBuilder->execute()->fetch();
    }

    /**
     * @param $id
     * @param $userId
     * @return \Doctrine\DBAL\Driver\Statement|int
     * Usuwanie danej broni z kolekcji użytkownika
     */
    public function deleteGun($id, $userId)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->delete('collections')
            ->where('id =:gunId')
            ->andWhere('user_id =:userId')
            ->setParameter('gunId', $id)
            ->setParameter('userId', $userId);

        return $queryBuilder->execute();
    }
}