<header class="navbar navbar-expand-lg">
    <nav class="container">
        <a class="col-auto navbar-brand fw-800" href="{{ route('index') }}">
                <!--
                <img src="{{ asset('img/logo.png') }}" width="50" height="50" alt="logo">
                <img class="logo" src="https://districtgurus.com/public/uploads/all/SC008HOLHmfOeB8E3SxNDONHI7nad1YJcmSl0ds9.png" data-src="https://districtgurus.com/public/uploads/all/SC008HOLHmfOeB8E3SxNDONHI7nad1YJcmSl0ds9.png" alt="District Gurus">-->
                #JEWELRYCG
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- left navbar-->
            <ul class="mb-2 navbar-nav mb-lg-0">
                <li class="nav-item menu-area d-none">
                    <a class="nav-link" href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item menu-area">
                    <a class="nav-link active" href="{{ route('shop_index') }}">3D Models</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" aria-current="page" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="#">Learn</a>
                    <ul class="dropdown-menu half-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('blog') }}">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dropdown-icon-wrap"><i class="bi bi-book"></i></div>
                                    </div>
                                    <div class="col-auto w-80">
                                        <div class="mb-2 w-100 fw-800">Blog</div>
                                        <div class="w-100">Learn product design in just 16 weeks...</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('blog') }}">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dropdown-icon-wrap"><i class="bi bi-book"></i></div>
                                    </div>
                                    <div class="col-auto w-80">
                                        <div class="mb-2 w-100 fw-800">Browse our courses</div>
                                        <div class="w-100">Learn how to create jewelry & start a business.</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <!--
                        <li><a class="dropdown-item" href="{{ route('categoryAll') }}">Categories</a></li>
                        <li><a class="dropdown-item" href="{{ route('tagAll') }}">Tags</a></li>
                        -->
                    </ul>
                </li>
                <li class="nav-item menu-area">
                    <a class="nav-link" href="#">Hire Designers</a>
                </li>
            </ul>
            <!-- end left navbar-->


            <!-- right navbar-->
            <ul class="mb-2 ml-auto navbar-nav mb-lg-0">
                <li class="nav-item dropdown menu-area">
                    <a href="{{route('cart.index')}}" class="nav-link">
                        <i class="bi bi-cart2"></i>
                        <?php
                            if(Cart::instance('default')->content()->count() == 0
                                && auth()->check()
                            )
                            {
                                Cart::merge(auth()->id());
                            }
                        ?>
                        <span class="cart-count">
                            @if ($cart_items = Cart::content()->count())
                                <span class="rounded-pill pill badge bg-primary text-light">
                                    {{$cart_items}}
                                </span>
                            @endif
                        </span>
                    </a>
                </li>
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" aria-current="page" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="#">{{ Auth::user()->first_name }}</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{route('user.index', auth()->user()->id)}}">My Info</a></li>
                        <li><a class="dropdown-item" href="{{route('orders.index')}}">{{ auth()->user()->is_admin ? 'All Orders' : 'My Orders' }}</a></li>
                        <li><a class="dropdown-item" href="{{route('wishlist')}}">My Wishlist</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </li>
                @else
                <li class="ml-1 nav-item">
                    <a class="auth-btn" href="{{ route('login') }}">Log In</a>
                </li>
                <li class="ml-1 nav-item">
                    <a class="auth-btn auth-primary" href="{{ route('signup') }}">Sign Up</a>
                </li>
                @endauth
            </ul>
            <!--end right nav-->

        </div>
    </nav>
</header>
