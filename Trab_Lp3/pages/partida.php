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

    <!-- PERSONAGENS -->
    <div id="info"></div>

    <!-- Boss -->
    <div id="boss_container">
        <!-- aqui fazer a logica de aparecer cada foto do boss dependendo de qual tema foi escolhido na partida (obs as imagens estao na pasta bosses com suas habilidades) !-->
       
    </div>

    <!-- HABILIDADES FIXAS -->
    <div id="habilidades-container">
        <ul id="habilidades-list"></ul>
    </div>

    <script>
        async function iniciarPartida() {
            //pega os dados q fez no partida_create (pers, dif e local) pra usar
            const partida = JSON.parse(localStorage.getItem("partida"));

            if (!partida) {
                alert("Partida não encontrada!");
                return;
            }

            // Aplica classe de fundo
            document.body.classList.add(partida.local);
            // mostra a fotinha do boss na direita (depende do tema escolhido no partuda_create)
            const bossContainer = document.getElementById("boss_container");

            if (partida.local === "deserto") {
                bossContainer.innerHTML =
                    '<img src="../bosses/deserto/imagem do deserto.png" alt="Boss Deserto">';
            }
            else if (partida.local === "floresta") {
                bossContainer.innerHTML =
                    '<img src="../bosses/floresta/boss_floresta.png" alt="Boss Floresta">';
            }
            else if (partida.local === "montanha") {
                bossContainer.innerHTML =
                    '<img src="../bosses/montanha/boss_montanha.png" alt="Boss Montanha">';
            }

            

            try {
                // Busca personagens via API
                const response = await fetch("partida_perso_pegar.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ personagens: partida.personagens })
                });

                if (!response.ok) throw new Error("Erro ao buscar personagens da partida");

                const personagens = await response.json();

                const containerPersonagens = document.getElementById("info");
                const containerHabilidades = document.getElementById("habilidades-list");

                containerPersonagens.innerHTML = "";
                containerHabilidades.innerHTML = "";

                personagens.forEach(p => {
                    const div = document.createElement("div");
                    div.className = "personagem";

                    div.innerHTML = `
                        <img src="../${p.imagem}" alt="${p.nome}">
                        <strong>${p.nome}</strong>
                    `;

                    // Ao clicar no personagem, mostra suas habilidades com dano e cura
                    div.addEventListener("click", () => {
                    containerHabilidades.innerHTML = "";
                    p.habilidades.forEach(h => {
                    const li = document.createElement("li");
                    li.textContent = `${h.nome} - Tipo: ${h.tipo} - Dano: ${h.dano} - Cura: ${h.cura ?? 0}`;
                    containerHabilidades.appendChild(li);
                        });
                    });

                    containerPersonagens.appendChild(div);
                });

            } catch (err) {
                console.error(err);
                document.getElementById("info").innerHTML = "Erro ao carregar personagens da partida.";
            }
        }

        iniciarPartida();
    </script>
</body>
</html>