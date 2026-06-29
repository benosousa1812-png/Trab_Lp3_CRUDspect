<?php
require_once __DIR__ . '/../includes/auth.php';

?>

<!DOCTYPE html>
<html lang="en">
<audio id="bgMusic" loop autoplay>
    <source src="../assets/musicas_boss/Showtime_Imp.mp3" type="audio/mpeg">
    Seu navegador não suporta áudio.
</audio>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Partida RPG</title>

    <link rel="stylesheet" href="../assets/style.css">

</head>

<body class="partida_pag">

<div id="info"></div>

<div id="boss_container">
<!-- logica do boss na func iniciarpartida na linha 100-->
</div>

<div id="habilidades-container">

    <ul id="habilidades-list"></ul>

</div>

<script>

// GUARDA A VIDA DA PARTIDA
let batalha = {
    jogadores:[

        {
            id:1,
            nome:"",
            vida:100,
            vidaMax:100
        },

        {
            id:2,
            nome:"",
            vida:100,
            vidaMax:100
        },

        {
            id:3,
            nome:"",
            vida:100,
            vidaMax:100
        }
    ],

    boss:{
        id:"boss",
        nome:"Boss",
        vida:200,
        vidaMax:200
    }
};

let ataqueSelecionado = false;
let danoAtaque = 0;
let modoSelecaoAlvo = false; // Indica se estamos selecionando alvo para cura
let habilidadeCuraPendente = null; // Guarda a habilidade de cura pendente
let jogadorSelecionado = -1; // Guarda qual jogador está selecionado para ações

// CRIA A BARRA DE VIDA
function criarBarraVida(atual,max,tipo){
    let porcentagem = (atual / max) * 100;

    return `

        <div class="vida_texto">

            Vida: ${atual}/${max}

        </div>

        <div class="barra">
            <div 
            class="vida ${tipo}" 
            style="width:${porcentagem}%">
            </div>
        </div>
    `;
}

// ATUALIZA A VIDA DO BOSS
function atualizarVidaBoss(){

    const barraVida = document.querySelector(".vida.boss");
    const textoVida = document.querySelector(".boss_vida .vida_texto");
    if (batalha.boss.vida>0)
    {
    let porcentagem = 
    (batalha.boss.vida / batalha.boss.vidaMax) * 100;

    barraVida.style.width = porcentagem + "%";

    textoVida.textContent =
    `Vida: ${batalha.boss.vida}/${batalha.boss.vidaMax}`;
    }
    else
    {
        vitoria=1;
       

    barraVida.style.width = "0%";

    textoVida.textContent =
    `Vida: ${"0"}/${batalha.boss.vidaMax}`;
    alert("🎉 VOCÊ VENCEU A BATALHA! 🎉");
    }
}

// ATUALIZA A VIDA DOS JOGADORES
function atualizarVidaJogadores() {
    const personagens = document.querySelectorAll('.personagem');
    personagens.forEach((div, index) => {
        if (index < batalha.jogadores.length) {
            const jogador = batalha.jogadores[index];
            const barra = div.querySelector('.vida.player');
            const texto = div.querySelector('.vida_texto');
            if (barra && texto) {
                let porcentagem = (jogador.vida / jogador.vidaMax) * 100;
                barra.style.width = porcentagem + "%";
                texto.textContent = `Vida: ${jogador.vida}/${jogador.vidaMax}`;
            }
        }
    });
}

// APLICA CURA EM UM JOGADOR ESPECÍFICO
function aplicarCura(jogadorIndex, quantidade) {
    const jogador = batalha.jogadores[jogadorIndex];
    if (!jogador || jogador.vida <= 0) {
        alert("Este personagem está morto!");
        return false;
    }
    
    const curaReal = Math.min(quantidade, jogador.vidaMax - jogador.vida);
    jogador.vida += curaReal;
    console.log(`${jogador.nome} curou ${curaReal} de vida!`);
    
    atualizarVidaJogadores();
    
    // Feedback visual - destaca o personagem curado
    const divs = document.querySelectorAll('.personagem');
    if (divs[jogadorIndex]) {
        divs[jogadorIndex].style.border = '3px solid #a8e6cf';
        setTimeout(() => {
            divs[jogadorIndex].style.border = '';
        }, 1000);
    }
    
    return true;
}

