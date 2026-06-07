<div id="info"></div>

<script>
const partida = JSON.parse(localStorage.getItem("partida"));



document.getElementById("info").innerHTML = `
    <h2>Dificuldade: ${partida.dificuldade}</h2>
    <h2>Local: ${partida.local}</h2>
    <h3>Personagens:</h3>
    ${partida.personagens.map(p => `
        <p>${p.nome}</p>
    `).join("")}
`;
</script>