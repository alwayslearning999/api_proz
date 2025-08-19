<?php 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin *");
header("Access-Control-Allow-Methods: POST, PUT");
header("Access-Control-Allow-Header: Content-Type");


//conexao

$host = "localhost";
$user = "root";
$pass = "aluno";
$db = "sistema_api";

$conn = new mysqli($host,$user,$pass,$db);

if($conn->connect_error){
  http_response_code(500);
  echo json_encode(["erro" => "Falha na conexão"], JSON_UNESCAPED_UNICODE);
  exit();
}

$method = $_SERVER["REQUEST_METHOD"];
if($method == "POST"){
    $data = json_decode(file_get_contents("php://input"),true);
    if(isset(
      $data["nome"],
      $data["email"],
      $data["senha"],
      $data["telefone"],
      $data["endereco"],
      $data["estado"],
      $data["data_nascimento"],
    )){
      $nome = $data["nome"];
      $email = $data["email"];
      $senha = $data["senha"];
      $telefone = $data["telefone"];
      $endereco = $data["endereco"];
      $estado = $data["estado"];
      $data_nascimento = $data["data_nascimento"];
    }else{
      http_response_code(400);
      echo json_encode(["erro"=>"Todos os campos são obrigatorios"], JSON_UNESCAPED_UNICODE);
      exit();
    }
    $sql = "INSET INTO api_usuarios(nome, email, senha, telefone, endereco, estado, data_nascimento)
     VALUES (
     '$nome',
     '$email',
     '$senha',
     '$telefone',
     '$endereco',
     '$estado',
     '$data_nascimento')";
    if($conn->query($sql)){
      $id = $conn->insert_id;
      $result = $conn->query("SELECT id, nome, email, senha, telefone, endereco, estado, data_nascimento, criado_em
       FROM api_usuarios WHERE id = $id");
        $cliente = $result->fetch_assoc();
        echo json_encode(["mensagem" => "Cliente cadastrado com sucesso", "cliente" => $cliente], 
        JSON_UNESCAPED_UNICODE);
    }else{
      http_response_code(400);
      echo json_encode(["erro" => "cliente ja existe"],
      JSON_UNESCAPED_UNICODE);
    }
}

?>