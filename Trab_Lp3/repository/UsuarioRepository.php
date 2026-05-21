<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Usuario.php';

class UsuarioRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorEmail(string $email): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Usuario($dados);
        }

        return null;
    }
      
    public function inserir(string $nome, string $email, string $senhaHash): void {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM usuario WHERE email = :email');
        $stmt->execute([':email' => $email]);
        
        if ($stmt->fetchColumn() > 0) {
            throw new RuntimeException('E-mail já cadastrado.');
        }

        // Insere o novo usuário
        $stmt = $this->pdo->prepare(
            'INSERT INTO usuario (nome, email, senha, criado_em) VALUES (:nome, :email, :senha, NOW())'
        );
        $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':senha' => $senhaHash
        ]);
    }


}
