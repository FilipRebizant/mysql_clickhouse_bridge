<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class MariaDBRepository extends AbstractRepository
{
    /** @var Connection */
    private $mariaDBConnection;

    /** @var string  */
    private $tableName = 'MainTable';

    public function __construct(Connection $connection)
    {
        $this->mariaDBConnection = $connection;
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
     * @param array $data
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function edit(array $data): int
    {
        $query = "
        UPDATE $this->tableName 
        SET 
          $this->tableName.id = '$data[id]',
          $this->tableName.Age = $data[age],
          $this->tableName.City = '$data[city]',
          $this->tableName.Name = '$data[name]'
        WHERE id = $data[id]";

        return $this->mariaDBConnection->executeUpdate($query);
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

    public function getColumnNames()
    {
        $query = "SHOW COLUMNS FROM $this->tableName";
        $columns = [];
        foreach ($this->mariaDBConnection->fetchAll($query) as $column) {
          $columns[] = $column['Field'];
        };

        return $columns;
    }

    public function getDataFromTables(string $tables, int $page)
    {
        $queryLimit = 500;
        $offset = (($page - 1 ) * $queryLimit) > 0 ? ($page - 1 ) * $queryLimit : 0;
        $query = "SELECT $tables
                  FROM $this->tableName 
                  LIMIT $queryLimit 
                  OFFSET $offset";

        return $this->mariaDBConnection->fetchAll($query);
    }
}
