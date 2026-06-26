<?php
require_once("dataAccess.php");

date_default_timezone_set('Europe/Lisbon');

$dataHoje = date('Y-m-d');
$entradaEsperada = $dataHoje . ' 09:00:00';
$saidaEsperada = $dataHoje . ' 17:00:00';

$da = new dataAccess();
$funcionarios = $da->getFuncionariosParaVerificar($dataHoje);

if ($funcionarios != -1) {
    while ($func = mysqli_fetch_assoc($funcionarios)) {
        $idFuncionario = (int)$func['id'];

        $resRegisto = $da->getRegistoDoDia($idFuncionario, $dataHoje);
        $registo = ($resRegisto != -1) ? mysqli_fetch_assoc($resRegisto) : null;

        if (!$registo) {
            $dataInicioFalta = $entradaEsperada;
            $dataFimFalta = $saidaEsperada;
            $tipoFalta = 'diaria';
            $motivoFalta = 'Ausencia de Picagem';

            $resFalta = $da->existeFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $motivoFalta);

            if ($resFalta != -1 && mysqli_num_rows($resFalta) == 0) {
                $da->inserirFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $tipoFalta, $motivoFalta);
            }

            continue;
        }

        $dataEntrada = $registo['dataEntrada'];
        $dataSaida = $registo['dataSaida'];

        if (empty($dataEntrada) && empty($dataSaida)) {
            $dataInicioFalta = $entradaEsperada;
            $dataFimFalta = $saidaEsperada;
            $tipoFalta = 'diaria';
            $motivoFalta = 'Ausencia de Picagem';

            $resFalta = $da->existeFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $motivoFalta);

            if ($resFalta != -1 && mysqli_num_rows($resFalta) == 0) {
                $da->inserirFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $tipoFalta, $motivoFalta);
            }

            continue;
        }

        if (!empty($dataEntrada) && strtotime($dataEntrada) > strtotime($entradaEsperada)) {
            $dataInicioFalta = $entradaEsperada;
            $dataFimFalta = $dataEntrada;
            $tipoFalta = 'parcial';
            $motivoFalta = 'Entrada apos hora limite';

            $resFalta = $da->existeFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $motivoFalta);

            if ($resFalta != -1 && mysqli_num_rows($resFalta) == 0) {
                $da->inserirFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $tipoFalta, $motivoFalta);
            }
        }

        if (empty($dataSaida)) {
            $dataInicioFalta = $saidaEsperada;
            $dataFimFalta = $saidaEsperada;
            $tipoFalta = 'parcial';
            $motivoFalta = 'Picagem de saida em falta';

            $resFalta = $da->existeFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $motivoFalta);

            if ($resFalta != -1 && mysqli_num_rows($resFalta) == 0) {
                $da->inserirFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $tipoFalta, $motivoFalta);
            }

            continue;
        }

        if (!empty($dataSaida) && strtotime($dataSaida) < strtotime($saidaEsperada)) {
            $dataInicioFalta = $dataSaida;
            $dataFimFalta = $saidaEsperada;
            $tipoFalta = 'parcial';
            $motivoFalta = 'Saida antes da hora prevista';

            $resFalta = $da->existeFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $motivoFalta);

            if ($resFalta != -1 && mysqli_num_rows($resFalta) == 0) {
                $da->inserirFalta($idFuncionario, $dataInicioFalta, $dataFimFalta, $tipoFalta, $motivoFalta);
            }
        }
    }
}
?>