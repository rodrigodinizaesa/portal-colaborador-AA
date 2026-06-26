<?php 
session_start();

if(isset($_POST['password'])){
    $ncolaborador = $_POST['nColaborador'];
    $pwd = $_POST['password'];

    include_once("dataAccess.php");
    $da = new dataAccess();        

    $res = $da->login($ncolaborador);
    if(is_int($res) &&  $res == -1){
        echo "<script>alert('Something went wrong')</script>";
    }else{
        if (mysqli_num_rows($res) == 0){
            echo "<script>alert('Invalid colaborator number or password'); 
            window.location.href='../index.html';</script>";
            exit;
        }else{
            $row = mysqli_fetch_object($res);
            if(password_verify($pwd, $row->password)) {
                session_start();
                $_SESSION['idFuncionario'] = $row->idFuncionario;
                $_SESSION['idTipo'] = $row->idTipo;
                $_SESSION['nome'] = $row->nomeUtilizador;
                $_SESSION['email'] = $row->email;
                $_SESSION['cargo'] = $row->cargo;
                $_SESSION['idArea'] = $row->idArea;
                header("Location: ../dashboard.php");
                exit;
            } 
        }
        echo "<script>alert('Invalid colaborator number or password'); 
        window.location.href='../index.html';</script>";
        exit;
    }
}
?>
