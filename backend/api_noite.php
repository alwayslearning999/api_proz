<?php
include "conexao.php";


header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin *");
header("Access-Control-Allow-Methods: POST, PUT");
header("Access-Control-Allow-Header: Content-Type");


$method = $_SERVER["REQUEST_METHOD"];
if ($method == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);
  if (isset(
    $data["nome"],
    $data["email"],
    $data["senha"],
    $data["telefone"],
    $data["endereco"],
    $data["estado"],
    $data["data_nascimento"],
  )) {
    $nome = $data["nome"];
    $email = $data["email"];
    $senha = $data["senha"];
    $telefone = $data["telefone"];
    $endereco = $data["endereco"];
    $estado = $data["estado"];
    $data_nascimento = $data["data_nascimento"];
  } else {
    http_response_code(400);
    echo json_encode(["erro" => "Todos os campos são obrigatorios"], JSON_UNESCAPED_UNICODE);
    exit();
  }


  $sql = "SELECT id FROM api_usuarios WHERE email = '$email'";
  $igual = $conn->query($sql);

  if ($igual->num_rows > 0) {
    echo json_encode(["erro" => "Usuario ja existe"], JSON_UNESCAPED_UNICODE);
    exit();
  }

  if (strlen($telefone < 9 or strlen($telefone) > 13)){
    echo json_encode(["erro" => "Numero inserido incorreto"], JSON_UNESCAPED_UNICODE);
    exit();
  }

if (strpos($email,'@')=== false){
echo json_encode(["erro" => "Email inserido invalido"], JSON_UNESCAPED_UNICODE);
    exit();
}

  $senhaEncriptada = password_hash($senha, PASSWORD_DEFAULT);

  $sql = "INSERT INTO api_usuarios(nome, email, senha, telefone, endereco, estado, data_nascimento)
     VALUES (
     '$nome',
     '$email',
     '$senhaEncriptada',
     '$telefone',
     '$endereco',
     '$estado',
     '$data_nascimento')";
  if ($conn->query($sql)) {
    $id = $conn->insert_id;
    $result = $conn->query("SELECT id, nome, email, senha, telefone, endereco, estado, data_nascimento, criado_em
       FROM api_usuarios WHERE id = $id");
    $cliente = $result->fetch_assoc();
    echo json_encode(
      ["mensagem" => "Cliente cadastrado com sucesso", "cliente" => $cliente],
      JSON_UNESCAPED_UNICODE
    );
  } else {
    http_response_code(400);
    echo json_encode(
      ["erro" => "cliente ja existe"],
      JSON_UNESCAPED_UNICODE
    );
  }
}
