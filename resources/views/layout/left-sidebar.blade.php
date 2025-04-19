<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}"><i class="la la-dashboard"></i> <span> Dashboard</span>
                </li>

                <li class="menu-title">
                    <span>Employees</span>
                </li>
                <li class="submenu">
                    <a href="#" class="noti-dot"><i class="la la-user"></i> <span> Employees</span>
                        <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('employees') }}">All Employees</a></li>
                        <li><a href="{{ route('departments') }}">Departments</a></li>
                        <li><a href="{{ route('employee.new-hired') }}">New Hired List</a></li>
                    </ul>
                </li>

                <li class="menu-title">
                    <span>HR</span>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-files-o"></i> <span> Accounting </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('budgets') }}">Budget Request</a></li>
                        <li><a href="{{ route('budget.used') }}">Used Budget</a></li>
                    </ul>
                </li>


                <li class="menu-title">
                    <span>Performance</span>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-graduation-cap"></i> <span> Performance </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('performance.index') }}"> Evaluation </a></li>
                        <li><a href="{{ route('performance.results') }}"> Results</a></li>
                        <li><a href=""> Attendance Record</a></li>
                    </ul>

                <li><a href="{{ route('performance.index') }}"> Results</a></li>

                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-edit"></i> <span> Training </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('training.list') }}"> On Training</a></li>
                        <li><a href="{{ route('training.trainers') }}"> Trainers</a></li>
                        <li><a href="{{ route('training.types') }}"> Training Type </a></li>
                        <li><a href="{{ route('training.for-training') }}"> For Training </a></li>
                    </ul>
                </li>

                <li class="menu-title">
                    <span>Recruitment / Onboarding</span>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-briefcase"></i> <span> Jobs </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('jobs.manage') }}"> Manage Jobs </a></li>
                        <li><a href="{{ route('applicants') }}"> Applicants </a></li>
                        <li><a href="{{ route('applicants.onboarding') }}"> Onboarding </a></li>
                        <li><a href="{{ route('applicant.files') }}"> Applicant Files </a></li>
                    </ul>
                </li>

                {{--
                <li>
                    <a href="users.html"><i class="la la-user-plus"></i> <span>Users</span></a>
                </li> --}}
                <li>
                    <a href="{{ route('inquiries') }}"><i class="la la-ticket"></i> <span>Inquiries</span></a>
                </li>
                <li>
                    <a href="{{ route('company.index') }}"><i class="la la-cog"></i> <span>Settings</span></a>
                </li>



            </ul>
        </div>
    </div>
</div>
