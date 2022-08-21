<?php

namespace App\Model;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

require_once(__DIR__ . '/../config/config.php');

class Database
{
    protected PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:dbname=" . DB_DATABASE_NAME . ";host=" . DB_HOST, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException) {
            throw new Exception('Nie można się połączyć z bazą danych.');
        }
    }

    public function execute(string $query = "", array $params = [])
    {
        try {
            $statement = $this->prepareStatement($query, $params);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if ($result === false) {
                throw new Exception('Nie prawidłowe żądanie' . $query, 400);
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return [];
    }

    /**
     * @param string $query sql query to execute
     * @param array $params set of parametrs. Structure:
     * ``` 
     * <?php
     *  array(
     *      [0] => 'attribute name',
     *      [1] => 'attribute value',
     *      [2] => 'attribute type' 
     *  )
     * ?>
     * ```
     */
    private function prepareStatement(string $query = "", array $params = []): PDOStatement
    {
        try {
            $statement = $this->connection->prepare($query);

            if ($statement === false) {
                throw new Exception('Nie udaje się przygotować żądanie do BD: ' . $query);
            }

            foreach ($params as $param) {
                $statement->bindValue($param[0], $param[1], $param[2]);
            }

            $statement->execute();

            return $statement;
        } catch (Exception $exeption) {
            throw new Exception($exeption->getMessage());
        } catch (PDOException $pdoException) {
            throw new Exception('Nie udaje się przygotować żądanie do BD: ' . $query);
        }
    }
}
