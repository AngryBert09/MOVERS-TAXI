<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li>
                    <a href="{{ route('applicant.dashboard') }}"><i class="la la-dashboard"></i> <span> Dashboard</span>
                </li>






                <li class="submenu">
                    <a href="#"><i class="la la-graduation-cap"></i> <span> Evaluations</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('applicant.facility') }}">Facilities Evaluation</a></li>
                    </ul>
                </li>


                <li class="submenu">
                    <a href="#"><i class="la la-edit"></i> <span> My Training </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('training.list') }}">Current Training</a></li>
                    </ul>
                </li>

                <li class="menu-title">
                    <span>Jobs</span>
                </li>


                {{--
                <li>
                    <a href="users.html"><i class="la la-user-plus"></i> <span>Users</span></a>
                </li> --}}
                <li>
                    <a href="{{ route('jobs.vacancies') }}"><i class="la la-briefcase"></i> <span> Apply Jobs</span></a>
                </li>
                <li>
                    <a href="{{ route('company.index') }}"><i class="la la-cog"></i> <span>Settings</span></a>
                </li>



            </ul>
        </div>
    </div>
</div>
