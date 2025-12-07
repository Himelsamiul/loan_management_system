<body>

    <!-- Page Preloader -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">

        <div class="offcanvas__search">
            <i class="fa fa-search search-switch"></i>
        </div>

        <div class="offcanvas__logo">
            <a href="{{ route('home') }}"><img src="{{ asset('a.png') }}" alt="Logo"></a>
        </div>

        <nav class="offcanvas__menu mobile-menu">
            <ul>
                <li class="active"><a href="{{ route('home') }}">Home</a></li>
                <li><a href="">About</a></li>
                <li><a href="">Services</a></li>
                <li><a href="">Blog</a></li>

                <li><a href="#">loan</a>
                    <ul class="dropdown">
                        <li><a href="">Loan 1</a></li>
                        <li><a href="">Loan 2</a></li>
                        <li><a href="">Loan 3</a></li>
                    </ul>
                </li>

                <li><a href="">Contact</a></li>

                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                @else
                    <li>
                        <a href="#">{{ Auth::user()->sure_name }}</a>
                        <ul class="dropdown">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;padding:0;color:#fff;cursor:pointer;">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </nav>

        <div id="mobile-menu-wrap"></div>
<ul class="header__top__widget">
    <li><i class="fa fa-phone"></i> +880 1310-439446</li>
    <li><i class="fa fa-map-marker"></i> Address: Mirpur -2, Dhaka</li>
    <li><i class="fa fa-envelope"></i> sadiasultananisha380@gmail.com</li>
</ul>
        
    </div>
    <!-- Offcanvas Menu End -->


    <!-- Header Section Begin -->
    <header class="header">

        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
<ul class="header__top__widget">
    <li><i class="fa fa-phone"></i> +880 1310-439446</li>
    <li><i class="fa fa-map-marker"></i> Address: Mirpur -2, Dhaka</li>
    <li><i class="fa fa-envelope"></i> sadiasultananisha380@gmail.com</li>
</ul>

                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <div class="row">

                <!-- Logo -->
                <div class="col-lg-3">
                    <div class="header__logo">
                        <div class="logo-placeholder">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('a.png') }}" alt="Logo">
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="col-lg-9">
                    <div class="header__nav">
                        <nav class="header__menu">
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="">About</a></li>
                                <li><a href="">Services</a></li>
                                <li><a href="">Blog</a></li>

                                <li><a href="{{ route('frontend.apply.show') }}">Apply</a>
                                </li>

                                <li><a href="">Contact</a></li>

                                @guest
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                @else
                                    <li>
                                        <a href="{{ route('profile.view') }}">{{ Auth::user()->sure_name }}</a>
                                        <ul class="dropdown">
                                            <li>
                                                <form action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" style="background:none;border:none;padding:0;color:#fff;cursor:pointer;">
                                                        Logout
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @endguest

                            </ul>
                        </nav>
                    </div>
                </div>

            </div>

            <div class="canvas__open">
                <span class="fa fa-bars"></span>
            </div>
        </div>

    </header>
    <!-- Header Section End -->


</body>

<style>
/* ---------------------- */
/* Logo Fix & Left Shift  */
/* ---------------------- */

/* Moves logo more to the left */
.logo-placeholder {
    width: 180px;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    overflow: hidden;
    margin-left: -25px; /* ⬅ main fix */
}

/* Scale image correctly */
.logo-placeholder img {
    width: 100%;
    height: auto;
    object-fit: contain;
    margin-top: -40px;
}

/* Navbar alignment */
.header__nav {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

/* Dropdown styling */
.header__menu ul.dropdown {
    display: none;
    position: absolute;
    background: #222;
    padding: 10px 0;
    list-style: none;
    z-index: 999;
}

.header__menu li:hover > ul.dropdown {
    display: block;
}

.header__menu ul.dropdown li {
    padding: 5px 20px;
}

.header__menu ul.dropdown li button {
    color: #fff;
}
</style>
