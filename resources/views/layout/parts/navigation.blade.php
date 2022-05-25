<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('organization.index') }}" class="brand-link elevation-4">
      <img src="{{ asset('logo.jpeg') }}" alt="CM" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('organization.index') }}" class="nav-link {{ str_contains(Route::currentRouteName(), 'organization') ? 'active' : '' }}">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Organizations
              </p>
            </a>
          </li>
          @if (Auth::user()->isAdmin)
            <li class="nav-item">
              <a href="{{ route('user.index') }}" class="nav-link {{ str_contains(Route::currentRouteName(), 'user.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Account Managers
                </p>
              </a>
            </li>
          @endif
          <li class="nav-item">
            <a href="{{ route('user.edit', Auth::id()) }}" class="nav-link {{ str_contains(Route::currentRouteName(), 'user.edit') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profile
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>