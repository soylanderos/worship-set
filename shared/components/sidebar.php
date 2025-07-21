<!-- Top Navbar para móviles -->
<header class="mobile-navbar">
    <button class="menu-toggle-btn">
        <span class="material-symbols-rounded">menu</span>
    </button>
    <h1 class="mobile-navbar-title">Menu</h1>
</header>

<!-- Sidebar existente -->
<aside class="sidebar">
    
    <nav class="sidebar-nav">
        <ul class="nav-list primary-nav">
            <li class="nav-item sidebar-item"><a href="#" class="nav-link"><span class="material-symbols-rounded">dashboard</span><span class="nav-label">Dashboard</span></a></li>
            <li class="nav-item sidebar-item" data-controller="admin" id="fetch_users"><a href="#" class="nav-link"><span class="material-symbols-rounded">group</span><span class="nav-label">Members</span></a></li>
            <li class="nav-item sidebar-item" data-controller="admin" id="fetch_teams"><a href="#" class="nav-link"><span class="material-symbols-rounded">diversity_3</span><span class="nav-label">Teams</span></a></li>
            <li class="nav-item sidebar-item" data-controller="services" id="fetch_services"><a href="#" class="nav-link"><span class="material-symbols-rounded">church</span><span class="nav-label">Services</span></a></li>
            <li class="nav-item sidebar-item" data-controller="admin" id="fetch_events"><a href="#" class="nav-link"><span class="material-symbols-rounded">event</span><span class="nav-label">Events</span></a></li>
            <li class="nav-item sidebar-item" data-controller="songs" id="fetch_songs"><a href="#" class="nav-link"><span class="material-symbols-rounded">music_note</span><span class="nav-label">Songs</span></a></li>
            <li class="nav-item sidebar-item"><a href="#" class="nav-link"><span class="material-symbols-rounded">folder</span><span class="nav-label">Files</span></a></li>
            <li class="nav-item sidebar-item"><a href="#" class="nav-link"><span class="material-symbols-rounded">settings</span><span class="nav-label">Settings</span></a></li>
        </ul>

        <ul class="nav-list secondary-nav">
            <li class="nav-item"><a href="#" class="nav-link"><span class="material-symbols-rounded">logout</span><span class="nav-label">Logout</span></a></li>
        </ul>
    </nav>
</aside>
