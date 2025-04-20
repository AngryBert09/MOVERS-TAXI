<div class="header">

    <!-- Logo -->
    {{-- <div class="header-left">
        <a href="index.html" class="logo">
            <img src="assets/img/logo.png" width="40" height="40" alt="">
        </a>
    </div> --}}
    <!-- /Logo -->

    <a id="toggle_btn" href="javascript:void(0);">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Title -->
    <div class="page-title-box">
        <h3>Movers</h3>
    </div>
    <!-- /Header Title -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>

    <!-- Header Menu -->
    <ul class="nav user-menu">

        <!-- Search -->
        <li class="nav-item">
            <div class="top-nav-search">
                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form id="searchForm">
                    <input class="form-control" type="text" id="searchInput" placeholder="Search here">
                    <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                </form>
                <!-- Search Results Dropdown -->
                <div class="dropdown-menu search-dropdown" id="searchResults"></div>
            </div>
        </li>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const pages = [{
                        name: "Dashboard",
                        url: "{{ route('applicant.dashboard') }}"
                    },
                    {
                        name: "Employees",
                        url: "{{ route('employees') }}"
                    },
                    {
                        name: "Training",
                        url: "{{ route('training.list') }}"
                    },
                    {
                        name: "Performance",
                        url: "{{ route('performance.index') }}"
                    },
                    {
                        name: "Settings",
                        url: "{{ route('company.index') }}"
                    }
                ];

                const searchInput = document.getElementById("searchInput");
                const searchResults = document.getElementById("searchResults");

                searchInput.addEventListener("input", function() {
                    const query = this.value.toLowerCase();
                    searchResults.innerHTML = "";

                    if (query) {
                        const filteredPages = pages.filter(page => page.name.toLowerCase().includes(query));

                        if (filteredPages.length > 0) {
                            searchResults.classList.add("show");
                            filteredPages.forEach(page => {
                                const item = document.createElement("a");
                                item.classList.add("dropdown-item");
                                item.href = page.url;
                                item.textContent = page.name;
                                searchResults.appendChild(item);
                            });
                        } else {
                            searchResults.classList.remove("show");
                        }
                    } else {
                        searchResults.classList.remove("show");
                    }
                });

                document.getElementById("searchForm").addEventListener("submit", function(event) {
                    event.preventDefault();
                    const firstResult = searchResults.querySelector("a");
                    if (firstResult) {
                        window.location.href = firstResult.href;
                    }
                });
            });
        </script>

        <style>
            .search-dropdown {
                position: absolute;
                top: 100%;
                left: 0;
                background: white;
                border: 1px solid #ddd;
                width: 70%;
                display: none;
            }

            .search-dropdown.show {
                display: block;
            }

            .search-dropdown .dropdown-item {
                padding: 5px;
            }
        </style>

        <!-- /Search -->


        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i> <span class="badge badge-pill">3</span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        <li class="notification-message">

                        </li>

                    </ul>
                </div>

            </div>
        </li>
        <!-- /Notifications -->


        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <span class="user-img">
                    <img src="{{ !empty(Auth::user()->personalInformation) && !empty(Auth::user()->personalInformation->avatar_path) ? Storage::disk('public')->url(Auth::user()->personalInformation->avatar_path) : asset('assets/img/default.jpg') }}"
                        alt="User Avatar">
                    <span class="status online"></span>
                </span>



                <span>{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('profile.index') }}">
                    My Profile
                </a>

                <a class="dropdown-item" href="{{ route('company.index') }}">Settings</a>
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <a class="dropdown-item" href="{{ route('auth.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>

            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    {{-- <div class="dropdown mobile-user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="profile.html">My Profile</a>
            <a class="dropdown-item" href="settings.html">Settings</a>
            <a class="dropdown-item" href="login.html">Logout</a>
        </div>
    </div> --}}
    <!-- /Mobile Menu -->

</div>
