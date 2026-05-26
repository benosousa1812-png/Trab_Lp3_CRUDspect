<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';

$repo = new UsuarioRepository();
$usuario = $repo->buscarPorId($_SESSION['usuario_id']);

if (!$usuario) {
    header('Location: index.php');
    exit;
}

$erro = '';
$sucesso = '';

// Processar atualização do perfil (sem redimensionamento)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $biografia = trim($_POST['biografia'] ?? '');
    $foto_path = $usuario->getFotoPerfil();
    
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $tipo_arquivo = $_FILES['foto_perfil']['type'];
        $extensoes_permitidas = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($tipo_arquivo, $extensoes_permitidas)) {
            // Limitar tamanho para 5MB
            if ($_FILES['foto_perfil']['size'] > 5 * 1024 * 1024) {
                $erro = "Imagem muito grande! Máximo 5MB.";
            } else {
                $upload_dir = __DIR__ . '/../uploads/perfil/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                if ($usuario->getFotoPerfil() && file_exists(__DIR__ . '/../' . $usuario->getFotoPerfil())) {
                    unlink(__DIR__ . '/../' . $usuario->getFotoPerfil());
                }
                
                $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
                $nome_arquivo = 'user_' . $usuario->getId() . '_' . uniqid() . '.' . $extensao;
                $caminho_relativo = 'uploads/perfil/' . $nome_arquivo;
                $caminho_absoluto = $upload_dir . $nome_arquivo;
                
                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_absoluto)) {
                    $foto_path = $caminho_relativo;
                    $sucesso = "Foto atualizada com sucesso!";
                } else {
                    $erro = "Erro ao salvar a foto.";
                }
            }
        } else {
            $erro = "Formato de imagem não permitido. Use JPG, PNG, GIF ou WEBP.";
        }
    }
    
    if (empty($erro)) {
        try {
            $usuario->setBiografia($biografia);
            $usuario->setFotoPerfil($foto_path);
            $repo->atualizarPerfil($usuario);
            if (empty($sucesso)) {
                $sucesso = "Perfil atualizado com sucesso!";
            }
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Meu Perfil</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro !== ''): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php if ($sucesso !== ''): ?>
  <div class="alert alert-sucesso"><?= htmlspecialchars($sucesso) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="POST" action="usuario_perfil.php" enctype="multipart/form-data">
    
    <div class="perfil-foto-container" style="text-align: center; margin-bottom: 30px;">
      <?php if ($usuario->getFotoPerfil() && file_exists(__DIR__ . '/../' . $usuario->getFotoPerfil())): ?>
        <img src="/Trab_Lp3/<?= $usuario->getFotoPerfil() ?>" 
             alt="Foto de perfil" 
             class="foto-perfil-grande">
      <?php else: ?>
        <div class="foto-perfil-grande-placeholder">
          🎭
        </div>
      <?php endif; ?>
      
      <div class="form-group" style="margin-top: 20px;">
        <label for="foto_perfil">Alterar foto de perfil</label>
        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/jpeg,image/png,image/gif,image/webp">
        <small style="display: block; margin-top: 5px; color: #666;">
          Formatos: JPG, PNG, GIF, WEBP. Máximo: 5MB
        </small>
      </div>
    </div>

    <div class="form-group">
      <label for="nome">Nome de usuário</label>
      <input type="text" id="nome" value="<?= htmlspecialchars($usuario->getNome()) ?>" disabled style="background: #e0e0e0;">
    </div>

    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" id="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" disabled style="background: #e0e0e0;">
    </div>

    <div class="form-group">
      <label for="biografia">Biografia</label>
      <textarea id="biografia" name="biografia" rows="5" style="width: 100%; padding: 10px; border: 2px solid #1a1a1a; font-family: 'Courier New', monospace;"><?= htmlspecialchars($usuario->getBiografia() ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label>Data de cadastro</label>
      <input type="text" value="<?= date('d/m/Y H:i', strtotime($usuario->getCriadoEm())) ?>" disabled style="background: #e0e0e0;">
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar alterações</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>