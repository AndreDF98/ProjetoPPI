<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

// Inicializa e resgata dados do cliente
$data = $horario = $nome = $sexo = $email = $medico = "";
if (isset($_POST["data"])) $data = $_POST["data"];
if (isset($_POST["horario"])) $horario = $_POST["horario"];
if (isset($_POST["nome"])) $nome = $_POST["nome"];
if (isset($_POST["sexo"])) $sexo = $_POST["sexo"];
if (isset($_POST["email"])) $email = $_POST["email"];
if (isset($_POST["medico"])) $medico = $_POST["medico"];

$sql = <<<SQL
  INSERT INTO agenda (data_agendamento, horario, nome, sexo, email, codigo_medico)
  VALUES (?, ?, ?, ?, ?, ?)
  SQL;

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare($sql);
  if (!$stmt->execute([
    $data, $horario, $nome, $sexo, $email, $medico
  ])) throw new Exception('Falha na inserção');

  // Efetiva as operações
  $pdo->commit();

  header("location: agenda-consulta.php");
  exit();
} 
catch (Exception $e) {
  $pdo->rollBack();
  if ($e->errorInfo[1] === 1062)
    exit('Dados duplicados: ' . $e->getMessage());
  else
    exit('Falha ao cadastrar a agenda: ' . $e->getMessage());
}
