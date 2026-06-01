<?php
session_start();

echo "<h1>Confirmação</h1>";
echo "<p>Código gerado:</p>";
echo "<h2>" . $_SESSION['codigo_confirmacao'] . "</h2>";