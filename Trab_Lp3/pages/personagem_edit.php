<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';

$repo = new PersonagemRepository();

$id = 0;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
}

$personagem = null;
if ($id > 0) {
    $personagem = $repo->buscarPorId($id);
}

if ($personagem === null || $personagem->getUsuarioId() !== $_SESSION['usuario_id']) {
    header('Location: index.php');
    exit;
}

$erro = '';
$nome = $personagem->getNome();
$classe = $personagem->getClasse();
$aspecto = $personagem->getAspecto();

$classes = ['Cavaleiro(a)', 'Escudeiro(a)', 'Vidente', 'Mago(a)', 'Ladrão(a)',
          'Ladino(a)', 'Servo(a)', 'Sílfide / Silfo', 'Bruxo(a)', 'Herdeiro(a)', 'Príncipe / Princesa',
          'Bardo(a)', 'Lorde', 'Musa'];

          $aspectos = ['Respiração', 'Sangue', 'Vida', 'Ruína', 'Luz', 'Vazio', 'Tempo', 'Espaço', 'Mente', 'Coração', 'Odio', 'Esperança'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $classe  = trim($_POST['classe'] ?? '');
    $aspecto = trim($_POST['aspecto'] ?? '');

    try {
        $personagem->alterarDados($nome, $classe, $aspecto);
        $repo->salvar($personagem);

        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Editar Personagem</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro !== ''): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="POST" action="personagem_edit.php?id=<?= $personagem->getId() ?>">

    <div class="form-group">
      <label for="nome">Nome do Personagem</label>
      <input
        type="text"
        id="nome"
        name="nome"
        placeholder="Ex: John Egbert"
        value="<?= htmlspecialchars($nome) ?>"
        required
      />
    </div>

    <div class="form-group">
      <label for="classe">Classe</label>
      <select id="classe" name="classe" required>
        <option value="">Selecione a classe...</option>
        <?php foreach ($classes as $t): ?>
          <?php
            $selecionado = '';
            if ($classe === $t) {
                $selecionado = 'selected';
            }
          ?>
          <option value="<?= $t ?>" <?= $selecionado ?>>
            <?= $t ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="aspecto">aspecto</label>
      <select id="aspecto" name="aspecto" required>
        <option value="">Selecione o aspecto...</option>
        <?php foreach ($aspectos as $t): ?>
          <?php
            $selecionado = '';
            if ($aspecto === $t) {
                $selecionado = 'selected';
            }
          ?>
          <option value="<?= $t ?>" <?= $selecionado ?>>
            <?= $t ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar alterações</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?> 
