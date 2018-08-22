<?php
/**
 * User repository
 */

namespace Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class UserRepository.
 */
class DictionaryRepository
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

    public function getLockTypes()
    {
        $query = 'SELECT name,id FROM `lock_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getAmmuntionTypes()
    {
        $query = 'SELECT name,id FROM `ammunition_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getCaliberTypes()
    {
        $query = 'SELECT name,id FROM `caliber_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getGunTypes()
    {
        $query = 'SELECT name,id FROM `gun_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getReloadTypes()
    {
        $query = 'SELECT name,id FROM `reload_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}