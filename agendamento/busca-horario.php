<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

class Horario
{
  public $horario;

  function __construct($horario)
  {
    $this->horario = $horario;
  }
}

$horarios = [];
$medico = $_GET['medico'] ?? '';
$data = $_GET['data'] ?? '';

try {

  $sql = <<<SQL
  SELECT DISTINCT horario
  FROM agenda
  WHERE codigo_medico = ?
  AND data_agendamento = ?
  SQL;

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$medico, $data]);
  while ($row = $stmt->fetch()) {
    $horarios[] = new Horario($row['horario']);
  }
} catch (Exception $e) {
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
  
echo json_encode($horarios);