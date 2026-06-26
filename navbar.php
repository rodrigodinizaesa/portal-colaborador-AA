<head>
  <link rel="stylesheet" href="dashboard.css" />
</head>
<header class="navbar" role="banner">
  <div class="navbar-left">
    <button class="hamburger" id="hamburger" aria-label="Abrir menu" aria-expanded="false" aria-controls="sidebar">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>

    <nav aria-label="Localização atual">
      <ol class="breadcrumb" role="list">
        <li><a href="dashboard.php">Portal</a></li>
        <li aria-hidden="true">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 18l6-6-6-6"/>
          </svg>
        </li>
        <?php if (!empty($seccao)) : ?>
        <li><a><?php echo htmlspecialchars($seccao); ?></a></li>
        <li aria-hidden="true">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 18l6-6-6-6"/>
          </svg>
        </li>
        <?php endif; ?>
        <li><span aria-current="page"><?php echo htmlspecialchars($tituloPagina ?? 'Dashboard'); ?></span></li>
      </ol>
    </nav>
  </div>

  <div class="navbar-right">
    <button class="navbar-icon-btn" data-theme-toggle aria-label="Alternar tema claro/escuro">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
      </svg>
    </button>

    <button class="navbar-icon-btn notif-btn" aria-label="Notificações (2 novas)">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
      <span class="notif-dot" aria-hidden="true"></span>
    </button>

    <div class="user-chip" id="user-chip" role="button" tabindex="0"
         aria-haspopup="true" aria-expanded="false"
         aria-label="Menu do utilizador">

      <div class="avatar avatar--md" aria-hidden="true"><?php echo htmlspecialchars($iniciais); ?></div>

      <div class="user-chip-info">
        <span class="user-chip-name"><?php echo htmlspecialchars($nome); ?></span>
        <span class="user-chip-role"><?php echo htmlspecialchars($cargo); ?></span>
      </div>

      <svg class="user-chip-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <path d="M6 9l6 6 6-6"/>
      </svg>

      <div class="user-dropdown" role="menu" aria-label="Opções do utilizador">
        <div class="dropdown-profile">
          <div class="avatar avatar--lg" aria-hidden="true"><?php echo htmlspecialchars($iniciais); ?></div>
          <div>
            <p class="dropdown-profile-name"><?php echo htmlspecialchars($nome); ?></p>
            <p class="dropdown-profile-email"><?php echo htmlspecialchars($email); ?></p>
          </div>
        </div>

        <div class="dropdown-divider"></div>

        <a href="perfil.php" class="dropdown-item" role="menuitem">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
          O Meu Perfil
        </a>
        
        <a class="dropdown-item dropdown-item--danger" role="menuitem" href="logout.php" onclick='return confirm("Tem a certeza que quer terminar sessão?")'>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          Terminar Sessão
        </a>
      </div>
    </div>
  </div>
</header>