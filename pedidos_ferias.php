<?php
require_once("db/auth.php");
include_once("db/dataAccess.php");

$idFuncionario = $_SESSION['idFuncionario'];
$idArea = $_SESSION['idArea'];

$seccao = "Férias";
$paginaAtual = "PedidosFerias";
$tituloPagina = "Pedidos de Férias";

if (strpos($cargo, 'Chefe') !== 0 && strpos($cargo, 'Diretor') !== 0 && strpos($cargo, 'Conselho Administrativo') !== 0) {
    header("Location: dashboard.php");
    exit;
}

$da = new dataAccess();        
$res = $da->mostrarPedidosFerias($idArea, $cargo);
?>
<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
  <title>Pedidos de Férias</title>
  <link rel="stylesheet" href="ferias.css" />
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  <div class="app">
    <?php include_once("sidebar.php"); ?>
    <div class="main-wrapper">
      <?php include_once("navbar.php"); ?>
      <main class="main-content" id="main-content">
        <!--<section class="page-shell">-->
          <div class="ferias-wrap">
            <div class="ferias-card">
              <div class="ferias-head">
                <div>
                  <!--<h2>Pedidos de férias</h2>-->
                </div>
              </div>

              <div class="pedidosferias-toolbar">
                <button class="pedidosferias-filter active" type="button" data-filter="all">Todos</button>
                <button class="pedidosferias-filter" type="button" data-filter="pendente">Pendentes</button>
                <button class="pedidosferias-filter" type="button" data-filter="aprovado">Aceites</button>
                <button class="pedidosferias-filter" type="button" data-filter="rejeitado">Rejeitados</button>
              </div>

              <div class="pedidosferias-table-scroll">
                <table class="pedidosferias-table">
                  <thead>
                    <tr>
                      <th>Colaborador</th>
                      <th>Início</th>
                      <th>Fim</th>
                      <th>Dias</th>
                      <th>Estado</th>
                      <th>Ações</th>
                    </tr>
                  </thead>
                  <tbody id="pedidosTableBody">
                    <?php
                    if ( $res === -1 ) {
                      echo '<tr><td colspan="6">Erro ao carregar os pedidos.</td></tr>';
                    } else {
                      while ($row = mysqli_fetch_assoc($res)) {
                        $nome = $row['nome'];
                        $cargoColaborador = $row['cargo'];
                        $inicio = date('d/m/Y', strtotime($row['dataInicio']));
                        $fim = date('d/m/Y', strtotime($row['dataFim']));
                        $estado = strtolower(trim($row['estado']));
                        $pedidoId = $row['idPedido'];

                        $dias = 0;
                        $dataInicio = date_create($row['dataInicio']);
                        $dataFim = date_create($row['dataFim']);
                        if ($dataInicio && $dataFim) {
                            $intervalo = date_diff($dataInicio, $dataFim);
                            $dias = $intervalo->days + 1;
                        }

                        $partesNome = explode(' ', trim($nome));
                        $iniciais = '';
                        if (count($partesNome) >= 2) {
                            $iniciais = strtoupper(substr($partesNome[0], 0, 1) . substr(end($partesNome), 0, 1));
                        } else {
                            $iniciais = strtoupper(substr($nome, 0, 2));
                        }

                        $badgeClass = 'badge--warning';
                        $estadoLabel = 'Pendente';
                        $dataStatus = 'pendente';

                        if ($estado === 'aprovado') {
                            $badgeClass = 'badge--success';
                            $estadoLabel = 'Aceite';
                            $dataStatus = 'aprovado';
                        } elseif ($estado === 'rejeitado') {
                            $badgeClass = 'badge--danger';
                            $estadoLabel = 'Rejeitado';
                            $dataStatus = 'rejeitado';
                        }
                      ?>
                    <tr data-status="<?php echo $dataStatus; ?>">
                      <td>
                        <div class="pedidosferias-user">
                          <span class="avatar avatar--sm" aria-hidden="true"><?php echo htmlspecialchars($iniciais); ?></span>
                          <div>
                            <strong><?php echo htmlspecialchars($nome); ?></strong>
                            <small><?php echo htmlspecialchars($cargoColaborador); ?></small>
                          </div>
                        </div>
                      </td>
                      <td><?php echo $inicio; ?></td>
                      <td><?php echo $fim; ?></td>
                      <td><?php echo $dias; ?></td>
                      <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $estadoLabel; ?></span></td>
                      <td>
                        <div class="pedidosferias-actions">
                          <?php if ($dataStatus === 'pendente') { ?>
                          <form method="post" action="db/aceitarPedidoFerias.php">
                            <input type="hidden" name="pedido_id" value="<?php echo $pedidoId; ?>">
                            <button type="submit" class="pedidosferias-btn pedidosferias-btn-accept" onclick='return confirmarAceitar()'>Aceitar</button>
                          </form>

                          <form method="post" action="db/rejeitarPedidoFerias.php">
                            <input type="hidden" name="pedido_id" value="<?php echo $pedidoId; ?>">
                            <button type="submit" class="pedidosferias-btn pedidosferias-btn-reject" onclick='return confirmarRejeitar()'>Rejeitar</button>
                          </form>
                          <?php } ?>
                        </div>
                      </td>
                    </tr>
                    <?php
                    }
                    if (mysqli_num_rows($res) === 0) {
                      echo '<tr><td colspan="6">Não existem pedidos de férias.</td></tr>';
                    }
                    }  
                    ?>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
<script>
  function confirmarAceitar(){
    return confirm("Tem a certeza que quer Aceitar este Pedido?");
  }

  function confirmarRejeitar(){
    return confirm("Tem a certeza que quer Rejeitar este Pedido?");
  }

  document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.pedidosferias-filter');
    const rows = document.querySelectorAll('#pedidosTableBody tr');

    function filterRows(status) {
      rows.forEach(row => {
        const rowStatus = row.dataset.status;
        row.style.display = (status === 'all' || rowStatus === status) ? '' : 'none';
      });
    }

    buttons.forEach(button => {
      button.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        button.classList.add('active');
        filterRows(button.dataset.filter);
      });
    });

    filterRows('all');
  });
</script>
<script src="js/layout.js"></script>
</body>
</html>