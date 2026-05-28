<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';

$repo = new PersonagemRepository();
$personagens = $repo->listarPorUsuario($_SESSION['usuario_id']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="search-bar-container2">
  <div class="search-wrapper2">
    <span class="search-icon2">🔍</span>

    <input 
      type="text" 
      id="searchInput2"
      placeholder="Pesquisar personagem..."
    >

    <button id="clearSearch2" class="clear-search2">
      ✕
    </button>
  </div>

  <div id="searchResultCount2" class="search-result-count2"></div>
</div>

<div class="table-wrapper2">

<table class="data-table2" id="personagemTable2">

  <thead>
    <tr>
      <th>Selecionar</th>
      <th>Foto</th>
      <th>Nome</th>
      <th>Classe</th>
    </tr>
  </thead>

  <tbody>

    <?php foreach ($personagens as $personagem): ?>

      <tr>

        <td>
          <input 
            type="checkbox"
            class="select-personagem"
          >
        </td>

        <td>
          <img 
            src="/Trab_Lp3/<?= $personagem->getCaminhoImagem() ?>"
            class="personagem-avatar2"
          >
        </td>

        <td class="nome-personagem">
          <?= htmlspecialchars($personagem->getNome()) ?>
        </td>

        <td>
          <?= htmlspecialchars($personagem->getClasse()) ?>
        </td>

      </tr>

    <?php endforeach; ?>

  </tbody>

</table>

</div>

<div class="selected-area2">

    <h2>PERSONAGENS SELECIONADOS</h2>

    <div 
        id="selectedCharacters2"
        class="selected-grid2"
    >

    </div>

</div>

<script>

/* ===================================
   ELEMENTOS
=================================== */

const searchInput2 =
    document.getElementById('searchInput2');

const clearSearch2 =
    document.getElementById('clearSearch2');

const resultCount2 =
    document.getElementById('searchResultCount2');

const rows2 =
    document.querySelectorAll(
        '#personagemTable2 tbody tr'
    );

const selectedContainer =
    document.getElementById(
        'selectedCharacters2'
    );

const checkboxes =
    document.querySelectorAll(
        '.select-personagem'
    );



/* ===================================
   PESQUISA
=================================== */

searchInput2.addEventListener('input', () => {

    const termo =
        searchInput2.value.toLowerCase();

    let visibleCount = 0;

    rows2.forEach((row) => {

        const nome =
            row.querySelector('.nome-personagem')
            .innerText
            .toLowerCase();

        if(nome.includes(termo)){

            row.style.display = '';

            visibleCount++;

        } else {

            row.style.display = 'none';

        }

    });

    resultCount2.innerText =
        visibleCount + ' personagem(ns) encontrado(s)';



    /* BOTÃO LIMPAR */

    if(termo.length > 0){

        clearSearch2.style.display = 'flex';

    } else {

        clearSearch2.style.display = 'none';

        resultCount2.innerText = '';

    }

});



/* ===================================
   LIMPAR PESQUISA
=================================== */

clearSearch2.addEventListener('click', () => {

    searchInput2.value = '';

    rows2.forEach((row) => {

        row.style.display = '';

    });

    resultCount2.innerText = '';

    clearSearch2.style.display = 'none';

});



/* ===================================
   SELECIONAR PERSONAGENS
=================================== */

checkboxes.forEach((checkbox) => {

    checkbox.addEventListener('change', () => {

        updateSelectedCharacters();

    });

});



function updateSelectedCharacters(){

    selectedContainer.innerHTML = '';

    const selectedRows =
        document.querySelectorAll(
            '.select-personagem:checked'
        );

    selectedRows.forEach((checkbox) => {

        const row =
            checkbox.closest('tr');

        const nome =
            row.querySelector(
                '.nome-personagem'
            ).innerText;

        const imagem =
            row.querySelector('img').src;

        const card =
            document.createElement('div');

        card.classList.add(
            'selected-character-card2'
        );

        card.innerHTML = `
            <img src="${imagem}">
            <span>${nome}</span>
        `;

        selectedContainer.appendChild(card);

    });

}

</script>