    <style>
        .dropdown-menu .before{
            opacity: 1;
        }
        .dropdown-menu .after{
            opacity: 0.5;
        }
        .preview-item .language{
            display: none;
        }
        .preview-item .selected i{
            display: inline;
        }
    </style>
    <nav class="navbar p-0 fixed-top d-flex flex-row">
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav w-100">
            <li class="nav-item w-100">
            <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                <input type="text" class="form-control" placeholder="Search products">
            </form>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-email"></i>
                    <span class="count-number bg-primary">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <h6 class="p-3 mb-0">Messages</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <img src="{{ asset('assets/images/faces/face4.jpg') }}" alt="image" class="rounded-circle profile-pic">
                    </div>
                    <div class="preview-item-content">
                        <p class="preview-subject ellipsis mb-1">Mark send you a message</p>
                        <p class="text-muted mb-0"> 1 Minutes ago </p>
                    </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <img src="{{ asset('assets/images/faces/face2.jpg') }}" alt="image" class="rounded-circle profile-pic">
                    </div>
                    <div class="preview-item-content">
                        <p class="preview-subject ellipsis mb-1">Cregh send you a message</p>
                        <p class="text-muted mb-0"> 15 Minutes ago </p>
                    </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <img src="{{ asset('assets/images/faces/face3.jpg') }}" alt="image" class="rounded-circle profile-pic">
                    </div>
                    <div class="preview-item-content">
                        <p class="preview-subject ellipsis mb-1">Profile picture updated</p>
                        <p class="text-muted mb-0"> 18 Minutes ago </p>
                    </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center">4 new messages</p>
                </div>
            </li>
            <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell"></i>
                    @if(auth()->user()->unreadNotifications->isNotEmpty())
                        <span class="count-number bg-danger">{{ count(auth()->user()->unreadNotifications) }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">Notifications</h6>
                    <div class="dropdown-divider"></div>
                    @forelse (auth()->user()->notifications as $index => $notification)
                        @if($index < 3)
                            <a class="dropdown-item preview-item {{ $notification->unread() ? 'before' : 'after' }}" href="{{ route('notifications.detail', $notification->id) }}">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                    <i class="mdi {{ $notification->data['type'] }}"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">{{ $notification->data['title'] }}</p>
                                    <p class="text-muted ellipsis mb-0">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        @endif
                    @empty
                        <p class="p-3 mb-0 text-center">No notifications available</p>
                    @endforelse
                    @if(auth()->user()->notifications->isNotEmpty())
                        <p class="p-3 mb-0 text-center"><a href="{{ route('notifications') }}" style="text-decoration: none;color:white">See all notifications</p>
                    @endif
                </div>
            </li>
            <li class="nav-item dropdown border-left">
                <a class="nav-link indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="fa-solid fa-earth-americas" style="font-size: 1.3rem"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item" href="{{ route('set_language', 'en') }}">
                        <div class="preview-item-content d-flex justify-content-between {{ session('locale') == 'en' ? 'selected' : '' }}" style="width: 100%">
                            <p class="preview-subject mb-1">Inggris</p>
                            <i class="fa-solid fa-circle-check text-success language"></i>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item" href="{{ route('set_language', 'id') }}">
                        <div class="preview-item-content d-flex justify-content-between {{ session('locale') == 'id' ? 'selected' : '' }}" style="width: 100%">
                            <p class="preview-subject mb-1">Indonesia</p>
                            <i class="fa-solid fa-circle-check text-success language"></i>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                </div>
            </li>
            <li class="nav-item dropdown">
            <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                <div class="navbar-profile">
                <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ Auth::user()->name }}</p>
                <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                </div>
            </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                    <h6 class="p-3 mb-0">Profile</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item" href="{{ route('profile') }}">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-account-circle text-success"></i>
                        </div>
                    </div>
                    <div class="preview-item-content">
                        <p class="preview-subject mb-1">Profile</p>
                    </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item" href="{{ route('change-password') }}">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-onepassword text-info"></i>
                        </div>
                    </div>
                    <div class="preview-item-content">
                        <p class="preview-subject mb-1">Change Password</p>
                    </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ Route('logout') }}" method="post" class="dropdown-item preview-item">
                    @csrf
                        <div class="preview-thumbnail">
                        <div class="preview-icon bg-dark rounded-circle">
                            <i class="mdi mdi-logout text-danger"></i>
                        </div>
                        </div>
                        <button type="submit" class="preview-item-content border-0 bg-transparent" style="color: white;font-size: 1rem;">
                        <p class="preview-subject mb-1">Log out</p>
                        </button>
                    </form>
                    <div class="dropdown-divider"></div>
            </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
        </div>
    </nav>
