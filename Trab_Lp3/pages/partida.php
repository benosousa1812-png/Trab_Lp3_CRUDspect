
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="partida_pag">

<div id="info"></div>

<script>
async function iniciarPartida() {
    const partida = JSON.parse(localStorage.getItem("partida"));

    if (!partida) {
        alert("Partida não encontrada!");
        return;
    }

    // Fundo
    const fundos = {
        deserto: "../assets/img/wall_deserto.png",
        floresta: "../assets/img/wall_floresta.png",
        montanha: "../assets/img/wall_montanha.png"
    };

    document.body.classList.add(partida.local);

    // Busca personagens via API
    try {
        const response = await fetch("partida_perso_pegar.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ personagens: partida.personagens })
        });

        if (!response.ok) throw new Error("Erro ao buscar personagens da partida");

        const personagens = await response.json();
        const container = document.getElementById("info");
        container.innerHTML = "";

        personagens.forEach(p => {
            const div = document.createElement("div");
            div.className = "personagem";
            div.innerHTML = `
                <img src="../assets/img2/${p.imagem}" alt="${p.nome}">
                <strong>${p.nome}</strong>
                <ul>
                    ${p.habilidades.map(h => `<li>${h.nome} - Dano: ${h.dano}</li>`).join("")}
                </ul>
            `;
            container.appendChild(div);
        });

    } catch (err) {
        console.error(err);
        document.getElementById("info").innerHTML = "Erro ao carregar personagens da partida.";
    }
}

// Executa a função
iniciarPartida();
</script>


</body>
</html>


