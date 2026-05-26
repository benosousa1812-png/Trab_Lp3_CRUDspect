<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';

$repo     = new PersonagemRepository();
$personagens = $repo->listarPorUsuario($_SESSION['usuario_id']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Meus Personagens god tier</h2>
  <a href="personagem_create.php" class="btn btn-primary">+ Novo personagem</a>
  
   <?php if (!empty($personagens)): ?>
    <a href="partida_create.php" class="btn btn-success">
      Iniciar Partida
    </a>
  <?php endif; ?>
</div>

<?php if (empty($personagens)): ?>
  <div class="empty-state">
    <p>Você ainda não cadastrou nenhum personagem.</p>
    <a href="personagem_create.php" class="btn btn-primary">Cadastrar agora</a>
  </div>
<?php else: ?>
  <div class="table-wrapper">
    <table class="data-table">
      <thead>
        <tr>
          <th>Foto</th>
          <th></th>
          <th>Nome</th>
          <th>Classe</th>
          <th>Aspecto</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($personagens as $personagem): ?>
          <tr>
            <!-- Na tabela, onde exibe a foto, mude para: -->
<td style="text-align: center; vertical-align: middle;">
  <?php if ($personagem->getCaminhoImagem() && file_exists(__DIR__ . '/../' . $personagem->getCaminhoImagem())): ?>
    <img src="/Trab_Lp3/<?= $personagem->getCaminhoImagem() ?>" 
         alt="<?= htmlspecialchars($personagem->getNome()) ?>"
         class="personagem-avatar"
         style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #1a1a1a;">
  <?php else: ?>
    <div class="personagem-avatar-placeholder" style="width: 45px; height: 45px; background: #d3d3d3; border: 2px solid #1a1a1a; display: inline-flex; align-items: center; justify-content: center;">
      🎭
    </div>
  <?php endif; ?>
</td>
            <td><?= '' ?></td>
            <td><strong><?= htmlspecialchars($personagem->getNome()) ?></strong></td>
            <td><span class="badge"><?= htmlspecialchars($personagem->getClasse()) ?></span></td>
            <td><span class="badge"><?= htmlspecialchars($personagem->getAspecto()) ?></span></td>
            <td class="acoes">
              <a href="personagem_edit.php?id=<?= $personagem->getId() ?>" class="btn btn-sm btn-editar">Editar</a>
              <a href="personagem_delete.php?id=<?= $personagem->getId() ?>" class="btn btn-sm btn-excluir">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>