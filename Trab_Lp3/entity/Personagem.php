<?php

class Personagem {

    private int    $id;
    private string $nome;
    private string $classe;
    private string $aspecto;
    private int    $usuarioId;
    private ?string $imagem;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->nome      = $dados['nome']       ?? '';
        $this->classe    = $dados['classe']     ?? '';
        $this->aspecto   = $dados['aspecto']    ?? '';
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
        $this->imagem    = $dados['imagem'] ?? null; 
    }

    public function getId():        int    { return $this->id; }
    public function getNome():      string { return $this->nome; }
    public function getClasse():    string { return $this->classe; }
    public function getAspecto():   string { return $this->aspecto; }
    public function getUsuarioId(): int    { return $this->usuarioId; }
    public function getImagem():    ?string { return $this->imagem; }
    
    // Método para obter imagem em Base64 para exibição
    public function getImagemBase64(): ?string {
        if ($this->imagem) {
            return 'data:image/jpeg;base64,' . base64_encode($this->imagem);
        }
        return null;
    }

    public static function novo(string $nome, string $classe, string $aspecto, int $usuarioId, ?string $imagem = null): Personagem {
        if ($usuarioId <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $personagem = new Personagem(['usuario_id' => $usuarioId]);
        $personagem->alterarDados($nome, $classe, $aspecto, $imagem);

        return $personagem;
    }

    public function alterarDados(string $nome, string $classe, string $aspecto, ?string $imagem = null): void {
        $nome       = trim($nome);
        $classe     = trim($classe);
        $aspecto    = trim($aspecto);

        if ($nome === '' || $classe === '' || $aspecto === '') {
            throw new InvalidArgumentException('Nome, classe e aspecto são obrigatórios.');
        }

        $this->nome  = $nome;
        $this->classe  = $classe;
        $this->aspecto = $aspecto;
        
        
        if ($imagem !== null) {
            $this->imagem = $imagem;
        }
    }

    public function registrarIdGerado(int $id): void {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID inválido.');
        }

        $this->id = $id;
    }
}