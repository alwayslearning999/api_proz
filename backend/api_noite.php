<?php
include "conexao.php";
include "funcao.php";

// instrução do chat para ver o porque está dando os erros ao inves de apenas um codigo 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin *");
header("Access-Control-Allow-Methods: POST, PUT, GET, DELETE");
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

// nao consegui fazer funcionar da forma correta 

  $senhaEncriptada = password_hash($senha, PASSWORD_DEFAULT);

$id = gerarUuid();

  $sql = "INSERT INTO api_usuarios(id, nome, email, senha, telefone, endereco, estado, data_nascimento)
     VALUES (
     '$id',
     '$nome',
     '$email',
     '$senhaEncriptada',
     '$telefone',
     '$endereco',
     '$estado',
     '$data_nascimento')";
  if ($conn->query($sql)) {
    $result = $conn->query("SELECT id, nome, email,telefone, endereco, estado, data_nascimento, criado_em
       FROM api_usuarios WHERE id = '$id'");
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
} elseif ($method == "GET") {
    if (!isset($_GET['id'])) {
        $sql = "SELECT id, nome, email, telefone, endereco, estado, data_nascimento, criado_em FROM api_usuarios";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            echo json_encode(["mensagem" => "Lista de usuários", "usuarios" => $usuarios], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Nenhum usuário encontrado"], JSON_UNESCAPED_UNICODE);
        }
    } else {
        $id = $_GET['id'];
        $sql = "SELECT id, nome, email, telefone, endereco, estado, data_nascimento, criado_em FROM api_usuarios WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            echo json_encode(["mensagem" => "Usuário encontrado", "usuario" => $usuario], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Usuário não encontrado"], JSON_UNESCAPED_UNICODE);
        }
    }
} elseif ($method == "DELETE") {
  if(!isset($_GET['id'])) {
    http_response_code(404);
    echo json_encode(["erro" => "Usuário não encontrado"], JSON_UNESCAPED_UNICODE);
  }else{
    $id = $_GET['id'];
    $sql = "DELETE FROM api_usuarios WHERE id = '$id'";
    $conn->query($sql);
    if ($conn->affected_rows > 0){
      echo json_encode(
      ["mensagem" => "Cliente excluido"],
      JSON_UNESCAPED_UNICODE
    );

    if($conn->affected_rows == 0){
      echo json_encode(
      ["mensagem" => "Cliente nao encontrado"],
      JSON_UNESCAPED_UNICODE);
    };
  };
};
};