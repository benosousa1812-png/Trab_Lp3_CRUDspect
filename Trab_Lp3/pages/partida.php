<?php
require_once __DIR__ . '/../includes/auth.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Partida RPG</title>

    <link rel="stylesheet" href="../assets/style.css">
    <script src="../bosses/bosseshab.js"></script>

</head>

<body class="partida_pag">

<div id="turno_container">
    Vez do jogadordwwadw
</div>

<div id="ataque_boss_container">

    <div id="ataque_boss_caixa">

        <button id="fechar_ataque">X</button>

        <h2 id="nome_ataque"></h2>

        <p id="resultado_ataque"></p>

    </div>

</div>

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
    turno:"jogador",
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
        vida:0,
        vidaMax:0
    }
};
let ataqueSelecionado = false;
let danoAtaque = 0;

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
function turnoboss(){

    batalha.turno = "boss";
    atualizar_contai();
    ataque_do_boss();


}
function ataque_do_boss()
{
 let partida = JSON.parse(localStorage.getItem("partida"));


    let bossAtual;



    if(partida.local == "deserto"){

        bossAtual = bosses.deserto;

    }

    else if(partida.local == "floresta"){

        bossAtual = bosses.floresta;

    }

    else{

        bossAtual = bosses.montanha;

    }



    let habilidade = 
    bossAtual.habilidades[
        Math.floor(
            Math.random() * bossAtual.habilidades.length
        )
    ];



    let resultado = "";



    if(habilidade.tipo == "ataque_area"){



        batalha.jogadores.forEach(jogador=>{


            jogador.vida -= habilidade.dano;


            resultado +=
            `${jogador.nome} perdeu ${habilidade.dano} de vida<br>`;


        });


    }



    else if(habilidade.tipo == "ataque"){



        let alvo = Math.floor(Math.random()*3);



        batalha.jogadores[alvo].vida -= habilidade.dano;



        resultado +=
        `${batalha.jogadores[alvo].nome} perdeu ${habilidade.dano} de vida`;



    }



    mostrar_ataque_boss(
        habilidade.nome,
        resultado
    );
    atualizarVidaJogadores();
    verificarDerrota();
}
function mostrar_ataque_boss(nome, resultado){


    document.getElementById("nome_ataque").innerText =
    "Boss usou: " + nome;


    document.getElementById("resultado_ataque").innerHTML =
    resultado;


    document.getElementById("ataque_boss_container")
    .style.display = "block";


}
function atualizar_contai(){
const caixa = document.getElementById("turno_container")

if(batalha.turno == "jogador")
{
    caixa.innerText = "vez do jogador";

}
else{
    caixa.innerText = "vez do boss";
}

}
function atualizarVidaJogadores(){

    const personagens = document.querySelectorAll(".personagem");


    batalha.jogadores.forEach((jogador,index)=>{


        let personagem = personagens[index];


        if(!personagem) return;


        let barra = personagem.querySelector(".vida.player");

        let texto = personagem.querySelector(".vida_texto");


        let porcentagem = 
        (jogador.vida / jogador.vidaMax) * 100;


        if(porcentagem < 0){
            porcentagem = 0;
        }


        barra.style.width = porcentagem + "%";


        texto.textContent =
        `Vida: ${jogador.vida}/${jogador.vidaMax}`;



        // MORREU
        if(jogador.vida <= 0){

            personagem.style.opacity = "0.4";

            personagem.style.pointerEvents = "none";

            personagem.classList.add("morto");

        }


    });


}
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

    mostrar_resultado(
    "VITÓRIA",
    "O boss foi derrotado!"
);


setTimeout(()=>{

    window.location.href="../pages/index.php";

},3000);
    

}
}
function verificarDerrota(){


    let mortos = batalha.jogadores.filter(j=>j.vida <=0);


    if(mortos.length == 3){


        mostrar_resultado(
            "DERROTA",
            "Todos os jogadores foram derrotados!"
        );


        setTimeout(()=>{

            window.location.href="../pages/index.php";

        },3000);


    }


}
function mostrar_resultado(titulo,texto){


    document.getElementById("nome_ataque").innerText =
    titulo;


    document.getElementById("resultado_ataque").innerHTML =
    texto;


    document
    .getElementById("ataque_boss_container")
    .style.display="block";


}


async function iniciarPartida(){

    const partida = JSON.parse(localStorage.getItem("partida"));
    if (partida.dificuldade == "Fácil")
    {
        batalha.boss.vida = 200;
        batalha.boss.vidaMax = 200;
    }
    else if (partida.dificuldade == "Médio")
    {
         batalha.boss.vida = 400;
        batalha.boss.vidaMax = 400;
    }
    else
    {
    batalha.boss.vida = 600;
    batalha.boss.vidaMax = 600;
    }


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
            `;
            div.addEventListener("click",()=>{
                containerHabilidades.innerHTML="";
                p.habilidades.forEach(h=>{
                    const li = document.createElement("li");
                    li.textContent = 
                    `${h.nome} - Tipo: ${h.tipo} - Dano: ${h.dano} - Cura: ${h.cura ?? 0}`;
                    containerHabilidades.appendChild(li);
                    li.addEventListener("click",(event)=>{
                        event.stopPropagation();
                        ver_ação(
                            h.nome,
                            h.tipo,
                            h.dano,
                            h.cura
                        );
                    });
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

atualizar_contai();


const bossContainer = document.getElementById("boss_container");


// QUANDO CLICAR NO BOSS
bossContainer.addEventListener("click",(event)=>{

    event.stopPropagation();

    if(ataqueSelecionado && batalha.turno == "jogador"){


        console.log("Atacou o boss");


        batalha.boss.vida -= danoAtaque;

        console.log(
            "Vida do boss:",
            batalha.boss.vida
        );
        atualizarVidaBoss();
        if(batalha.boss.vida <= 0){

            return;

        }
        turnoboss();

        // resetar ataque
        ataqueSelecionado = false;

        danoAtaque = 0;


        bossContainer.classList.remove("ataque_ativo");


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


    }


});




// ATIVA O MODO DE ATAQUE
function ativarAtaqueBoss(dano){


    ataqueSelecionado = true;


    danoAtaque = dano;


    bossContainer.classList.add("ataque_ativo");


}



// RECEBE A HABILIDADE CLICADA
function ver_ação(nome,tipo,dano,cura){


    console.log("Nome:",nome);
    console.log("Tipo:",tipo);
    console.log("Dano:",dano);



    if(tipo === "Ataque"){


        ativarAtaqueBoss(dano);


    }


}

document
.getElementById("fechar_ataque")
.addEventListener("click",()=>{


    document
    .getElementById("ataque_boss_container")
    .style.display="none";


    batalha.turno="jogador";


    atualizar_contai();

console.log(batalha.jogadores);
});
</script>
</body>
</html>