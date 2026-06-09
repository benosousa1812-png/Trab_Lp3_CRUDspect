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

    <!-- HABILIDADES FIXAS -->
    <div id="habilidades-container">
        <ul id="habilidades-list"></ul>
    </div>

    <script>
        async function iniciarPartida() {
            const partida = JSON.parse(localStorage.getItem("partida"));

            if (!partida) {
                alert("Partida não encontrada!");
                return;
            }

            // Aplica classe de fundo
            document.body.classList.add(partida.local);

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