<?php
/**
 * User repository
 */

namespace Repository;

use Doctrine\DBAL\Connection;


/**
 * Class UserRepository.
 */
class CommentsRepository
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

    public function addComment($userId, $gunId, $formData)
    {
        $data = [];
        $data['user_id'] = $userId;
        $data['gun_id'] = $gunId;
        $data['comment'] = $formData['comment'];

        $this->db->insert('comments', $data);
   }

    public function findComments($gunId)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('comments', 'c')
            ->innerJoin('c', 'users', 'u', 'c.user_id = u.id')
            ->where('c.gun_id =:gunId')
            ->setParameter('gunId', $gunId);

        return $queryBuilder->execute()->fetchAll();
    }

}