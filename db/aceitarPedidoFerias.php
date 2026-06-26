<?php
require_once("auth.php"); 
if (isset($_POST['pedido_id'])) {
    $pedidoId = $_POST['pedido_id'];
    $idResponsavel = $_SESSION['idFuncionario'];

    include_once("dataAccess.php");
    $da = new dataAccess();
    $res = $da->aceitarPedidoFerias($pedidoId, $idResponsavel);
    if ($res == -1)
        echo "<script>alert('Erro Contacte o administrador.');</script>";
    else
        echo "<script>alert('Sucesso');</script>";
}

echo "<script>window.location.href='../pedidos_ferias.php';</script>";
?>