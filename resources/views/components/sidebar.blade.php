<nav class="sidebar sidebar-offcanvas " id="sidebar">
    <ul class="nav">
      <li class="nav-item profile mb-4">
        <div class="profile-desc">
          <div class="profile-pic">
            <div class="count-indicator">
              @if(Auth::user()->profile_image)
                <img class="img-xs rounded-circle " src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="">
              @else
                <img class="img-xs rounded-circle " src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF" alt="">
              @endif
              <span class="count bg-success"></span>
            </div>
            <div class="profile-name">
              <h5 class="mb-0 font-weight-normal">Hi, {{ Auth::user()->name }}</h5>
              <span>{{ auth()->user()->email }}</span>
            </div>
          </div>
        </div>
      </li>
      <li class="nav-item nav-category">
        <span class="nav-link">Navigation</span>
      </li>
      <li class="nav-item menu-items {{  Request::is('/') ? 'active' : ''  }}">
        <a class="nav-link" href="{{ Route('dashboard') }}">
          <span class="menu-icon">
            <i class="mdi mdi-speedometer"></i>
          </span>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      @canany(['user_view', 'user_detail', 'user_export'])
      <li class="nav-item menu-items {{  Request::is('users*') ? 'active' : ''  }}">
        <a class="nav-link" href="{{ route('users.list') }}">
          <span class="menu-icon">
            <i class="mdi mdi-account-multiple"></i>
          </span>
          <span class="menu-title">Users</span>
        </a>
      </li>
      @endcanany
      @canany(['category_view', 'category_create', 'category_edit', 'category_delete', 'category_exim'])
      <li class="nav-item menu-items {{  Request::is('categories*') ? 'active' : ''  }}">
        <a class="nav-link" href="{{ route('categories.index') }}">
          <span class="menu-icon">
            <i class="mdi mdi-apps"></i>
          </span>
          <span class="menu-title">Categories</span>
        </a>
      </li>
      @endcanany
      @canany(['product_view', 'product_create', 'product_edit', 'product_delete', 'product_exim'])
      <li class="nav-item menu-items {{  Request::is('products*') ? 'active' : ''  }}">
        <a class="nav-link" href="{{ route('products.index') }}">
          <span class="menu-icon">
            <i class="mdi mdi-package-variant-closed"></i>
          </span>
          <span class="menu-title">Products</span>
        </a>
      </li>
      @endcanany
      @canany(['order_view', 'order_view_detail', 'order_export', 'order_update'])
      <li class="nav-item menu-items {{  Request::is('orders*') ? 'active' : ''  }}">
        <a class="nav-link" href="{{ route('orders.list') }}">
          <span class="menu-icon">
            <i class="mdi mdi-cart"></i>
          </span>
          <span class="menu-title">Orders</span>
        </a>
      </li>
      @endcanany
        @can('assign_roles')
        <li class="nav-item nav-category">
            <span class="nav-link">User Access</span>
        </li>
        <li class="nav-item menu-items {{  (Request::is('roles*') && !Request::is('roles/create')) ? 'active' : ''  }}">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <span class="menu-icon">
                <i class="mdi mdi-account-key"></i>
            </span>
            <span class="menu-title">Access</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('roles.index') }}"> All Role</a></li>
                    @foreach ($roles as $role)
                        @if($role->name != 'Super Admin')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('roles.show' , $role->id) }}"> {{ $role->name }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </li>
        @endcan
        @role('Super Admin')
        <li class="nav-item nav-category">
          <span class="nav-link">Super Admin</span>
        </li>
        <li class="nav-item menu-items {{  Request::is('roles/create') ? 'active' : ''  }}">
            <a class="nav-link" href="{{ route('roles.create') }}">
            <span class="menu-icon">
                <i class="fa-solid fa-key"></i>
            </span>
            <span class="menu-title">Add Role</span>
            </a>
        </li>
        @endrole
    </ul>
  </nav>
