<header class="templateux-navbar" role="banner">

    <div class="container" data-aos="fade-down">
        <div class="row">

            <div class="col-3 templateux-logo">
                <a href="{{ route('landing.index') }}" class="animsition-link">{{ $company->company_name }}</a>
            </div>
            <nav class="col-9 site-nav">
                <button
                    class="d-block d-md-none hamburger hamburger--spin templateux-toggle templateux-toggle-light ml-auto templateux-toggle-menu"
                    data-toggle="collapse" data-target="#mobile-menu" aria-controls="mobile-menu" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button> <!-- .templateux-toggle -->

                <ul class="sf-menu templateux-menu d-none d-md-block">
                    <li class="active">
                        <a href="{{ route('landing.index') }}" class="animsition-link">Home</a>
                    </li>
                    <li><a href="{{ route('jobs.vacancies') }}" class="animsition-link">Job Vacancies</a></li>
                    <li><a href="{{ route('landing.contact') }}" class="animsition-link">Contact</a></li>
                    <li><a href="{{ route('auth.login') }}" class="animsition-link">Login</a></li>
                    <li><a href="{{ route('auth.register') }}" class="animsition-link">Register</a></li>
                </ul> <!-- .templateux-menu -->

            </nav> <!-- .site-nav -->


        </div> <!-- .row -->
    </div> <!-- .container -->
</header> <!-- .templateux-navbar -->
