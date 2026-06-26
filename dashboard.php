<?php
require_once("db/auth.php"); 
include_once("db/dataAccess.php");

$idFuncionario = $_SESSION['idFuncionario'];

$paginaAtual = "Dashboard";
$tituloPagina = "Dashboard";

$da = new dataAccess();
$res = $da->getUltimosPedidos($idFuncionario);
?>

<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="dashboard.css" />
</head>
<body>
  <div class="app">
  <?php include_once("sidebar.php"); ?>
  <div class="main-wrapper">
    <?php include_once("navbar.php"); ?>
    <main class="main-content" id="main-content">
      <!-- Cabeçalho da página -->
      <div class="page-header">
        <div>
          <h1 class="page-title">Olá, <?php echo htmlspecialchars($nome); ?></h1>
          <p class="page-subtitle" id="live-date"></p>
        </div>
      </div>
      
      <div class="mid-row">
        <!-- Ações rápidas -->
        <section class="card" aria-labelledby="section-actions">
          <div class="card-head">
            <h2 id="section-actions" class="card-title">Ações Rápidas</h2>
          </div>
          <div class="quick-actions">
            <a href="ferias.php" class="quick-action">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              <span>Pedir Férias</span>
            </a>
            <a href="faltas.php" class="quick-action">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="12" y2="17"/></svg>
              <span>Ver/Justificar Faltas</span>
            </a>
            <a href="calendario.php" class="quick-action">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><polyline points="9 16 11 18 15 14"/></svg>
              <span>Ver Calendário de Férias</span>
            </a>
            <a href="perfil.php" class="quick-action">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              <span>Perfil</span>
            </a>
          </div>
        </section>
        
        <!-- Últimos Pedidos -->
        <section class="card" id="card-pedidos-recentes" aria-labelledby="section-pedidos">
        <div class="card-head">
          <h2 id="section-pedidos" class="card-title">Os Meus Últimos Pedidos</h2>
        </div>
        <ul class="pedidos-recentes" role="list">
          <?php
          if ($res && mysqli_num_rows($res) > 0):
            while ($p = mysqli_fetch_assoc($res)):
              $tipo = $p['tipo'];
              $estado = strtolower(trim($p['estado']));
              $data = date('d/m/Y', strtotime($p['dataPedido']));

              if ($estado === 'aprovado') {
                $badgeClass = 'tag--success';
                $estadoLabel = 'Aceite';
              } elseif ($estado === 'rejeitado') {
                $badgeClass = 'tag--danger';
                $estadoLabel = 'Rejeitado';
              } else {
                $badgeClass = 'tag--warning';
                $estadoLabel = 'Pendente';
              }
          ?>
          <li class="pedido-recente">
            <div class="pedido-recente-info">
              <span class="pedido-recente-tipo"><?php echo htmlspecialchars($tipo); ?></span>
              <time class="pedido-recente-data"><?php echo $data; ?></time>
            </div>
            <span class="tag <?php echo $badgeClass; ?>"><?php echo $estadoLabel; ?></span>
          </li>
          <?php
            endwhile;
          else:
          ?>
          <li class="pedidos-vazio">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <p>Ainda não tem pedidos submetidos.</p>
          </li>
          <?php endif; ?>
        </ul>
      </section>
      </div>
    </main>
  </div>
</div>
<script src="js/layout.js"></script>
<script>
/*Data automática*/
const liveDate = document.getElementById('live-date');
if (liveDate) {
  const s = new Date().toLocaleDateString('pt-PT', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
  liveDate.textContent = s.charAt(0).toUpperCase() + s.slice(1);
}
</script>
</body>
</html>