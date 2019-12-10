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
        $conn = $this->get('doctrine.dbal.clickhouse_connection');

        // ***quick start***
        $fromSchema = $conn->getSchemaManager()->createSchema();
        $toSchema = clone $fromSchema;


// create new table object
        $newTable = $toSchema->createTable('new_table');

// add columns
        $newTable->addColumn('id', 'integer', ['unsigned' => true]);
        $newTable->addColumn('payload', 'string', ['notnull' => false]);
// *option 'notnull' in false mode allows you to insert NULL into the column;
//                   in this case, the column will be represented in the ClickHouse as Nullable(String)
        $newTable->addColumn('hash', 'string', ['length' => 32, 'fixed' => true]);
// *option 'fixed' sets the fixed length of a string column as specified;
//                 if specified, the type of the column is FixedString

//set primary key
        $newTable->setPrimaryKey(['id']);


// execute migration SQLs to create table in ClickHouse
        $sqlArray = $fromSchema->getMigrateToSql($toSchema, $conn->getDatabasePlatform());
        foreach ($sqlArray as $sql) {
            $conn->exec($sql);
        }

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

    public function insert($data)
    {
        $this->mariaDBConnection->insert($this->tableName, $data);
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

    public function getRowsCount($columns)
    {
        $columns = explode(',', $columns);
        $query = "SELECT COUNT($columns[0]) as 'numberOfRows' FROM $this->tableName";

        return $this->mariaDBConnection->fetchAssoc($query);
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
