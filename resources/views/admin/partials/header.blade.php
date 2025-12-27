<header class="bg-white shadow-sm mb-0 d-flex align-items-center justify-content-between px-3 px-md-4"
    style="height: 60px; position: sticky; top: 0; z-index: 900;">

    <!-- 1. LEFT: Toggle Button & Title -->
    <div class="d-flex align-items-center">
        <button class="toggle-btn me-2 me-md-3 border border-none bg-white"
            onclick="toggleSidebar()"
            style="min-width: 40px; height: 40px; font-size: 1.2rem;">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- 2. CENTER: Live Search Bar (Desktop Only) -->
    <div class="flex-grow-1 mx-4 d-none d-md-block position-relative" style="max-width: 500px;">

        <!-- Input Form -->
        <form action="{{ route('admin.global.search') }}" method="GET">
            <div class="input-group bg-light rounded-pill border overflow-hidden">
                <input type="text"
                    id="adminSearchInput"
                    name="search"
                    class="form-control border-0 bg-transparent ps-4 shadow-none"
                    placeholder="Search products, orders, clients..."
                    value="{{ request('search') }}"
                    autocomplete="off">
                <button class="btn border-0 text-primary pe-3" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <!-- AJAX RESULTS DROPDOWN -->
        <div id="adminSearchResults" class="dropdown-menu w-100 shadow-lg border-0 rounded-4 mt-2 overflow-hidden p-0" style="display: none; position: absolute; top: 100%; left: 0; z-index: 1050;">
            <!-- Results injected by JS -->
        </div>
    </div>



    <!-- 3. RIGHT: Icons & Profile -->
    <div class="d-flex align-items-center gap-3">

        <!-- Mobile Search Icon (Visible only on Mobile) -->
        <a href="#" class="d-md-none text-dark" onclick="toggleMobileSearch()">
            <i class="bi bi-search fs-5"></i>
        </a>

        <!-- Message Icon -->
        <a href="{{ route('admin.messages.index') }}" class="header-icon position-relative text-dark" title="Messages">
            <i class="bi bi-envelope-fill fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                style="width: 8px; height: 8px;"></span>
        </a>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark fw-bold"
                data-bs-toggle="dropdown">
                <!-- Royal Blue Circle -->
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                    style="width: 35px; height: 35px; background-color: #405FA5;">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
                <span class="ms-2 d-none d-lg-block small">{{ Auth::user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear me-2"></i> Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- MOBILE SEARCH BAR (Hidden by default, toggles on click) -->
<div id="mobileSearchBar" class="bg-white border-bottom p-3 d-md-none" style="display: none; position: sticky; top: 60px; z-index: 899;">
    <form action="{{ route('admin.products.index') }}" method="GET">
        <div class="input-group bg-light rounded-pill border">
            <input type="text" name="search" class="form-control border-0 bg-transparent ps-3" placeholder="Search...">
            <button class="btn border-0 text-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div>

<!-- SCRIPT FOR MOBILE TOGGLE -->
<script>
    function toggleMobileSearch() {
        const bar = document.getElementById('mobileSearchBar');
        if (bar.style.display === 'none') {
            bar.style.display = 'block';
        } else {
            bar.style.display = 'none';
        }
    }
</script>

<!-- ============================================== -->
<!-- SCRIPT FOR LIVE SEARCH -->
<!-- ============================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById('adminSearchInput');
        const resultsBox = document.getElementById('adminSearchResults');
        let timeout = null;

        if (input) {
            input.addEventListener('keyup', function() {
                clearTimeout(timeout);
                const query = this.value.trim();

                // Hide if too short
                if (query.length < 2) {
                    resultsBox.style.display = 'none';
                    return;
                }

                // Debounce 300ms
                timeout = setTimeout(() => {
                    fetch("{{ route('admin.search.ajax') }}?q=" + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            let html = '';

                            if (data.length > 0) {
                                html += '<div class="list-group list-group-flush">';

                                data.forEach(item => {
                                    html += `
                                        <a href="${item.url}" class="list-group-item list-group-item-action d-flex align-items-center p-2 gap-3 border-bottom-0">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi ${item.icon} fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1" style="line-height: 1.2;">
                                                <div class="fw-bold text-dark small">${item.title}</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">${item.type} • ${item.subtitle}</small>
                                            </div>
                                            <i class="bi bi-arrow-right-short text-muted"></i>
                                        </a>
                                    `;
                                });

                                // "View All Results" Link at bottom
                                html += `
                                    <a href="{{ route('admin.global.search') }}?search=${query}" class="list-group-item list-group-item-action text-center bg-light text-primary fw-bold small py-2">
                                        View all results <i class="bi bi-search ms-1"></i>
                                    </a>
                                </div>`;

                                resultsBox.innerHTML = html;
                                resultsBox.style.display = 'block';
                            } else {
                                resultsBox.innerHTML = `
                                    <div class="p-4 text-center text-muted">
                                        <i class="bi bi-search display-6 opacity-25"></i>
                                        <p class="small mt-2 mb-0">No results found.</p>
                                    </div>`;
                                resultsBox.style.display = 'block';
                            }
                        })
                        .catch(err => console.error('Search Error:', err));
                }, 300);
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.style.display = 'none';
                }
            });
        }
    });
</script>