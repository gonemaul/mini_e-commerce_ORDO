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
              <h5 class="mb-0 font-weight-normal">{{ Auth::user()->name }}</h5>
              <span>Administrator</span>
            </div>
          </div>
          <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
          <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
            <a href="" class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-settings text-primary"></i>
                </div>
              </div>
              <div class="preview-item-content">
                <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-onepassword  text-info"></i>
                </div>
              </div>
              <div class="preview-item-content">
                <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
              </div>
            </a>
            <div class="dropdown-divider"></div>
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
      <li class="nav-item menu-items {{  Request::is('Users') ? 'active' : ''  }}">
        <a class="nav-link" href="">
          <span class="menu-icon">
            <i class="mdi mdi-account-multiple"></i>
          </span>
          <span class="menu-title">Users</span>
        </a>
      </li>
      <li class="nav-item menu-items {{  Request::is('categories/*') ? 'active' : ''  }}">
        <a class="nav-link" href="">
          <span class="menu-icon">
            <i class="mdi mdi-apps"></i>
          </span>
          <span class="menu-title">Categories</span>
        </a>
      </li>
      <li class="nav-item menu-items {{  Request::is('products/*') ? 'active' : ''  }}">
        <a class="nav-link" href="">
          <span class="menu-icon">
            <i class="mdi mdi-package-variant-closed"></i>
          </span>
          <span class="menu-title">Products</span>
        </a>
      </li>
      <li class="nav-item menu-items {{  Request::is('orders/*') ? 'active' : ''  }}">
        <a class="nav-link" href="">
          <span class="menu-icon">
            <i class="mdi mdi-cart"></i>
          </span>
          <span class="menu-title">Orders</span>
        </a>
      </li>
    </ul>
  </nav>
