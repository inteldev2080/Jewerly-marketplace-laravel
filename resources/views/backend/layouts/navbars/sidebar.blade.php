<!-- Navbar Vertical -->
  <aside id="navbarSupportedContent" class="sidebar collapse col-lg-2 col-sm-12">
    <div class="navbar-vertical-container">
      <div class="navbar-vertical-footer-offset">

        <!-- Content -->
        <div class="navbar-vertical-content">
          <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
            <div class="nav-item">
                <a class="nav-link nav-link-main " href="/backend" data-placement="left">
                  <i class="bi-house-door nav-icon"></i>
                  <span class="nav-link-title">{{ __("Dashboard") }}</span>
                </a>
              </div>

            <span class="dropdown-header">{{ __("Users Management") }}</span>

            <!-- Collapse -->
            <div class="navbar-nav nav-compact">

            </div>
            <div id="navbarVerticalMenuPagesMenu">
              <!-- Collapse -->
              <div class="nav-item">
                <a class="nav-link nav-link-main dropdown-toggle " href="#navbarVerticalMenuPagesUsersMenu" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuPagesUsersMenu" aria-expanded="false" aria-controls="navbarVerticalMenuPagesUsersMenu">
                  <i class="bi-people nav-icon"></i>
                  <span class="nav-link-title">{{ __("Users") }} <span class="badge bg-primary rounded-pill ms-1">5</span></span>
                </a>

                <div id="navbarVerticalMenuPagesUsersMenu" class="nav-collapse collapse @if($activePage == 'users') show @endif" data-bs-parent="#navbarVerticalMenuPagesMenu">
                  <a class="nav-link " href="{{ route('backend.users.list') }}">{{ __("All Users") }}</a>
                  <a class="nav-link " href="{{ route('backend.customers.list') }}">{{ __("Customers") }}</a>
                  <a class="nav-link " href="{{ route('backend.sellers.list') }}">{{ __("Sellers") }} <span class="badge bg-primary rounded-pill ms-1">5</span></a>
                  <a class="nav-link " href="#">{{ __("Profile") }}</a>
                </div>
              </div>
              <!-- End Collapse -->

              <span class="dropdown-header mt-4">{{ __("Content") }}</span>

              <!-- Collapse -->
              <div class="nav-item">
                <a class="nav-link nav-link-main dropdown-toggle " href="#navbarVerticalMenuAllPostMenu" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuAllPostMenu" aria-expanded="false" aria-controls="navbarVerticalMenuAllPostMenu">
                  <i class="bi bi-pin-angle-fill nav-icon"></i>
                  <span class="nav-link-title">{{ __("Post") }}</span>
                </a>

                <div id="navbarVerticalMenuAllPostMenu" class="nav-collapse collapse @if($activePage == 'posts') show @endif" data-bs-parent="#navbarVerticalMenuPagesMenu">
                  <a class="nav-link @if($navName == 'allpost') active @endif" href="{{ route('backend.posts.list') }}">{{ __("All Post") }}</a>
                  <a class="nav-link @if($navName == 'addpost') active @endif" href="{{ route('backend.posts.create') }}">{{ __("Create Post") }}</a>
                  <a class="nav-link @if($navName == 'blogcategories') active @endif" href="{{ route('backend.blog.categories.list') }}">{{ __("Categories") }}</a>
                  <a class="nav-link @if($navName == 'blogtags') active @endif" href="{{ route('backend.blog.tags.list') }}">{{ __("Tags") }}</a>
                  <a class="nav-link @if($navName == 'blogtrash') active @endif" href="{{ route('backend.posts.trash') }}">{{ __("Trash") }}</a>
                </div>
              </div>
              <!-- End Collapse -->

              <!-- Collapse -->
              <div class="nav-item">
                <a class="nav-link nav-link-main dropdown-toggle " href="#navbarVerticalMenuAllPagesMenu" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuAllPagesMenu" aria-expanded="false" aria-controls="navbarVerticalMenuAllPagesMenu">
                  <i class="bi-stickies nav-icon"></i>
                  <span class="nav-link-title">{{ __("Pages") }}</span>
                </a>

                <div id="navbarVerticalMenuAllPagesMenu" class="nav-collapse collapse @if($activePage == 'page') show @endif" data-bs-parent="#navbarVerticalMenuPagesMenu">
                  <a class="nav-link " href="{{ route('backend.page.index') }}">{{ __("All Pages") }}</a>
                  <a class="nav-link " href="{{ route('backend.page.create') }}">{{ __("Add New") }}</a>
                </div>
              </div>
              <!-- End Collapse -->

              <div class="nav-item">
                <a class="nav-link nav-link-main @if($activePage == 'filemanager') collapse @endif" href="{{ route('backend.file.index')}}" data-placement="left">
                  <i class="bi-folder2-open nav-icon"></i>
                  <span class="nav-link-title">File Manager</span>
                </a>
              </div>

              <span class="dropdown-header mt-4">{{ __("Commerce") }}</span>

              <!-- Collapse -->
              <div class="nav-item">
                <a class="nav-link nav-link-main dropdown-toggle " href="#navbarVerticalMenuAllProductsMenu" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuAllProductsMenu" aria-expanded="false" aria-controls="navbarVerticalMenuAllProductsMenu">
                  <i class="bi-basket nav-icon"></i>
                  <span class="nav-link-title">{{ __("Products") }}</span>
                </a>

                <div id="navbarVerticalMenuAllProductsMenu" class="nav-collapse collapse @if($activePage == 'products') show @endif" data-bs-parent="#navbarVerticalMenuPagesMenu">
                  <a class="nav-link @if($navName == 'allproducts') active @endif" href="{{ route('backend.products.list') }}">{{ __("All Products") }}</a>
                  <a class="nav-link @if($navName == 'addproduct') active @endif" href="{{ route('backend.products.create') }}">{{ __("Create Product") }}</a>
                  <a class="nav-link @if($navName == 'attributes') active @endif " href="{{route('backend.products.attributes.list')}}">{{ __("Attributes") }}</a>
                  <a class="nav-link @if($navName == 'productscategories') active @endif" href="{{ route('backend.products.categories.list') }}">{{ __("Categories") }}</a>
                  <a class="nav-link @if($navName == 'productstags') active @endif" href="{{ route('backend.products.tags.list') }}">{{ __("Tags") }}</a>
                  <a class="nav-link @if($navName == 'productstrash') active @endif" href="{{ route('backend.products.trash') }}">{{ __("Trash") }}</a>
                </div>
              </div>
              <!-- End Collapse -->

              <!-- Collapse -->
              <div class="nav-item">
                <a class="nav-link nav-link-main dropdown-toggle " href="#navbarVerticalMenuAllOrdersMenu" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuAllOrdersMenu" aria-expanded="false" aria-controls="navbarVerticalMenuAllOrdersMenu">
                  <i class="bi bi-receipt nav-icon"></i>
                  <span class="nav-link-title">{{ __("Orders") }}</span>
                </a>

                <div id="navbarVerticalMenuAllOrdersMenu" class="nav-collapse collapse @if($activePage == 'orders') show @endif" data-bs-parent="#navbarVerticalMenuPagesMenu">
                  <a class="nav-link @if($navName == 'orderslist') active @endif" href="{{ route('backend.orders.list') }}">{{ __("All Orders") }}</a>
                  <a class="nav-link {{$navName}} @if($navName == 'ordersPending') active @endif" href="{{ route('backend.orders.pending') }}">{{ __("Pending") }} <span id="pendingBadge" class="badge bg-primary rounded-pill ms-1">{{ "" }}</span></a>
                </div>
              </div>
              <!-- End Collapse -->

              <div class="nav-item">
                <a class="nav-link nav-link-main " href="#" data-placement="left">
                  <i class="bi-folder2-open nav-icon"></i>
                  <span class="nav-link-title">Coupons</span>
                </a>
              </div>

              <div class="nav-item">
                <a class="nav-link nav-link-main " href="#" data-placement="left">
                  <i class="bi-folder2-open nav-icon"></i>
                  <span class="nav-link-title">Reports</span>
                </a>
              </div>

              <span class="dropdown-header mt-4">{{ __("Configuration") }}</span>

              <div class="nav-item">
                <a class="nav-link nav-link-main " href="{{ route('backend.tax.index') }}" data-placement="left">
                  <i class="bi-key nav-icon"></i>
                  <span class="nav-link-title @if($navName == 'tax') active @endif">Tax</span>
                </a>
              </div>

              <div class="nav-item">
                <a class="nav-link nav-link-main " href="{{ route('backend.shipping.index') }}" data-placement="left">
                  <i class="bi-key nav-icon"></i>
                  <span class="nav-link-title @if($navName == 'Shipping') active @endif">Shipping</span>
                </a>
              </div>

              <div class="nav-item">
                <a class="nav-link nav-link-main " href="{{ route('backend.general.index') }}" data-placement="left">
                  <i class="bi-key nav-icon"></i>
                  <span class="nav-link-title @if($navName == 'ordersPending') active @endif">General</span>
                </a>
              </div>

              <div class="nav-item">
                <a class="nav-link nav-link-main " href="#" data-placement="left">
                  <i class="bi-key nav-icon"></i>
                  <span class="nav-link-title @if($navName == 'ordersPending') active @endif">API Keys</span>
                </a>
              </div>

        </div>
        <!-- End Content -->

        <!-- Footer -->
        <div class="navbar-vertical-footer">
          <ul class="navbar-vertical-footer-list">
          </ul>
        </div>
        <!-- End Footer -->
      </div>
    </div>
  </aside>

  <!-- End Navbar Vertical -->

  <script>
    $(function() {
      $.ajax({
        url: "{{ url('backend/orders') }}",
        type: 'post',
        data: {
          "_token": "{{ csrf_token() }}",
          status: $(this).val()
        },
        success: function (data) {
          $("#pendingBadge").html(data);
        }
      })
    });
  </script>