// INICIA O MODO DE SELEÇÃO DE ALVO PARA CURA
function iniciarSelecaoAlvo(nomeHabilidade, quantidadeCura) {
    modoSelecaoAlvo = true;
    habilidadeCuraPendente = {
        nome: nomeHabilidade,
        cura: quantidadeCura
    };
    
    const containerHabilidades = document.getElementById('habilidades-list');
    containerHabilidades.innerHTML = `
        <li style="background: #2b7a3e; color: white; text-align: center; font-weight: bold; padding: 10px; list-style: none; border-radius: 8px;">
            💚 ${nomeHabilidade} - Selecione um alvo para curar
        </li>
        <li style="text-align: center; color: #e8e507; font-size: 0.9rem; padding: 5px; list-style: none;">
            Clique em um personagem abaixo para aplicar a cura
        </li>
        <li style="text-align: center; color: #ff6b6b; font-size: 0.8rem; padding: 5px; cursor: pointer; list-style: none;" onclick="cancelarSelecaoAlvo()">
            ❌ Cancelar
        </li>
    `;
    
    // Destaca os personagens vivos para seleção
    const divs = document.querySelectorAll('.personagem');
    divs.forEach((div, index) => {
        const jogador = batalha.jogadores[index];
        if (jogador && jogador.vida > 0) {
            div.style.border = '3px solid #e8e507';
            div.style.cursor = 'pointer';
            div.style.boxShadow = '0 0 15px rgba(232, 229, 7, 0.3)';
            div.dataset.alvo = 'true';
        } else if (jogador && jogador.vida <= 0) {
            div.style.opacity = '0.4';
            div.style.cursor = 'not-allowed';
            div.dataset.alvo = 'false';
        }
    });
}

// CANCELA A SELEÇÃO DE ALVO
function cancelarSelecaoAlvo() {
    modoSelecaoAlvo = false;
    habilidadeCuraPendente = null;
    
    // Remove os destaques
    const divs = document.querySelectorAll('.personagem');
    divs.forEach(div => {
        div.style.border = '';
        div.style.cursor = 'pointer';
        div.style.boxShadow = '';
        div.style.opacity = '1';
        div.dataset.alvo = 'false';
    });
    
    // Restaura a lista de habilidades
    const containerHabilidades = document.getElementById('habilidades-list');
    containerHabilidades.innerHTML = '';
}

// ATIVA O MODO DE ATAQUE
function ativarAtaqueBoss(dano){
    ataqueSelecionado = true;
    danoAtaque = dano;
    bossContainer.classList.add("ataque_ativo");
    document.querySelector('#habilidades-list').innerHTML = '<li style="text-align:center; color:#e8e507; font-weight:bold; list-style: none;">⚔️ Clique no BOSS para atacar!</li>';
}

// RECEBE A HABILIDADE CLICADA
function ver_ação(nome, tipo, dano, cura){
    console.log("Nome:", nome);
    console.log("Tipo:", tipo);
    console.log("Dano:", dano);
    console.log("Cura:", cura);

    // Se for uma habilidade de Cura
    if(tipo === "Cura" || tipo === "cura") {
        // Verifica se a habilidade tem cura
        if (!cura || cura <= 0) {
            alert("Esta habilidade não tem cura!");
            return;
        }
        
        // Inicia o modo de seleção de alvo
        iniciarSelecaoAlvo(nome, cura);
        return;
    }

    // Se for uma habilidade de Ataque
    if(tipo === "Ataque" || tipo === "ataque") {
        if (batalha.boss.vida <= 0) {
            alert("O boss já foi derrotado!");
            return;
        }
        ativarAtaqueBoss(dano);
    }

    // Se for Buff (opcional)
    if(tipo === "Buff" || tipo === "buff") {
        if (jogadorSelecionado === -1) {
            alert("Selecione um personagem primeiro!");
            return;
        }
        const jogador = batalha.jogadores[jogadorSelecionado];
        if (jogador && jogador.vida > 0) {
            jogador.buff = (jogador.buff || 0) + 10;
            console.log(`${jogador.nome} recebeu +10 de buff!`);
            const divPersonagem = document.querySelectorAll('.personagem')[jogadorSelecionado];
            if (divPersonagem) {
                divPersonagem.style.border = '3px solid #ffd93d';
                setTimeout(() => {
                    divPersonagem.style.border = '';
                }, 1000);
            }
        }
    }
}

