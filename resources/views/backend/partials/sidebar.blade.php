<div class="sidebar py-3" id="sidebar">
    <button onclick="toggleSidebar()" 
            class="btn btn-outline-light mb-3 w-100 text-white border-0 sidebar-toggle-btn">
        Toggle Sidebar
    </button>

    <input type="text" id="sidebarSearch" placeholder="Search..." class="form-control sidebar-search mb-3">

    <ul class="list-unstyled">

        {{-- Dashboard --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center" 
               href="{{ route('admin.dashboard') }}" onclick="setActiveLink(this)">
                <i class="fas fa-tachometer-alt me-3"></i>
                <span class="sidebar-link-title">Dashboard</span>
            </a>
        </li>

        {{-- Loan Types --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center"
               href="{{ route('admin.loan.type.index') }}" onclick="setActiveLink(this)">
                <i class="fas fa-list-alt me-3"></i>
                <span class="sidebar-link-title">Loan Types</span>
            </a>
        </li>
        {{-- Loan Name (NEW) --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center"
               href="{{ route('admin.loan.name.index') }}" onclick="setActiveLink(this)">
                <i class="fas fa-file-signature me-3"></i>
                <span class="sidebar-link-title">Loan Name</span>
            </a>
        </li>
        {{-- Clients --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center"
               href="{{ route('admin.registration.index') }}" onclick="setActiveLink(this)">
                <i class="fas fa-users me-3"></i>
                <span class="sidebar-link-title">Clients</span>
            </a>
        </li>

        {{-- Loan Applications --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center"
               href="{{ route('admin.loan.applications') }}" onclick="setActiveLink(this)">
                <i class="fas fa-file-invoice-dollar me-3"></i>
                <span class="sidebar-link-title">Loan Applications</span>
            </a>
        </li>

        {{-- Reports --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center"
               href="{{ route('admin.loan.approved') }}" onclick="setActiveLink(this)">
                <i class="fas fa-chart-line me-3"></i>
                <span class="sidebar-link-title">Loan Reports</span>
            </a>
        </li>
        {{-- Reports --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center"
               href="{{ route('admin.loan.given') }}" onclick="setActiveLink(this)">
                <i class="fas fa-chart-line me-3"></i>
                <span class="sidebar-link-title">Loan Given</span>
            </a>
        </li>

        {{-- Settings --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center"
               href="" onclick="setActiveLink(this)">
                <i class="fas fa-cog me-3"></i>
                <span class="sidebar-link-title">Settings</span>
            </a>
        </li>

        {{-- Logout --}}
        <li class="sidebar-list-item">
            <a class="sidebar-link d-flex align-items-center" href="{{route('admin.logout')}}">
                <i class="fas fa-sign-out-alt me-3"></i>
                <span class="sidebar-link-title">Logout</span>
            </a>
        </li>

    </ul>
</div>


<script>
  // Avoid JS errors if button exists but function was missing
  function toggleSidebar(){
    const el = document.getElementById('sidebar');
    el.classList.toggle('collapsed');
  }

  // Sidebar search (safe)
  (function(){
    const search = document.getElementById('sidebarSearch');
    if(!search) return;
    search.addEventListener('input', function(){
      const term = (this.value || '').toLowerCase().trim();
      document.querySelectorAll('#sidebar .sidebar-list-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = term ? (text.includes(term) ? 'block' : 'none') : 'block';
      });
    });
  })();

  function toggleSubmenu(id, trigger){
    const submenu = document.getElementById(id);
    if(!submenu) return;
    submenu.classList.toggle('d-none');
    const chevron = trigger.querySelector('.fa-chevron-right');
    if(chevron) chevron.classList.toggle('rotate-icon');
  }

  function setActiveLink(link){
    document.querySelectorAll('#sidebar .sidebar-link').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
  }
  function setActiveSubmenuLink(link){
    document.querySelectorAll('#sidebar .submenu .sidebar-link').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
  }
</script>

<style>
  :root{
    --deepblue:#02152E;      /* match header; change once to recolor all */
    --deepblue-2:#0a2a52;    /* lighter shade for inputs */
    --hover:#0b203f;
    --active:#12824A;        /* active pill color */
    --ink:#fff;
  }

  .sidebar{
    width:260px;
    background:var(--deepblue);
    color:var(--ink);
    padding:15px;
    border-radius:10px;
    height:100vh;           /* make it full-height */
    position:sticky; top:0; /* stick while page scrolls */
    overflow-y:auto;        /* scroll inside sidebar if long */
    box-shadow:0 0 15px rgba(0,0,0,.22);
  }
  .sidebar.collapsed{ width:72px; }
  .sidebar.collapsed .sidebar-link-title,
  .sidebar.collapsed .submenu{ display:none !important; }
  .sidebar.collapsed .fa-chevron-right{ display:none; }

  .sidebar-toggle-btn{
    background:linear-gradient(90deg,var(--deepblue-2),var(--deepblue));
    border:1px solid rgba(255,255,255,.15);
    border-radius:8px;
    transition:.2s;
  }
  .sidebar-toggle-btn:hover{ background:var(--hover); }

  .sidebar-list-item{ margin-bottom:10px; }

  .sidebar-link{
    color:var(--ink);
    display:flex; align-items:center;
    padding:10px; border-radius:8px;
    text-decoration:none;
    transition:background .2s,color .2s;
  }
  .sidebar-link:hover{ background:var(--hover); }
  .sidebar-link.active{ background:var(--active); }

  .submenu{ padding-left:20px; }
  .fa-chevron-right{ transition:transform .25s; }
  .rotate-icon{ transform:rotate(90deg); }

  .sidebar-search{
    background:var(--deepblue-2);
    color:#fff;
    border:1px solid rgba(255,255,255,.2);
    border-radius:8px;
  }
  .sidebar-search::placeholder{ color:#c9d1d9; }
</style>