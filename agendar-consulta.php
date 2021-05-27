<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

try {

  $sql = <<<SQL
  SELECT DISTINCT especialidade
  FROM medico
  SQL;

  $stmt = $pdo->query($sql);
} catch (Exception $e) {
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Agendar Consulta</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
  <link rel="stylesheet" href="css/general.css">
</head>

<body>
  <header>
    <h1> Clínica Bem Viver </h1>
  </header>
  <nav class="navbar navbar-expand-sm navbar-light bg-light">
      <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
              aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                      <a class="nav-link" href="index.html">Home</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="galeria.html">Galeria</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" aria-current="page" href="novo-endereco.html">Novo Endereço</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link active" href="agendar-consulta.php">Agendar Consulta</a>
                  </li>
              </ul>
              <a class="nav-link" href="login.html">Login</a>
          </div>
      </div>
  </nav>
  <main>
    <div class="container">
      <h3>Agendamento de Consulta</h3>
    
      <form action="agendamento/agendar.php" method="POST" class="row g-3">
        <div class="col-sm-6">
          <label for="especialidade" class="form-label">Especialidade médica desejada:</label>
          <select name="especialidade" class="form-select" id="especialidade" name="especialidade">
            <option selected>Selecione</option>

            <?php
            while ($row = $stmt->fetch()) {
              $especialidade = htmlspecialchars($row['especialidade']);

              echo <<<HTML
                <option value={$especialidade}>{$especialidade}</option>      
              HTML;
            }
            ?>

          </select>
        </div>
        <div class="col-sm-6">
          <label for="medico" class="form-label">Médico especialista:</label>
          <select name="medico" class="form-select" id="medico" name="medico">
            <option selected>Selecione</option>
          </select>
        </div>
        <div class="col-sm-6">
          <label for="data" class="form-label">Data da consulta:</label>
          <input type="date" class="form-control" id="data" name="data">
        </div>
        <div class="col-sm-6">
          <label for="horario" class="form-label">Horário disponível para consulta:</label>
          <select name="horario" class="form-select" id="horario" name="horario">
            <option selected>Selecione</option>
          </select>
        </div>
        <div class="col-sm-8">
          <label for="nome" class="form-label">Nome:</label>
          <input type="text" class="form-control" id="nome" name="nome">
        </div>
        <div class="col-sm-4">
          <label for="sexo" class="form-label">Sexo:</label>
          <select name="sexo" class="form-select" id="sexo" name="sexo">
            <option selected>Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outro</option>
          </select>
        </div>
        <div>
          <label for="email" class="form-label">Email:</label>
          <input type="email" class="form-control" id="email" name="email">
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
      </form>
    
    </div>
  </main>

  <script>

    function buscaMedico(especialidade) {
      
      fetch("agendamento/busca-medico.php?especialidade=" + especialidade)
        .then(response => {
          if (!response.ok) {
            throw new Error(response.status);
          }
          return response.json();
        })
        .then(medicos => {
          
          var campoSelect = document.getElementById("medico");
          campoSelect.innerHTML = '';
          var option = document.createElement("option");
          option.text = "Selecione";
          option.value = "Selecione";
          campoSelect.add(option);

          medicos.forEach(element => {
            var option = document.createElement("option");
            option.text = element['nome'];
            option.value = element['codigo'];
            campoSelect.add(option);
          });
        })
        .catch(error => {
          console.error('Falha inesperada: ' + error);
        });
    }

    function buscaHorario(medico, data) {
      
      fetch("agendamento/busca-horario.php?medico=" + medico + "&data=" + data)
        .then(response => {
          if (!response.ok) {
            throw new Error(response.status);
          }
          return response.json();
        })
        .then(horarios => {

          var campoSelect = document.getElementById("horario");
          campoSelect.innerHTML = '';
          var option = document.createElement("option");
          option.text = "Selecione";
          option.value = "Selecione";
          campoSelect.add(option);

          let horas = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"];
          horarios.forEach(horario => {
            horas = horas.filter(hora => hora != horario['horario'].slice(0, -3))
          });
          
          horas.forEach(hora => {
            var option = document.createElement("option");
            option.text = hora;
            option.value = hora;
            campoSelect.add(option);
          })
        })
        .catch(error => {
          console.error('Falha inesperada: ' + error);
        });
    }

    window.onload = function () {
      const inputEspecialidade = document.querySelector("#especialidade");
      inputEspecialidade.addEventListener('change', (event) => buscaMedico(event.target.value));

      const inputData = document.querySelector("#data");
      const inputMedico = document.getElementById("medico");
      inputData.addEventListener('change', (event) => buscaHorario(inputMedico.value, event.target.value));
      inputMedico.addEventListener('change', (event) => buscaHorario(event.target.value, inputData.value));
    }

  </script>

</body>

</html>