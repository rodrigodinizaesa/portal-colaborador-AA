<?php
if (session_status() === PHP_SESSION_NONE) { // VER ISTO
    session_start(); // VER ISTO
}

if (!isset($_SESSION['idFuncionario'])) {
    header("Location: index.html");
    exit();
}

$nomeCompleto = $_SESSION['nome'];
$email = $_SESSION['email'];
$cargo = $_SESSION['cargo'];

function nomeCurto($nome) {
    $nome = trim(preg_replace('/\s+/', ' ', $nome));
    if ($nome === '') return 'Utilizador';

    $partes = explode(' ', $nome);
    if (count($partes) === 1) return $partes[0];

    return $partes[0] . ' ' . $partes[count($partes) - 1];
}


function iniciaisAvatar($nome) {
    $nome = trim((string)$nome);
    if ($nome === '') return 'U';

    $partes = preg_split('/\s+/', $nome);
    $iniciais = '';

    foreach ($partes as $parte) {
        if ($parte !== '') {
            $iniciais .= mb_strtoupper(mb_substr($parte, 0, 1, 'UTF-8'), 'UTF-8');
        }
        if (mb_strlen($iniciais, 'UTF-8') === 2) break;
    }

    return $iniciais ?: 'U';
}

$nome = nomeCurto($nomeCompleto);
$iniciais = iniciaisAvatar($nome);
?>