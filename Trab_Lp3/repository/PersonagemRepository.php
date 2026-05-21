<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Personagem.php';

class PersonagemRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    /** @return Personagem[] */
    public function listarPorUsuario(int $usuarioId): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM personagem WHERE usuario_id = :uid ORDER BY nome ASC'
        );
        $stmt->execute([':uid' => $usuarioId]);
        $lista = [];
        foreach ($stmt->fetchAll() as $dados) {
            $lista[] = new Personagem($dados);
        }
        return $lista;
    }

    public function buscarPorId(int $id): ?Personagem {
        $stmt = $this->pdo->prepare('SELECT * FROM personagem WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Personagem($dados);
        }

        return null;
    }

    public function salvar(Personagem $personagem): void {
        if ($personagem->getId() > 0) {
            $stmt = $this->pdo->prepare(
                'UPDATE personagem SET nome = :nome, classe = :classe, aspecto = :aspecto WHERE id = :id'
            );
            $stmt->execute([
                ':nome'    => $personagem->getNome(),
                ':classe'  => $personagem->getClasse(),
                ':aspecto' => $personagem->getAspecto(),
                ':id'      => $personagem->getId(),     
            ]);
            return;
        }

        if ($personagem->getUsuarioId() <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO personagem (nome, classe, aspecto, usuario_id) VALUES (:nome, :classe, :aspecto, :uid)'
        );
        $stmt->execute([
                ':nome'    => $personagem->getNome(),
                ':classe'  => $personagem->getClasse(),
                ':aspecto' => $personagem->getAspecto(),
                ':uid'      => $personagem->getUsuarioId(),
        ]);

        $personagem->registrarIdGerado((int) $this->pdo->lastInsertId());
    }

    public function inserir(string $nome, string $classe, string $aspecto, int $usuarioId): void {
        $personagem = Personagem::novo($nome, $classe, $aspecto, $usuarioId);
        $this->salvar($personagem);
    }

    public function atualizar(int $id, string $nome, string $classe, string $aspecto): void {
        $personagem = $this->buscarPorId($id);

        if ($personagem === null) {
            throw new RuntimeException('personagem não encontrado.');
        }

        $personagem->alterarDados($nome, $classe, $aspecto);
        $this->salvar($personagem);
    }

    public function excluir(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM personagem WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
