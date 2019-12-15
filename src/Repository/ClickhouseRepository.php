<?php

namespace App\Repository;

use FOD\DBALClickHouse\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClickhouseRepository extends AbstractRepository
{
    /** @var Connection */
    private $conn;

    /** @var string  */
    private $tableName = 'MainTable';

    public function __construct(ContainerInterface $container)
    {
        $this->conn = $container->get('doctrine.dbal.clickhouse_connection');
    }

    public function fetchAll(int $page): array
    {
        $offset = (($page - 1 ) * $this->queryLimit) > 0 ? ($page - 1 ) * $this->queryLimit : 0;
        $query = "SELECT *
                  FROM $this->tableName 
                  LIMIT $this->queryLimit 
                  OFFSET $offset";

//        $query = "SELECT * FROM $this->tableName";
//        $stmt = $this->conn->prepare('SELECT * FROM new_table');
//        return $stmt->execute();
//        return $this->conn->executeQuery($query);
        return $this->conn->fetchAll($query);

    }

    /**
     * @param int $id
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fetch(int $id): array
    {
        $query = "SELECT * FROM $this->tableName WHERE id = $id";
        return $this->conn->fetchAssoc($query);
    }

    /**
     * @param array $data
     * @throws \Doctrine\DBAL\DBALException
     */
    public function insert(array $data)
    {
        $this->conn->insert($this->tableName, $data);
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

        return $this->conn->executeUpdate($query);
    }

    public function getRowsCount(): array
    {
        return [];
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setupDatabase()
    {
        // ***quick start***
        $fromSchema = $this->conn->getSchemaManager()->createSchema();
        $toSchema = clone $fromSchema;

        // create new table object
        $newTable = $toSchema->createTable($this->tableName);

        // add columns
        $newTable->addColumn('id', 'integer', ['unsigned' => true]);
        $newTable->addColumn('Age', 'string', ['notnull' => false]);
        $newTable->addColumn('City', 'string', ['notnull' => false]);
        $newTable->addColumn('Name', 'string', ['notnull' => false]);

        //set primary key
        $newTable->setPrimaryKey(['id']);

        // execute migration SQLs to create table in ClickHouse
        $sqlArray = $fromSchema->getMigrateToSql($toSchema, $this->conn->getDatabasePlatform());
        foreach ($sqlArray as $sql) {
            $this->conn->exec($sql);
        }
    }
}
