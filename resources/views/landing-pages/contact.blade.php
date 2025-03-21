<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Movers</title>
    <link rel="stylesheet" href="{{ asset('extras/style.css') }}">

</head>

<body>

    <div class="js-animsition animsition" id="site-wrap" data-animsition-in-class="fade-in"
        data-animsition-out-class="fade-out">



        @include('landing-pages.layout.header')
        <!-- .templateux-navbar -->
        <!-- .templateux-navba -->

        <div class="templateux-cover" style="background-image: url(extras/images/our-company.jpg);">
            <div class="container">
                <div class="row align-items-lg-center">
                    <div class="col-lg-6 order-lg-1 text-center mx-auto">
                        <h1 class="heading mb-3 text-white" data-aos="fade-up">Contact Us</h1>
                        <p class="lead mb-5 text-white" data-aos="fade-up" data-aos-delay="100">
                            Have questions or need HR support? Reach out to us, and our team will be happy to assist
                            you.
                        </p>
                        <p data-aos="fade-up"><a href="{{ route('auth.login') }}"
                                class="btn btn-primary py-3 px-4 mr-3">Get Started</a> <a href="#our-company"
                                class="text-white">Learn More</a></p>
                    </div>


                </div>
            </div>
        </div> <!-- .templateux-cover -->



        <div class="templateux-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-7 pr-md-7 mb-5">
                        <form action="{{ route('inquiries.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea name="message" id="message" cols="30" rows="10" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary py-3 px-5" value="Send Message">
                            </div>
                        </form>

                    </div>
                    <div class="col-md-5">
                        <div class="media block-icon-1 d-block text-center">
                            <div class="icon mb-3"><span class="ion-ios-location-outline"></span></div>
                            <div class="media-body">
                                <h3 class="h5 mb-4">BLOCK 23 LOT 20 BAUTISTA ST. MALIGAYA PARK CAMARIN CALOOCAN CITY
                                </h3>
                            </div>
                        </div> <!-- .block-icon-1 -->

                        <div class="media block-icon-1 d-block text-center">
                            <div class="icon mb-3"><span class="ion-ios-telephone-outline"></span></div>
                            <div class="media-body">
                                <h3 class="h5 mb-4">+1 209 923 2302</h3>
                            </div>
                        </div> <!-- .block-icon-1 -->

                        <div class="media block-icon-1 d-block text-center">
                            <div class="icon mb-3"><span class="ion-ios-email-outline"></span></div>
                            <div class="media-body">
                                <h3 class="h5 mb-4">humanresource2.moverstaxi.com</h3>
                            </div>
                        </div> <!-- .block-icon-1 -->

                    </div>
                </div> <!-- .row -->

            </div>
        </div> <!-- .templateux-section -->

        <footer class="templateux-footer bg-light">
            <div class="container">

                <div class="row mb-5">
                    <div class="col-md-4 pr-md-5">
                        <div class="block-footer-widget">
                            <h3>About Us</h3>
                            <p>We are dedicated to providing top-notch HR services to help your business thrive. Our
                                team of
                                experts is here to support you every step of the way.</p>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="block-footer-widget">
                                    <h3>Services</h3>
                                    <ul class="list-unstyled">
                                        <li><a href="#">HR Consulting</a></li>
                                        <li><a href="#">Leadership Training</a></li>
                                        <li><a href="#">HR Management</a></li>
                                        <li><a href="#">Corporate Programs</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="block-footer-widget">
                                    <h3>Support</h3>
                                    <ul class="list-unstyled">
                                        <li><a href="#">FAQ</a></li>
                                        <li><a href="#">Contact Us</a></li>
                                        <li><a href="#">Help Desk</a></li>
                                        <li><a href="#">Knowledgebase</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="block-footer-widget">
                                    <h3>Company</h3>
                                    <ul class="list-unstyled">
                                        <li><a href="#">About Us</a></li>
                                        <li><a href="#">Careers</a></li>
                                        <li><a href="#">Terms of Service</a></li>
                                        <li><a href="#">Privacy Policy</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="block-footer-widget">
                                    <h3>Connect With Us</h3>
                                    <ul class="list-unstyled block-social">
                                        <li><a href="#" class="p-1"><span
                                                    class="icon-facebook-square"></span></a></li>
                                        <li><a href="#" class="p-1"><span class="icon-twitter"></span></a>
                                        </li>
                                        <li><a href="#" class="p-1"><span class="icon-linkedin"></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- .row -->

                    </div>
                </div> <!-- .row -->

                <div class="row pt-5 text-center">
                    <div class="col-md-12 text-center">
                        <p>
                            &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | Movers HR
                        </p>
                    </div>
                </div> <!-- .row -->

            </div>
        </footer> <!-- .templateux-footer -->

    </div> <!-- .js-animsition -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Try Again'
                });
            @endif
        });
    </script>
    <script src="{{ asset('extras/js/scripts-all.js') }}"></script>
    <script src="{{ asset('extras/js/main.js') }}"></script>

</body>

</html>
