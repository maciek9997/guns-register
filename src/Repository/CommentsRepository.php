<?php

namespace Repository;

use Doctrine\DBAL\Connection;


/**
 * Class CommentsRepository
 * @package Repository
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
     * CommentsRepository constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param $userId
     * @param $gunId
     * @param $formData
     * Dodanie komentarza przez danego użytkownika o danej broni
     */
    public function addComment($userId, $gunId, $formData)
    {
        $data = [];
        $data['user_id'] = $userId;
        $data['gun_id'] = $gunId;
        $data['comment'] = htmlspecialchars($formData['comment']);

        $this->db->insert('comments', $data);
   }

    /**
     * @param $gunId
     * @return array
     * Wyświetlenie komentarzy odnośnie danej broni
     */
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