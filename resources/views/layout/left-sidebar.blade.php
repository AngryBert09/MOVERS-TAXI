<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li class="submenu">
                    <a href="{{ route('dashboard') }}"><i class="la la-dashboard"></i> <span> Dashboard</span>
                </li>

                <li class="menu-title">
                    <span>Employees</span>
                </li>
                <li class="submenu">
                    <a href="#" class="noti-dot"><i class="la la-user"></i> <span> Employees</span>
                        <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="employees.html">All Employees</a></li>
                        <li><a href="{{ route('departments') }}">Departments</a></li>
                        <li><a href="departments.html">New Hired List</a></li>
                    </ul>
                </li>


                <li>
                    <a href="{{ route('inquiries') }}"><i class="la la-ticket"></i> <span>Inquiries</span></a>
                </li>
                <li class="menu-title">
                    <span>HR</span>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-files-o"></i> <span> Accounting </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('budgets') }}">Budget Expenses</a></li>
                    </ul>
                </li>


                <li class="menu-title">
                    <span>Performance</span>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-graduation-cap"></i> <span> Performance </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="performance-indicator.html"> Performance Indicator </a></li>
                        <li><a href="performance-appraisal.html"> Performance Appraisal </a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-edit"></i> <span> Training </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('training.list') }}"> Training List </a></li>
                        <li><a href="{{ route('training.trainers') }}"> Trainers</a></li>
                        <li><a href="{{ route('training.types') }}"> Training Type </a></li>
                    </ul>
                </li>
                {{-- <li><a href="promotion.html"><i class="la la-bullhorn"></i> <span>Promotion</span></a></li>
                <li><a href="resignation.html"><i class="la la-external-link-square"></i>
                        <span>Resignation</span></a></li>
                <li><a href="termination.html"><i class="la la-times-circle"></i> <span>Termination</span></a>
                </li> --}}
                <li class="menu-title">
                    <span>Administration</span>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-briefcase"></i> <span> Jobs </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('jobs.manage') }}"> Manage Jobs </a></li>
                        <li><a href="{{ route('applicants') }}"> Applicants </a></li>
                    </ul>
                </li>


                <li>
                    <a href="users.html"><i class="la la-user-plus"></i> <span>Users</span></a>
                </li>
                <li>
                    <a href="settings.html"><i class="la la-cog"></i> <span>Settings</span></a>
                </li>



            </ul>
        </div>
    </div>
</div>
