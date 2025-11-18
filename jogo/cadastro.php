<?php
include ("conexao.php");

$nome = $_POST['nome'];
$senha = $_POST['senha'];

// garantir que as colunas de controle existem (attempts, blocked_until)
$check = mysqli_query($conexao, "SHOW COLUMNS FROM cadastro LIKE 'attempts'");
if(mysqli_num_rows($check) == 0){
    @mysqli_query($conexao, "ALTER TABLE cadastro ADD COLUMN attempts INT NOT NULL DEFAULT 0");
}
$check2 = mysqli_query($conexao, "SHOW COLUMNS FROM cadastro LIKE 'blocked_until'");
if(mysqli_num_rows($check2) == 0){
    @mysqli_query($conexao, "ALTER TABLE cadastro ADD COLUMN blocked_until DATETIME NULL");
}

$nome = mysqli_real_escape_string($conexao, $nome);
$senha = mysqli_real_escape_string($conexao, $senha);

$sql = "INSERT INTO cadastro (nome,senha,attempts,blocked_until) VALUES ('$nome','$senha',0,NULL)";
if (mysqli_query ($conexao,$sql)) {
    header('Location: index.html');
 } else {
     echo "erro: " . mysqli_error($conexao);
 }

mysqli_close($conexao);
?>