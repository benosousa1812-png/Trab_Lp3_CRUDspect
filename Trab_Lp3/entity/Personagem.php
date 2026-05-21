<?php

class Personagem {

    private int    $id;
    private string $nome;
    private string $classe;
    private string $aspecto;
    private int    $usuarioId;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->nome      =        $dados['nome']       ?? '';
        $this->classe    =        $dados['classe']     ?? '';
        $this->aspecto   =        $dados['aspecto']    ?? '';
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
    }

    public function getId():        int    { return $this->id; }
    public function getNome():      string { return $this->nome; }
    public function getClasse():    string { return $this->classe; }
    public function getAspecto():   string { return $this->aspecto; }
    public function getUsuarioId(): int    { return $this->usuarioId; }

    public static function novo(string $nome, string $classe, string $aspecto, int $usuarioId): Personagem {
        if ($usuarioId <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $personagem = new Personagem(['usuario_id' => $usuarioId]);
        $personagem->alterarDados($nome, $classe, $aspecto);

        return $personagem;
    }

    public function alterarDados(string $nome, string $classe, string $aspecto): void {
        $nome       = trim($nome);
        $classe     = trim($classe);
        $aspecto    = trim($aspecto);
         echo($nome . $classe . $aspecto);

        if ($nome === '' || $classe === '' || $aspecto === '') {
            throw new InvalidArgumentException('Nome, classe e aspecto são obrigatórios.');
        }

        $this->nome  = $nome;
        $this->classe  = $classe;
        $this->aspecto = $aspecto;
    }

    public function registrarIdGerado(int $id): void {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID inválido.');
        }

        $this->id = $id;
    }
}
