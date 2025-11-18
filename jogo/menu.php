<?php
session_start();
if(empty($_SESSION['logged'])){
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menu - Jogo Quiz</title>
  <link rel="stylesheet" href="style.css">
  <style> .menu{max-width:500px;margin:40px auto;padding:16px;border:1px solid #ccc;border-radius:8px} </style>
</head>
<body>
  <div class="menu">
    <h2>Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h2>
    <ul>
      <li><a href="quiz.php">Iniciar Jogo/Quiz</a></li>
      <li><a href="#" onclick="alert('Placar será implementado (opcional).')">Ver Placar</a></li>
      <li><a href="logout.php">Sair / Logout</a></li>
    </ul>
  </div>
</body>
</html>
