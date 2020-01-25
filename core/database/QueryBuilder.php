<?php

namespace App\Core\Database;

class QueryBuilder {

  protected $pdo;

  public function __construct(\PDO $pdo) {
    $this->pdo = $pdo;
  }

  public function select($tabela, $coluna = "*", $where = NULL, $order = NULL, $limit = NULL) {
    try {
        $statement = $this->pdo->prepare("SELECT {$coluna} FROM {$tabela} {$where} {$order} {$limit}");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_CLASS);
    } catch (PDOException $e) {
        dd($e->getMessage());
    }
  }

  public function selectAll($table) {
    $statement = $this->pdo->prepare("select * from {$table}");
    $statement->execute();
  
    return $statement->fetchAll(\PDO::FETCH_CLASS);
  }

  public function insert($table, $parameters) {
    $sql = sprintf(
      'INSERT INTO %s (%s) VALUES (%s)',
      $table,
      implode(', ', array_keys($parameters)),
      ':' . implode(', :', array_keys($parameters))
    );

    $statement = $this->pdo->prepare($sql);

    $statement->execute($parameters);
  }

}
