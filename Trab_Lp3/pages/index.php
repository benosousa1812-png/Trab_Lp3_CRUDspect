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
          <th>#</th>
          <th>Nome</th>
          <th>Classe</th>
          <th>Aspecto</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($personagens as $personagem): ?>
          <tr>
            <td style="text-align: center;">
              <?php if ($personagem->getImagem()): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($personagem->getImagem()) ?>" 
                     alt="<?= htmlspecialchars($personagem->getNome()) ?>"
                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 2px solid var(--ink);">
              <?php else: ?>
                <div style="width: 50px; height: 50px; background: var(--cream); border: 2px solid var(--ink); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                  🎭
                </div>
              <?php endif; ?>
            </td>
            <td><?= $personagem->getId() ?></td>
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