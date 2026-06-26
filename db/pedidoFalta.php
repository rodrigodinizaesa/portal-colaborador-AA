<?php 
if(isset($_POST['idFaltas'])){
    require_once("auth.php");
    $idFuncionario = $_SESSION['idFuncionario'];

    $idFaltas = $_POST['idFaltas'];
    $motivo = $_POST['justificacao'];
    $comprovativo = $_FILES['comprovativo'];

    $extensao = strtolower(pathinfo($_FILES['comprovativo']['name'], PATHINFO_EXTENSION));
    // formatos permitidos
    $permitidos = ['pdf', 'jpg', 'jpeg', 'png'];
    
    if (!in_array($extensao, $permitidos)) {
        echo "<script>alert('Formato do ficheiro nao permitido');</script>";
        echo "<script>window.location.href='../faltas.php'</script>";
        exit;
    }

    $data = date('Ymd');
    $nome = $data . '_' . $iniciais . '_' . $idFuncionario . '.' . $extensao;

    $caminho = "C:/xampp/uploads/" . $nome;
    
    if (!move_uploaded_file($comprovativo['tmp_name'], $caminho)) {
        echo "<script>alert('Erro ao guardar o ficheiro.');</script>";
        echo "<script>window.location.href='../faltas.php'</script>";
        exit;
    }

    include_once("dataAccess.php");
    $da = new dataAccess();
    $res = $da->criarPedidoFalta($idFaltas, $motivo, $caminho);
    if ( $res == -1 )
        echo "<script>alert('Erro Contacte o administrador.')</script>";
    else 
        echo "<script>alert('Sucesso')</script>";
}

echo "<script>window.location.href='../faltas.php'</script>";

?>