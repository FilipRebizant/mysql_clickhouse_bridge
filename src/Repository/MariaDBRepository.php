<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MariaDBRepository extends AbstractRepository
{
    /** @var Connection */
    private $mariaDBConnection;

    /** @var string  */
    private $tableName = 'MainTable';

    public function __construct(ContainerInterface $container)
    {
        $this->mariaDBConnection = $container->get('doctrine.dbal.mariadb_connection');
    }

    /**
     * @param int $page
     * @return array
     */
    public function fetchAll(int $page): array
    {
        $offset = (($page - 1 ) * $this->queryLimit) > 0 ? ($page - 1 ) * $this->queryLimit : 0;
        $query = "SELECT *
                  FROM $this->tableName 
                  LIMIT $this->queryLimit 
                  OFFSET $offset";

        return $this->mariaDBConnection->fetchAll($query);
    }

    /**
     * @param int $id
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fetch(int $id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id = $id";
        return $this->mariaDBConnection->fetchAssoc($query);
    }

    /**
     * @param int $id
     * @throws \Doctrine\DBAL\DBALException
     */
    public function delete(int $id): void
    {
        $query = "DELETE
                  FROM $this->tableName
                  WHERE id = $id";

        $this->mariaDBConnection->query($query);
    }
}
