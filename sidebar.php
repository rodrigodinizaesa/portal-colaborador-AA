<head>
  <link rel="stylesheet" href="dashboard.css" />
</head>
<aside class="sidebar" id="sidebar" aria-label="Navegação principal">
  <div class="sidebar-header">
      <div class="sidebar-logo-text">
        <span class="logo-title">Arsenal do Alfeite S.A.</span>
        <span class="logo-sub">Área do Colaborador</span>
      </div>
    </a>

    <button class="sidebar-close" id="sidebar-close" aria-label="Fechar menu lateral">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <nav class="sidebar-nav" aria-label="Menu principal">
    <div class="nav-group">
      <p class="nav-group-label">Principal</p>
      <ul role="list">
        <li>
          <a href="dashboard.php" class="nav-link <?php if ($paginaAtual == 'Dashboard') echo 'active'; ?>"
            <?php if ($paginaAtual == 'dashboard') echo 'aria-current="page"'; ?>>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
              <rect x="3" y="3" width="7" height="7" rx="1.5"/>
              <rect x="14" y="3" width="7" height="7" rx="1.5"/>
              <rect x="3" y="14" width="7" height="7" rx="1.5"/>
              <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            <span>Dashboard</span>
          </a>
        </li>
        
        <li>
          <a href="faltas.php" class="nav-link <?php if ($paginaAtual == 'Faltas') echo 'active'; ?>"
            <?php if ($paginaAtual == 'Faltas') echo 'aria-current="page"'; ?>>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
              <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7z"/>
              <path d="M14 2v5h5"/>
              <path d="M9 13l2 2 4-4"/>
            </svg>
            <span>Faltas</span>
          </a>
        </li>

        <?php $feriasOpen = in_array($paginaAtual, ['Ferias', 'CalendarioFerias', 'PedidosFerias']);?>
        <li class="nav-group-submenu <?php echo $feriasOpen ? 'is-open' : ''; ?>">
          <button type="button" class="nav-link nav-link-toggle <?php echo $feriasOpen ? 'active' : ''; ?>"
            <?php echo $feriasOpen ? 'aria-expanded="true"' : 'aria-expanded="false"'; ?>>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
            <span>Férias</span>
            <svg class="submenu-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M6 9l6 6 6-6"/>
            </svg>
          </button>

          <ul class="submenu">
            <li><a href="calendario.php" class="submenu-link <?php echo $paginaAtual == 'CalendarioFerias' ? 'active' : ''; ?>" <?php echo $paginaAtual == 'CalendarioFerias' ? 'aria-current="page"' : ''; ?>>Ver Calendário geral</a></li>
            <li><a href="ferias.php" class="submenu-link <?php echo $paginaAtual == 'Ferias' ? 'active' : ''; ?>" <?php echo $paginaAtual == 'Ferias' ? 'aria-current="page"' : ''; ?>>Marcar férias</a></li>
            <?php if(isset($_SESSION['cargo']) && (strpos($_SESSION['cargo'], 'Chefe') === 0 || strpos($_SESSION['cargo'], 'Diretor') === 0)) { ?>
            <li><a href="pedidos_ferias.php" class="submenu-link <?php echo $paginaAtual == 'PedidosFerias' ? 'active' : ''; ?>" <?php echo $paginaAtual == 'PedidosFerias' ? 'aria-current="page"' : ''; ?>>Ver pedidos</a></li>
            <?php }?>
          </ul>
        </li>

        <?php if($_SESSION['idArea'] === '13') {?>
        <li>
          <a href="pedidos_fiscais.php" class="nav-link <?php if ($paginaAtual == 'PedidosDadosFiscais') echo 'active'; ?>"
            <?php if ($paginaAtual == 'PedidosDadosFiscais') echo 'aria-current="page"'; ?>>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
              <path d="M15 2v6h6"/>
              <path d="M8 13h8"/>
              <path d="M8 17h8"/>
            </svg>
            <span>Pedidos Fiscais</span>
          </a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </nav>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>

<script>
  document.querySelectorAll('.nav-link-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const parent = button.closest('.nav-group-submenu');
      const isOpen = parent.classList.contains('is-open');

      document.querySelectorAll('.nav-group-submenu').forEach(item => {
        item.classList.remove('is-open');
        const btn = item.querySelector('.nav-link-toggle');
        if (btn) btn.setAttribute('aria-expanded', 'false');
      });

      if (!isOpen) {
        parent.classList.add('is-open');
        button.setAttribute('aria-expanded', 'true');
      }
    });
  });
</script>