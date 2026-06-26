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
    alert("voce ganhouaawdbiawudbibiabd");
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


const bossContainer = document.getElementById("boss_container");


// QUANDO CLICAR NO BOSS
bossContainer.addEventListener("click",()=>{

event.stopPropagation();

    if(ataqueSelecionado){


        console.log("Atacou o boss");


        batalha.boss.vida -= danoAtaque;


        console.log(
            "Vida do boss:",
            batalha.boss.vida
        );
        atualizarVidaBoss();

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
</script>
</body>
</html>