<?php

namespace Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class DictionaryRepository
 * @package Repository
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
     * DictionaryRepository constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return array
     * @throws DBALException
     * Pobiera typy zamków broni z bazy danych
     */
    public function getLockTypes()
    {
        $query = 'SELECT name,id FROM `lock_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     * @throws DBALException
     * Pobiera typy amunicji broni z bazy danych
     */
    public function getAmmuntionTypes()
    {
        $query = 'SELECT name,id FROM `ammunition_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     * @throws DBALException
     * Pobiera typy kalibru broni z bazy danych
     */
    public function getCaliberTypes()
    {
        $query = 'SELECT name,id FROM `caliber_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     * @throws DBALException
     * Pobiera typy broni z bazy danych
     */
    public function getGunTypes()
    {
        $query = 'SELECT name,id FROM `gun_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     * @throws DBALException
     * Pobiera typy przeładowań broni z bazy danych
     */
    public function getReloadTypes()
    {
        $query = 'SELECT name,id FROM `reload_types`';
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     * @throws DBALException
     * Pobiera wszystkie dane z tabel słownikowych w celu ich odpowiedniego wyświetlenia
     */
    public function getAllTypes()
    {
        return [
            'lockTypes' => array_flip($this->getLockTypes()),
            'ammunitionTypes' => array_flip($this->getAmmuntionTypes()),
            'caliberTypes' => array_flip($this->getCaliberTypes()),
            'gunTypes' => array_flip($this->getGunTypes()),
            'reloadTypes' => array_flip($this->getReloadTypes()),
        ];
    }

    /**
     * @return array
     * @throws DBALException
     * Pobiera wszystkie dane z tabel słownikowych do formularza dodawania broni w celu umozliwienia ich odpowiedniego wyboru
     */
    public function getAllTypesForAddForm()
    {
        return [
            'lockTypes' => $this->getLockTypes(),
            'ammunitionTypes' => $this->getAmmuntionTypes(),
            'caliberTypes' => $this->getCaliberTypes(),
            'gunTypes' => $this->getGunTypes(),
            'reloadTypes' => $this->getReloadTypes(),
        ];
    }
}