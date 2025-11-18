<?php
session_start();
if(empty($_SESSION['logged'])){
    header('Location: index.html');
    exit;
}

include 'perguntas.php';
if(!isset($_SESSION['quiz'])){

    $keys = array_keys($perguntas);
    shuffle($keys);
    $selected = [];
    for($i=0;$i<20 && isset($keys[$i]);$i++){
        $selected[] = $perguntas[$keys[$i]];
    }
    $_SESSION['quiz'] = $selected;
    $_SESSION['q_index'] = 0;
    $_SESSION['score'] = 0;
}

$index = &$_SESSION['q_index'];
$score = &$_SESSION['score'];
$questions = &$_SESSION['quiz'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $resp = isset($_POST['resposta']) ? $_POST['resposta'] : '';
    $corr = $questions[$index]['correta'];
    if($resp === $corr){
        $score++;
    }
    $index++;
}


if($index >= count($questions)){
    $final = $score;
   
    unset($_SESSION['quiz']);
    unset($_SESSION['q_index']);
    unset($_SESSION['score']);

    if($final >= 18){
        $msg = 'Parabéns, excelente!';
    } elseif($final >= 14){
        $msg = 'Bom trabalho, continue estudando!';
    } elseif($final >= 10){
        $msg = 'Razoável, estude mais para melhorar.';
    } else {
        $msg = 'Precisa estudar mais, tente novamente!';
    }
    echo "<!DOCTYPE html><html lang='pt-br'><head><meta charset='utf-8'><title>Resultado</title></head><body>";
    echo "<h2>Resultado final: $final / " . count(
        $perguntas) . "</h2>"; 
    echo "<p>$msg</p>";
    echo "<p><a href='menu.php'>Voltar ao menu</a></p>";
    echo "</body></html>";
    exit;
}

$q = $questions[$index];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quiz - Pergunta <?php echo $index + 1; ?></title>
  <style> body{font-family:Arial,Helvetica,sans-serif;padding:18px} .box{max-width:720px;margin:20px auto;padding:16px;border:1px solid #ddd;border-radius:8px} .op{display:block;margin:8px 0} </style>
</head>
<body>
  <div class="box">
    <h3>Pergunta <?php echo ($index + 1) . ' / ' . count($questions); ?></h3>
    <p><?php echo htmlspecialchars($q['pergunta']); ?></p>
    <form method="POST">
      <?php foreach($q['opcoes'] as $k => $v): ?>
        <label class="op"><input type="radio" name="resposta" value="<?php echo $k; ?>" required> <strong><?php echo $k; ?></strong> - <?php echo htmlspecialchars($v); ?></label>
      <?php endforeach; ?>
      <button type="submit">Confirmar</button>
    </form>
    <p>Placar atual: <?php echo $score; ?></p>
    <p><a href="menu.php">Cancelar e Voltar ao menu</a></p>
  </div>
</body>
</html>
