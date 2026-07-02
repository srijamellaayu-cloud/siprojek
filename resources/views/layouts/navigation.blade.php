<nav x-data="{ open: false }" class="main-header navbar navbar-expand px-4 py-2" style="background: #ffffff; border-bottom: none; box-shadow: none;">
    <!-- Primary Navigation Menu -->
    <div class="w-100 d-flex align-items-center gap-3" style="flex-direction: row-reverse !important; justify-content: flex-end !important;">
        <!-- Notification Bell -->
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
                <div class="notification-list" id="notificationList" style="max-height: 300px; overflow-y: auto;">
                    <div class="px-3 py-2 text-muted text-center" style="font-size: 0.85rem;">Memuat...</div>
                </div>
            </div>
        </div>

        <!-- Profile Link -->
        <div class="d-none d-sm-flex align-items-center dropend">
            <a href="{{ route('profile.edit') }}" class="btn btn-link text-sm rounded-lg text-gray-600 bg-transparent hover:bg-white transition" style="text-decoration: none; color: #666; padding: 0.5rem 0.75rem; font-size: 14px; font-weight: 500;">
                {{ __('Profile') }}
            </a>
        </div>

        <!-- Settings Dropdown (Email & Admin) -->
        <div class="d-none d-sm-flex align-items-center dropend">
            <x-dropdown width="48">
                <x-slot name="trigger">
                    <button class="btn btn-link d-flex align-items-center gap-2 px-2 py-1 text-sm rounded-lg text-gray-600 bg-transparent hover:bg-white transition" style="text-decoration: none; color: #666; gap: 0.5rem;">
                        <div class="text-end" style="line-height: 1.2; text-align: right;">
                            <div class="fw-semibold" style="font-size: 14px; color: #2f4159;">{{ Auth::user()->email }}</div>
                            <div style="font-size: 13px; color: #999;">Bagian Administrasi</div>
                        </div>

                        <div class="rounded-circle border-2 bg-white text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-color: #000;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-6 14a6 6 0 1 1 12 0H4Z" />
                            </svg>
                        </div>

                        <div>
                            <svg style="width: 12px; height: 12px; fill: #999;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Hamburger -->
        <button type="button" @click="open = ! open" class="d-sm-none btn btn-link ms-2" style="text-decoration: none; color: #666;">
            <svg :class="{'d-none': open, 'd-block': ! open }" class="d-block" style="width: 24px; height: 24px;" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg :class="{'d-none': ! open, 'd-block': open }" class="d-none" style="width: 24px; height: 24px;" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'d-block': open, 'd-none': ! open}" class="d-none d-sm-none">
        <div class="pt-3 pb-2">
            <!-- Responsive Settings Options -->
            <div class="px-3">
                <div class="fw-medium" style="font-size: 1rem; color: #2f2f2f;">{{ Auth::user()->name }}</div>
                <div class="fw-medium text-muted" style="font-size: 0.875rem;">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .notification-bell-container {
        position: relative;
    }

    .notification-bell {
        transition: color 0.2s ease !important;
    }

    .notification-bell:hover {
        color: #2f2518 !important;
    }

    .notification-badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.45rem !important;
        min-width: 18px;
        text-align: center;
    }

    .notification-dropdown {
        background: #ffffff !important;
        border: 0 !important;
        box-shadow: 0 4px 12px rgba(34, 56, 86, 0.12) !important;
        border-radius: 10px !important;
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0.5rem;
        z-index: 2100;
        min-width: 420px !important;
        max-width: 560px !important;
        width: auto !important;
        padding: 0.4rem !important;
    }

    /* Responsive adjustments for notification dropdown */
    @media (max-width: 992px) {
        .notification-dropdown {
            min-width: 360px !important;
            max-width: 90vw !important;
            right: 8px !important;
            left: auto !important;
        }
    }

    @media (max-width: 576px) {
        .notification-dropdown {
            min-width: auto !important;
            width: calc(100% - 16px) !important;
            left: 8px !important;
            right: 8px !important;
            border-radius: 8px !important;
        }

        .notification-item {
            padding: 0.5rem !important;
        }

        .notification-list {
            max-height: 50vh !important;
        }
    }

    .notification-header {
        border-bottom: 1px solid rgba(69, 54, 34, 0.08);
    }

    .notification-item {
        padding: 0.6rem 1rem !important;
        border-bottom: 1px solid rgba(69, 54, 34, 0.08);
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: rgba(143, 106, 58, 0.08) !important;
    }

    .notification-item-title {
        font-weight: 600;
        color: #2f4159;
        margin-bottom: 0.25rem;
    }

    .notification-item-info {
        font-size: 0.8rem;
        color: #666;
    }

    .notification-item-urgency-today {
        color: #d55e5e;
        font-weight: 600;
    }

    .notification-item-urgency-tomorrow {
        color: #f39c12;
        font-weight: 600;
    }

    .notification-empty {
        padding: 1rem !important;
        text-align: center;
        color: #999;
        font-size: 0.85rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationList = document.getElementById('notificationList');
        const apiUrl = '{{ route("api.deadline.notifications") }}';

        // Fetch notifikasi
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

                    // Update badge
                    if (count > 0) {
                        notificationBadge.textContent = count > 9 ? '9+' : count;
                        notificationBadge.style.display = 'inline-block';
                    } else {
                        notificationBadge.style.display = 'none';
                    }

                    // Update list
                    if (tasks.length === 0) {
                        notificationList.innerHTML = '<div class="notification-empty">Tidak ada deadline yang akan datang</div>';
                    } else {
                        notificationList.innerHTML = tasks.map(item => {
                            const urgencyClass = item.urgency === 'today' ? 'notification-item-urgency-today' :
                                item.urgency === 'tomorrow' ? 'notification-item-urgency-tomorrow' : '';
                            const urgencyText = item.deadline_label || ('H-' + (item.days_left ?? '-') + ' hari');
                            const projectTitle = item.nama_proyek || item.deal_nama || 'Nama Proyek';
                            const taskTitle = item.nama_tugas || 'Nama Tugas';

                            return `
                                    <a href="/{{ Auth::check() ? Auth::user()->role : 'administrasi' }}/deal/${item.deal_id}" class="text-decoration-none notification-item" role="menuitem">
                                        <div class="notification-item-title">${projectTitle}</div>
                                        <div class="notification-item-info">
                                            <div>${taskTitle} · <span class="${urgencyClass}">${urgencyText}</span></div>
                                            <small class="text-muted">${item.tanggal_tugas ?? '-'}</small>
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

        // Toggle dropdown
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';
        });

        // Close dropdown ketika klik di luar
        document.addEventListener('click', function(e) {
            if (!notificationBell.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.style.display = 'none';
            }
        });

        // Fetch notifikasi saat page load dan setiap 1 menit
        fetchNotifications();
        setInterval(fetchNotifications, 60000);
    });
</script>