<!DOCTYPE html>
<html lang="id">


<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <!-- Load critical styles early to avoid flash of unstyled content -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css" integrity="" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.0/css/all.min.css" integrity="" crossorigin="anonymous">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Load styles and scripts dynamically using Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<!-- Vite will load bundled JS (includes Bootstrap/AdminLTE) -->

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <!-- Modern Toast Notifications Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 70px;">
        @if(session('success'))
            <div class="toast custom-toast toast-success show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center gap-2">
                    <div class="toast-icon-wrapper rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; min-width: 28px; background-color: #ffffff !important; color: #10b981 !important;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1 toast-text">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white ms-auto" aria-label="Close" onclick="const toast = this.closest('.custom-toast'); toast.classList.add('hide-toast'); toast.addEventListener('animationend', () => toast.remove());"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast custom-toast toast-error show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center gap-2">
                    <div class="toast-icon-wrapper rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; min-width: 28px; background-color: #ffffff !important; color: #ef4444 !important;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="flex-grow-1 toast-text">{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white ms-auto" aria-label="Close" onclick="const toast = this.closest('.custom-toast'); toast.classList.add('hide-toast'); toast.addEventListener('animationend', () => toast.remove());"></button>
                </div>
            </div>
        @endif
    </div>

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand px-4 py-2" style="background: #ffffff; border-bottom: none; box-shadow: none;">
            <div class="d-flex w-100 align-items-center">
                <button type="button" id="sidebarToggle" class="btn btn-link d-lg-none mobile-sidebar-toggle me-2" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="d-flex align-items-center me-auto ms-2">
                    @stack('navbar_left')
                </div>

                <!-- Right-side profile/email/notification group -->
                <div class="d-flex align-items-center ms-auto gap-3">
                    @if(Auth::check() && Auth::user()->role !== 'eksekutif')
                    <!-- Notification Bell (left-most of the right group) -->
                    <div class="notification-bell-container position-relative">
                        <button type="button" class="btn btn-link notification-bell" id="notificationBell" style="text-decoration: none; color: #666; padding: 0; border: 0;">
                            <svg style="width: 20px; height: 20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="notification-badge badge bg-danger" id="notificationBadge" style="display: none; position: absolute; top: -5px; right: -5px;">0</span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div class="notification-dropdown dropdown-menu dropdown-menu-end" id="notificationDropdown" style="display: none; min-width: 360px;">
                            <div class="notification-header px-3 py-2">
                                <h6 class="mb-0" style="font-weight: 700; color: #2f4159;">Deadline</h6>
                            </div>
                            <div class="notification-list" id="notificationList" style="max-height: 300px; overflow-y: auto;">
                                <div class="px-3 py-2 text-muted text-center" style="font-size: 0.85rem;">Memuat...</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Profile Dropdown -->
                    <div class="position-relative">
                        <a href="#" class="d-flex align-items-center gap-2" id="profileDropdownTrigger" style="text-decoration: none; color: inherit; cursor: pointer;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                                class="rounded-circle border-2"
                                alt="User Avatar"
                                width="40" height="40"
                                style="border-color: #000;">
                            <div class="text-start profile-text" style="text-align: left;">
                                <span class="d-block profile-email" style="font-size: 12px; font-weight: 600; color: #2f4159;">{{ Auth::user()->email }}</span>
                                <small class="text-muted" style="font-size: 11px; color: #999; text-transform: capitalize;">Bagian {{ Auth::user()->role }}</small>
                            </div>
                        </a>

                        <!-- Dropdown Menu -->
                        <div id="profileDropdownMenu" class="dropdown-menu dropdown-menu-end shadow-sm" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 0.5rem; background: #ffffff; border: 1px solid #e9ecef; border-radius: 8px; min-width: 180px; z-index: 1050; padding: 0.25rem 0; box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2" style="background: none; border: none; width: 100%; text-align: left; color: #334155; font-size: 15px; font-weight: 500; padding: 0.75rem 1.25rem; transition: background 0.15s ease, color 0.15s ease;" onmouseover="this.style.background='#f8fafc'; this.style.color='#1e293b';" onmouseout="this.style.background='none'; this.style.color='#334155';">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="color: #334155; margin-right: 2px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 11-12.728 0M12 3v9" />
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar elevation-4 app-sidebar">
            <!-- Logo -->
            <strong>
                <a href="{{ Auth::check() ? route(Auth::user()->role . '.dashboard') : '#' }}" class="brand-link text-center app-brand-link">
                    <img src="{{ asset('images/sp-logo.png') }}" alt="Logo SP" style="height: 38px; width: auto;">
                    <span>SiProjek</span>
                </a>
            </strong>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column app-sidebar-menu" role="menu">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ Auth::check() ? route(Auth::user()->role . '.dashboard') : '/dashboard' }}" class="nav-link {{ request()->routeIs('*.dashboard') || request()->is('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th"></i>
                                <p class="menu-text">Dashboard</p>
                            </a>
                        </li>

                        <!-- Penawaran -->
                        @if(Auth::check() && in_array(Auth::user()->role, ['administrasi', 'eksekutif']))
                        <li class="nav-item">
                            <a href="{{ route(Auth::user()->role . '.penawaran.index') }}" class="nav-link {{ request()->routeIs('*.penawaran.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p class="menu-text">Penawaran</p>
                            </a>
                        </li>
                        @endif

                        <!-- Deal -->
                        @if(Auth::check() && in_array(Auth::user()->role, ['administrasi', 'eksekutif', 'keuangan']))
                        <li class="nav-item">
                            <a href="{{ route(Auth::user()->role . '.deal.index') }}" class="nav-link {{ request()->routeIs('*.deal.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-handshake"></i>
                                <p class="menu-text">Deal</p>
                            </a>
                        </li>
                        @endif

                        <!-- Tagihan (Keuangan) -->
                        @if(Auth::check() && Auth::user()->role === 'keuangan')
                        <li class="nav-item">
                            <a href="{{ route('keuangan.tagihan.index') }}" class="nav-link {{ request()->routeIs('*.tagihan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p class="menu-text">Tagihan</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content -->
        <div class="content-wrapper p-4">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer text-center" style="padding: 0.75rem 1rem !important; height: 45px !important; display: flex; align-items: center; justify-content: center; background: #ffffff !important; border-top: 1px solid #dee2e6 !important;">
            <strong>&copy; 2026 SiProjek.</strong>
        </footer>
    </div>

    <script>
        // Define global openModalCustom to bypass any event delegation bindings
        window.openModalCustom = function(targetId) {
            const targetModalEl = document.querySelector(targetId);
            if (!targetModalEl) {
                console.error('Modal element not found:', targetId);
                return;
            }

            console.log('openModalCustom called for:', targetId);
            let success = false;

            // Try Bootstrap 5 Modal API
            if (window.bootstrap && window.bootstrap.Modal) {
                try {
                    let modalInstance = bootstrap.Modal.getInstance(targetModalEl);
                    if (!modalInstance) {
                        modalInstance = new bootstrap.Modal(targetModalEl);
                    }
                    modalInstance.show();
                    success = true;
                    console.log('Opened via Bootstrap 5 API');
                    return;
                } catch (err) {
                    console.warn('Bootstrap 5 modal show failed, trying jQuery:', err);
                }
            }

            // Try jQuery Bootstrap 4 Modal API
            if (window.$ && typeof window.$.fn.modal === 'function') {
                try {
                    window.$(targetModalEl).modal('show');
                    success = true;
                    console.log('Opened via jQuery Bootstrap API');
                    return;
                } catch (err) {
                    console.error('jQuery modal show failed:', err);
                }
            }

            // Absolute fallback: manually toggle CSS visibility if JS libraries are blocked
            if (!success) {
                try {
                    targetModalEl.style.display = 'block';
                    targetModalEl.classList.add('show');
                    targetModalEl.style.backgroundColor = 'rgba(0,0,0,0.5)';
                    targetModalEl.style.opacity = '1';
                    document.body.classList.add('modal-open');
                    success = true;
                    console.log('Opened via manual style fallback');
                } catch (err) {
                    console.error('Manual style fallback failed:', err);
                    alert('Gagal membuka modal. Pustaka bootstrap belum siap.');
                }
            }
        };

        // Close button and click-outside listener globally
        document.addEventListener('click', (e) => {
            const isCloseButton = e.target.closest('[data-bs-dismiss="modal"]') || e.target.closest('[data-dismiss="modal"]');
            const isModalOverlay = e.target.classList.contains('modal') && e.target.classList.contains('show');
            
            if (isCloseButton || isModalOverlay) {
                const modalEl = isModalOverlay ? e.target : e.target.closest('.modal');
                if (modalEl) {
                    let success = false;
                    if (window.bootstrap && window.bootstrap.Modal) {
                        try {
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) {
                                modalInstance.hide();
                                success = true;
                            }
                        } catch (err) {}
                    }
                    if (!success && window.$ && typeof window.$.fn.modal === 'function') {
                        try {
                            window.$(modalEl).modal('hide');
                            success = true;
                        } catch (err) {}
                    }
                    // Manual fallback close
                    modalEl.style.display = 'none';
                    modalEl.classList.remove('show');
                    modalEl.style.backgroundColor = '';
                    document.body.classList.remove('modal-open');
                }
            }
        });

        const body = document.body;
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.main-sidebar');


        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                body.classList.toggle('sidebar-open');
            });
        }

        document.addEventListener('click', function(event) {
            if (window.innerWidth >= 992 || !body.classList.contains('sidebar-open')) {
                return;
            }

            const clickedInsideSidebar = sidebar && sidebar.contains(event.target);
            const clickedToggle = sidebarToggle && sidebarToggle.contains(event.target);

            if (!clickedInsideSidebar && !clickedToggle) {
                body.classList.remove('sidebar-open');
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                body.classList.remove('sidebar-open');
            }
        });

        // Theme toggle removed — dark-mode support disabled

        requestAnimationFrame(() => {
            body.classList.add('page-ready');
        });

        @if(Auth::check() && Auth::user()->role !== 'eksekutif')
        // ===== NOTIFICATION BELL =====
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationList = document.getElementById('notificationList');
        const apiUrl = '{{ route("api.deadline.notifications") }}';

        function fetchNotifications() {
            console.log('Fetching notifications from:', apiUrl);
            fetch(apiUrl)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Notifications data:', data);
                    const count = data.count || 0;
                    const tasks = data.tasks || [];

                    if (count > 0) {
                        notificationBadge.textContent = count > 9 ? '9+' : count;
                        notificationBadge.style.display = 'inline-block';
                    } else {
                        notificationBadge.style.display = 'none';
                    }

                    if (tasks.length === 0) {
                        notificationList.innerHTML = '<div class="notification-empty">Tidak ada deadline yang akan datang</div>';
                    } else {
                        notificationList.innerHTML = tasks.map(task => {
                            const urgencyClass = task.urgency === 'today' ? 'notification-item-urgency-today' :
                                task.urgency === 'tomorrow' ? 'notification-item-urgency-tomorrow' : '';
                            const urgencyText = task.deadline_label || ('H-' + (task.days_left ?? '-') + ' hari');

                            return `
                                    <a href="/{{ Auth::check() ? Auth::user()->role : 'administrasi' }}/deal/${task.deal_id}" class="text-decoration-none notification-item">
                                        <div class="notification-item-title">${task.nama_tugas || task.deal_nama}</div>
                                        <div class="notification-item-info">
                                            <div>${task.deal_nama}</div>
                                            <div class="${urgencyClass}">${urgencyText} (${task.tanggal_tugas ?? '-'})</div>
                                        </div>
                                    </a>
                                `;
                        }).join('');
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                    notificationList.innerHTML = '<div class="notification-empty">Gagal memuat notifikasi</div>';
                });
        }

        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            if (!notificationBell.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.style.display = 'none';
            }
        });

        fetchNotifications();
        setInterval(fetchNotifications, 60000);
        @endif
    </script>

    <!-- Profile Dropdown Toggle Logic & Toast Auto-Dismiss -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trigger = document.getElementById('profileDropdownTrigger');
            const menu = document.getElementById('profileDropdownMenu');

            if (trigger && menu) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });

                document.addEventListener('click', function(e) {
                    if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                        menu.style.display = 'none';
                    }
                });
            }

            // Auto-dismiss custom toasts after 4 seconds
            document.querySelectorAll('.custom-toast').forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('hide-toast');
                    toast.addEventListener('animationend', (e) => {
                        if (e.animationName === 'toast-slide-out') {
                            toast.remove();
                        }
                    });
                }, 4000);
            });

            // Track form changes for edit/update forms
            function trackFormChanges(form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (!submitBtn) return;

                function getSignature() {
                    const elements = form.querySelectorAll('input, select, textarea');
                    const parts = [];
                    elements.forEach(el => {
                        if (el.type === 'button' || el.type === 'submit' || el.type === 'reset' || el.disabled) return;
                        
                        let val = '';
                        if (el.type === 'checkbox' || el.type === 'radio') {
                            val = el.checked ? 'checked' : 'unchecked';
                        } else if (el.type === 'file') {
                            val = el.files && el.files.length > 0 ? el.files[0].name : '';
                        } else {
                            val = el.value;
                        }
                        parts.push(`${el.name || el.id || ''}:${val}`);
                    });
                    return parts.join('|');
                }

                const initialSignature = getSignature();

                function checkChanges() {
                    const currentSignature = getSignature();
                    if (currentSignature === initialSignature) {
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.6';
                        submitBtn.style.cursor = 'not-allowed';
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';
                    }
                }

                checkChanges();

                form.addEventListener('input', checkChanges);
                form.addEventListener('change', checkChanges);
            }

            document.querySelectorAll('.js-track-changes-form').forEach(trackFormChanges);
        });
    </script>
</body>

</html>