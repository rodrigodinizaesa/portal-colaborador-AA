<?php
$caminho = $_GET['comprovativo'] ?? '';

//só permite ficheiros dentro da pasta de uploads
$pastaPermitida = realpath("C:/xampp/uploads/");
$caminhoReal = realpath($caminho);

if (!$caminhoReal || strpos($caminhoReal, $pastaPermitida) !== 0 || !file_exists($caminhoReal)) {
    http_response_code(404);
    exit("Ficheiro não encontrado.");
}

$extensao = strtolower(pathinfo($caminhoReal, PATHINFO_EXTENSION)); //formatos permitidos
$mimeTypes = [
    'pdf'  => 'application/pdf',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
];

$mime = $mimeTypes[$extensao] ?? 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($caminhoReal));

readfile($caminhoReal);
exit;
?>