async function iniciarPartida(){

    const partida = JSON.parse(localStorage.getItem("partida"));

    if(!partida){
        alert("Partida não encontrada!");
        return;
    }
    document.body.classList.add(partida.local);

    const bossContainer = document.getElementById("boss_container");
    if(partida.local === "deserto"){
        bossContainer.innerHTML =
        '<img src="../bosses/deserto/imagem do deserto.png">';
    }
    else if(partida.local === "floresta"){
        bossContainer.innerHTML =
        '<img src="../bosses/floresta/boss_floresta.png">';
    }
    else if(partida.local === "montanha"){
        bossContainer.innerHTML =
        '<img src="../bosses/montanha/boss_montanha.png">';
    }
    // barra do boss embaixo da imagem
    bossContainer.innerHTML += `
        <div class="boss_vida">
        ${criarBarraVida(
            batalha.boss.vida,
            batalha.boss.vidaMax,
            "boss"
        )}
        </div>
    `;
    try{
        const response = await fetch("partida_perso_pegar.php",{
            method:"POST",
            headers:{
                "Content-Type":"application/json"
            },
            body:JSON.stringify({
                personagens:partida.personagens
            })
        });
        if(!response.ok)

            throw new Error("Erro ao buscar personagens");

        const personagens = await response.json();

        const containerPersonagens =
        document.getElementById("info");

        const containerHabilidades =
        document.getElementById("habilidades-list");

        containerPersonagens.innerHTML="";
        containerHabilidades.innerHTML="";
        personagens.forEach((p,index)=>{
            const jogador = batalha.jogadores[index];
            jogador.nome = p.nome;
            const div = document.createElement("div");
            div.className="personagem";
            div.dataset.index = index;
            div.innerHTML = `
                <img src="../${p.imagem}" alt="${p.nome}">
                <strong>

                Jogador ${jogador.id} - ${p.nome}

                </strong>
                ${criarBarraVida(
                    jogador.vida,
                    jogador.vidaMax,
                    "player"
                )}
                ${jogador.buff ? `<span style="display:block;color:#e8e507;font-size:0.8rem;">💪 Buff: +${jogador.buff}</span>` : ''}
            `;
            div.addEventListener("click", (event) => {
                event.stopPropagation();
                
                // Se estiver no modo de seleção de alvo (cura)
                if (modoSelecaoAlvo) {
                    const jogadorAlvo = batalha.jogadores[index];
                    if (!jogadorAlvo || jogadorAlvo.vida <= 0) {
                        alert("Este personagem está morto!");
                        return;
                    }
                    
                    // Aplica a cura no alvo selecionado
                    const sucesso = aplicarCura(index, habilidadeCuraPendente.cura);
                    if (sucesso) {
                        // Limpa o modo de seleção
                        modoSelecaoAlvo = false;
                        const nomeCura = habilidadeCuraPendente.nome;
                        habilidadeCuraPendente = null;
                        
                        // Remove os destaques
                        const divs = document.querySelectorAll('.personagem');
                        divs.forEach(div => {
                            div.style.border = '';
                            div.style.cursor = 'pointer';
                            div.style.boxShadow = '';
                            div.style.opacity = '1';
                            div.dataset.alvo = 'false';
                        });
                        
                        // Mostra mensagem de sucesso na lista de habilidades
                        const containerHabilidades = document.getElementById('habilidades-list');
                        containerHabilidades.innerHTML = `
                            <li style="background: #2b7a3e; color: #a8e6cf; text-align: center; font-weight: bold; padding: 10px; list-style: none; border-radius: 8px;">
                                ✅ ${nomeCura} aplicada com sucesso!
                            </li>
                        `;
                        setTimeout(() => {
                            containerHabilidades.innerHTML = '';
                        }, 2000);
                    }
                    return;
                }
                
                // Seleção normal do personagem (para ataques/buffs)
                jogadorSelecionado = index;
                
                // Remove seleção anterior
                document.querySelectorAll('.personagem').forEach(el => {
                    el.style.border = '';
                });
                div.style.border = '3px solid #e8e507';
                
                containerHabilidades.innerHTML="";
                
                // Adiciona cabeçalho com o nome do jogador
                const header = document.createElement("li");
                header.style.background = "#2b7a3e";
                header.style.color = "white";
                header.style.textAlign = "center";
                header.style.fontWeight = "bold";
                header.style.padding = "10px";
                header.style.listStyle = "none";
                header.style.borderRadius = "8px";
                header.textContent = `🎯 ${p.nome} - Selecione uma habilidade`;
                containerHabilidades.appendChild(header);
                
                p.habilidades.forEach(h=>{
                    const li = document.createElement("li");
                    let tipoIcon = "⚔️";
                    let cor = "#ffffff";
                    
                    if (h.tipo === "Cura" || h.tipo === "cura") {
                        tipoIcon = "💚";
                        cor = "#a8e6cf";
                    } else if (h.tipo === "Ataque" || h.tipo === "ataque") {
                        tipoIcon = "⚔️";
                        cor = "#ff6b6b";
                    } else if (h.tipo === "Buff" || h.tipo === "buff") {
                        tipoIcon = "💪";
                        cor = "#ffd93d";
                    }
                    
                    let texto = `${tipoIcon} ${h.nome}`;
                    if (h.dano > 0) texto += ` - Dano: ${h.dano}`;
                    if (h.cura > 0) texto += ` - Cura: ${h.cura}`;
                    
                    li.textContent = texto;
                    li.style.color = cor;
                    li.style.cursor = "pointer";
                    li.style.padding = "8px 12px";
                    li.style.margin = "4px 0";
                    li.style.border = "1px solid #333";
                    li.style.borderRadius = "4px";
                    li.style.listStyle = "none";
                    li.style.transition = "all 0.2s";
                    
                    li.addEventListener("mouseenter", function() {
                        this.style.background = "rgba(255,255,255,0.1)";
                    });
                    li.addEventListener("mouseleave", function() {
                        this.style.background = "transparent";
                    });
                    
                    li.addEventListener("click",(event)=>{
                        event.stopPropagation();
                        ver_ação(
                            h.nome,
                            h.tipo,
                            h.dano,
                            h.cura
                        );
                    });
                    containerHabilidades.appendChild(li);
                });
            });
            containerPersonagens.appendChild(div);
        });
    }

    catch(err){
        console.error(err);
        document.getElementById("info").innerHTML =
        "Erro ao carregar personagens da partida.";

    }

}

iniciarPartida();


const bossContainer = document.getElementById("boss_container");


// QUANDO CLICAR NO BOSS
bossContainer.addEventListener("click",(event)=>{
    event.stopPropagation();

    if(ataqueSelecionado){
        console.log("Atacou o boss");
        batalha.boss.vida -= danoAtaque;
        console.log("Vida do boss:", batalha.boss.vida);
        atualizarVidaBoss();

        // resetar ataque
        ataqueSelecionado = false;
        danoAtaque = 0;
        bossContainer.classList.remove("ataque_ativo");
        
        // Restaura a lista de habilidades
        document.querySelector('#habilidades-list').innerHTML = '';
    }
});


// CLIQUE FORA CANCELA O ATAQUE
document.addEventListener("click",(event)=>{
    if(!ataqueSelecionado){
        return;
    }

    if(!event.target.closest("#boss_container")){
        console.log("Ataque cancelado");
        ataqueSelecionado = false;
        danoAtaque = 0;
        bossContainer.classList.remove("ataque_ativo");
        document.querySelector('#habilidades-list').innerHTML = '';
    }
});

</script>
</body>
</html>