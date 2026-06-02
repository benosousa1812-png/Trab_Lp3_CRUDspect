<?php
session_start();

require_once __DIR__ . '/../repository/UsuarioRepository.php';
if(!isset($_SESSION['cadastro_temp']) || !isset ($_SESSION['codigo_confirmacao']))
    {
        header('Location: log_create.php');
        exit;
    }
$erro = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $codigoDigitado = trim($_POST['codigo']);
    if((string)$codigoDigitado === (string)$_SESSION['codigo_confirmacao']){
        echo "Código Correto";
        $dados = $_SESSION['cadastro_temp'];
        $repository = new UsuarioRepository();
        $repository->inserir(
            $dados['nome'],
            $dados['email'],
            $dados['senha']
        );
        unset($_SESSION['cadastro_temp']);
        unset($_SESSION['codigo_confirmacao']);

        header('Location: login.php');
        exit;
    } else{
        $erro = 'Código incorreto';
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Código</title>
</head>
<body>
    <h1>Confirmar Cadastro</h1>

 <?php
if ($erro !== '') {
    echo "<p>$erro</p>";
}
?>

<form method="POST">
    <input
    type="text"
    id="codigo"
    name="codigo"
    placeholder="Digite o código recebido"
    required
>
<button type ="submit"> Confirmar</button>
</form>

</body>
</html>