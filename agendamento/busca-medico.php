<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

class Medico
{
  public $nome;
  public $codigo;

  function __construct($nome, $codigo)
  {
    $this->nome = $nome;
    $this->codigo = $codigo;
  }
}

$medicos = [];
$especialidade = $_GET['especialidade'] ?? '';

try {

  $sql = <<<SQL
  SELECT p.nome, p.codigo
  FROM medico m
  JOIN pessoa p ON p.codigo = m.codigo
  WHERE m.especialidade = ?
  SQL;

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$especialidade]);
  while ($row = $stmt->fetch()) {
    $medicos[] = new Medico($row['nome'], $row['codigo']);
  }
} catch (Exception $e) {
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
  
echo json_encode($medicos);