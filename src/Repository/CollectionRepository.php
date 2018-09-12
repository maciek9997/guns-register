<?php
/**
 * User repository
 */

namespace Repository;

use Doctrine\DBAL\Connection;


/**
 * Class UserRepository.
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
     * TagRepository constructor.
     *
     * @param \Doctrine\DBAL\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    //Dodawanie danej broni do kolekcji danego użytkownika

    public function addGun($userId, $gunId)
    {
        $data['user_id'] = $userId;
        $data['gun_id'] = $gunId;

        $this->db->insert('collections', $data);
   }

   //Wyszukanie broni z kolekcji danego użytkownika

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

    //Wyszukanie czy dana broń już jest w kolekcji danego użytkownika

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
}