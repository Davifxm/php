<?php
include("conexao.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$nome = mysqli_real_escape_string($conexao, $_POST['nome']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

// garantir colunas existam
$check = mysqli_query($conexao, "SHOW COLUMNS FROM cadastro LIKE 'attempts'");
if(mysqli_num_rows($check) == 0){
    @mysqli_query($conexao, "ALTER TABLE cadastro ADD COLUMN attempts INT NOT NULL DEFAULT 0");
}
$check2 = mysqli_query($conexao, "SHOW COLUMNS FROM cadastro LIKE 'blocked_until'");
if(mysqli_num_rows($check2) == 0){
    @mysqli_query($conexao, "ALTER TABLE cadastro ADD COLUMN blocked_until DATETIME NULL");
}

$res = mysqli_query($conexao, "SELECT * FROM cadastro WHERE nome = '$nome' LIMIT 1");
if(!$res || mysqli_num_rows($res) == 0){
    echo "Usuário não encontrado. <a href='index.html'>Voltar</a>";
    exit;
}

$user = mysqli_fetch_assoc($res);

// verificar bloqueio
if(!empty($user['blocked_until'])){
    $blocked_ts = strtotime($user['blocked_until']);
    if($blocked_ts > time()){
        $wait = $blocked_ts - time();
        echo "Conta bloqueada. Tente novamente em $wait segundos. <a href='index.html'>Voltar</a>";
        exit;
    }
}

if($senha === $user['senha']){
    // sucesso: resetar tentativas
    mysqli_query($conexao, "UPDATE cadastro SET attempts = 0, blocked_until = NULL WHERE nome = '$nome'");
    $_SESSION['nome'] = $nome;
    $_SESSION['logged'] = true;
    header('Location: menu.php');
    exit;
} else {
    // falha: incrementar tentativas
    $attempts = intval($user['attempts']) + 1;
    if($attempts >= 3){
        $blocked_until = date('Y-m-d H:i:s', time() + 300); // bloqueia 5 minutos
        mysqli_query($conexao, "UPDATE cadastro SET attempts = $attempts, blocked_until = '$blocked_until' WHERE nome = '$nome'");
        echo "Senha incorreta. Conta bloqueada por 5 minutos. <a href='index.html'>Voltar</a>";
    } else {
        mysqli_query($conexao, "UPDATE cadastro SET attempts = $attempts WHERE nome = '$nome'");
        $rest = 3 - $attempts;
        echo "Senha incorreta. Tentativas restantes: $rest. <a href='index.html'>Voltar</a>";
    }
    exit;
}

?>
