<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Student Management System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Laravel CSS and JavaScript through Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @php
        $currentRole = auth()->user()?->role?->slug;
        $isPublicPage = request()->routeIs('home', 'login', 'register*');
        $showSidebar = auth()->check() && !$isPublicPage;
        $pendingRegistrationsCount = in_array($currentRole, ['super-admin', 'admin'], true)
            ? \App\Models\RegistrationRequest::where('status', 'pending')->count()
            : 0;
    @endphp

                        
                            

    <!-- Top Navigation -->
    @if (request()->routeIs('home'))

        <header class="public-site-header">
            {{-- Top utility bar --}}
            <div class="public-topbar">
                <div class="container-fluid px-3 px-lg-5 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>
                        <i class="bi bi-mortarboard-fill me-2"></i>
                        ACADEMIC MANAGEMENT PORTAL
                    </span>

                    <div class="d-flex align-items-center gap-3">
                        <a href="#features">Our Features</a>
                        <a href="#about">About Us</a>
                        <a href="#contact">Contact</a>
                    </div>
                </div>
            </div>

            {{-- Main branding bar --}}
            <div class="public-mainbar">
                <div class="container-fluid px-3 px-lg-5 d-flex align-items-center justify-content-between gap-3">

                    <a href="{{ route('home') }}" class="public-brand">
                        <span class="public-brand-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </span>

                        <span class="public-brand-text">
                            <strong>Student Management System</strong>
                            <small>Learning · Progress · Excellence</small>
                        </span>
                    </a>

                    <div class="public-main-links d-none d-lg-flex align-items-center gap-4">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="#about">About</a>
                        <a href="#features">Features</a>
                        <a href="#academic-services">Academic Services</a>
                        <a href="#contact">Contact</a>
                    </div>

                    <div class="public-login-action d-flex align-items-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-warning fw-semibold">
                                <i class="bi bi-speedometer2 me-1"></i>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Login Portal
                            </a>

                            <a href="{{ route('register') }}" class="btn btn-outline-light fw-semibold">
                                <i class="bi bi-person-plus me-1"></i>
                                Register
                            </a>
                        @endauth
                    </div>

                </div>
            </div>

            {{-- Mobile navigation --}}
            <div class="public-mobile-nav d-lg-none">
                <div class="container-fluid px-3 d-flex gap-4 overflow-auto">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="#about">About</a>
                    <a href="#features">Features</a>
                    <a href="#academic-services">Academics</a>
                    <a href="#contact">Contact</a>
                </div>
            </div>

        </header>

    @else

        {{-- Application and login header --}}
        <nav class="navbar navbar-dark app-navbar sticky-top shadow-sm">
            <div class="container-fluid px-3 px-md-4">

                @auth
                    <button
                        class="navbar-toggler d-lg-none me-2"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                @endauth

                <a class="navbar-brand fw-semibold"
                   href="{{ auth()->check() ? route('dashboard') : route('home') }}">
                    <i class="bi bi-mortarboard-fill me-2"></i>
                    Student Management System
                </a>

                <div class="d-flex align-items-center gap-3">
                    @auth
                        <div class="text-white text-end d-none d-sm-block">
                            <div class="small fw-semibold">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="small opacity-75">
                                {{ auth()->user()->role?->name ?? 'No role assigned' }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf

                            <button type="submit" class="btn btn-light btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('home') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-house me-1"></i>
                            Home
                        </a>
                    @endauth
                </div>

            </div>
        </nav>

    @endif

    <!-- Application Layout -->
    <div class="d-flex flex-column flex-lg-row min-vh-100">

        @auth
            @if ($showSidebar)
                <!-- Sidebar -->
                <aside
                    id="sidebarMenu"
                    class="collapse d-lg-block bg-white border-end app-sidebar"
                >
                    <div class="sidebar-inner p-3">

                        <!-- Sidebar Brand -->
                        <div class="sidebar-brand d-flex align-items-center gap-3 mb-4">
                            <div class="sidebar-brand-icon">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>

                            <div>
                                <div class="sidebar-brand-title">
                                    Student Portal
                                </div>

                                <div class="sidebar-brand-subtitle">
                                    Management System
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="sidebar-user d-flex align-items-center gap-3 mb-4">
                            <div class="sidebar-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div class="sidebar-user-info">
                                <div class="sidebar-user-name">
                                    {{ auth()->user()->name }}
                                </div>

                                <div class="sidebar-user-role">
                                    {{ auth()->user()->role?->name ?? 'User' }}
                                </div>
                            </div>

                            <span
                                class="sidebar-status-dot"
                                title="Account status"
                            ></span>
                        </div>

                        <!-- Main Menu -->
                        <div class="sidebar-section-label">
                            MAIN MENU
                        </div>

                        <a
                            href="{{ route('dashboard') }}"
                            class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}"
                        >
                            <i class="bi bi-grid-1x2-fill sidebar-link-icon"></i>
                            <span>Dashboard</span>
                            <i class="bi bi-chevron-right sidebar-link-chevron"></i>
                        </a>

                        <!-- Student Directory (Accessible to Admin, Super Admin, Teacher, Student) -->
                        @if (in_array($currentRole, ['super-admin', 'admin', 'teacher', 'student'], true))
                            <div class="sidebar-section-label mt-4">
                                STUDENT MANAGEMENT
                            </div>

                            <a
                                href="{{ url('/students') }}"
                                class="sidebar-link {{ request()->is('students') || request()->is('students/*') ? 'active' : '' }}"
                            >
                                <i class="bi bi-people-fill sidebar-link-icon"></i>
                                <span>{{ $currentRole === 'student' ? 'Student List' : 'Students' }}</span>
                                <i class="bi bi-chevron-right sidebar-link-chevron"></i>
                            </a>
                        @endif

                        <!-- Admin & Super Admin Only Menus -->
                        @if (in_array($currentRole, ['super-admin', 'admin'], true))
                            <!-- Teacher Management Section -->
                            <div class="sidebar-section-label mt-4">
                                TEACHER MANAGEMENT
                            </div>

                            <a
                                href="{{ route('admin.teachers.index') }}"
                                class="sidebar-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}"
                            >
                                <i class="bi bi-person-badge-fill sidebar-link-icon"></i>
                                <span>Teachers</span>
                                <i class="bi bi-chevron-right sidebar-link-chevron"></i>
                            </a>

                            <!-- Registration Approval -->
                            <div class="sidebar-section-label mt-4">
                                ADMINISTRATION
                            </div>

                            <a
                                href="{{ route('admin.registration-requests.index') }}"
                                class="sidebar-link {{ request()->routeIs('admin.registration-requests.*') ? 'active' : '' }}"
                            >
                                <i class="bi bi-person-check-fill sidebar-link-icon"></i>
                                <span class="flex-grow-1">Registration Requests</span>

                                @if ($pendingRegistrationsCount > 0)
                                    <span class="badge bg-warning text-dark rounded-pill me-1">
                                        {{ $pendingRegistrationsCount }}
                                    </span>
                                @endif

                                <i class="bi bi-chevron-right sidebar-link-chevron"></i>
                            </a>

                            <!-- Settings Menu -->
                            <div class="sidebar-section-label mt-4">
                                SETTINGS
                            </div>

                            <button
                                type="button"
                                class="sidebar-link sidebar-settings-toggle"
                                data-bs-toggle="collapse"
                                data-bs-target="#settingsSubmenu"
                                aria-expanded="{{ request()->is('settings*') ? 'true' : 'false' }}"
                                aria-controls="settingsSubmenu"
                            >
                                <i class="bi bi-gear-fill sidebar-link-icon"></i>
                                <span>Settings</span>
                                <i class="bi bi-chevron-down sidebar-link-chevron"></i>
                            </button>

                            <div
                                id="settingsSubmenu"
                                class="collapse settings-submenu {{ request()->is('settings*') ? 'show' : '' }}"
                            >
                                @if ($currentRole === 'super-admin')
                                    <a href="{{ url('/settings/roles') }}"
                                       class="sidebar-link sidebar-sub-link">
                                        <i class="bi bi-person-badge sidebar-link-icon"></i>
                                        <span>Roles</span>
                                    </a>

                                    <a href="{{ url('/settings/permissions') }}"
                                       class="sidebar-link sidebar-sub-link">
                                        <i class="bi bi-shield-check sidebar-link-icon"></i>
                                        <span>Permissions</span>
                                    </a>

                                    <a href="{{ url('/settings/role-permissions') }}"
                                       class="sidebar-link sidebar-sub-link">
                                        <i class="bi bi-shield-lock sidebar-link-icon"></i>
                                        <span>Role-Based Permissions</span>
                                    </a>
                                @endif

                                <a href="{{ url('/settings/users') }}"
                                   class="sidebar-link sidebar-sub-link">
                                    <i class="bi bi-people sidebar-link-icon"></i>
                                    <span>Users</span>
                                </a>
                            </div>
                        @endif

                        <!-- Sidebar Footer -->
                        <div class="sidebar-footer mt-4">
                            <i class="bi bi-shield-check me-2"></i>
                            Access based on your assigned role
                        </div>

                    </div>
                </aside>
            @endif
        @endauth

        <!-- Main Page Content -->
        <main class="app-content flex-grow-1">

            @auth
                <div class="container-fluid p-3 p-md-4">
                    @yield('content')
                </div>
            @else
                <div class="container py-4 py-md-5">
                    @yield('content')
                </div>
            @endauth

        </main>

    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>