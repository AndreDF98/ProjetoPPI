<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

// Inicializa e resgata dados do cliente
$cep = $logradouro = $cidade = $estado = "";
if (isset($_POST["cep"])) $cep = $_POST["cep"];
if (isset($_POST["logradouro"])) $logradouro = $_POST["logradouro"];
if (isset($_POST["cidade"])) $cidade = $_POST["cidade"];
if (isset($_POST["estado"])) $estado = $_POST["estado"];

$sql = <<<SQL
  INSERT INTO base_enderecos_ajax (cep, logradouro, cidade, estado)
  VALUES (?, ?, ?, ?)
  SQL;

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare($sql);
  if (!$stmt->execute([
    $cep, $logradouro, $cidade, $estado
  ])) throw new Exception('Falha na inserção');

  // Efetiva as operações
  $pdo->commit();

  header("location: novo-endereco.html");
  exit();
} 
catch (Exception $e) {
  $pdo->rollBack();
  if ($e->errorInfo[1] === 1062)
    exit('Dados duplicados: ' . $e->getMessage());
  else
    exit('Falha ao cadastrar o endereço: ' . $e->getMessage());
}
