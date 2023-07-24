<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('admin-assets') }}" data-template="vertical-menu-template-free">

<head>
  <title>@yield('title') | Nama Situs</title>

  @include('includes.meta')

  @stack('before-style')
  @include('includes.style')
  @stack('after-style')
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      @include('includes.navbar')
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        {{-- @include('includes.header') --}}
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
          </div>
          <!-- / Content -->

          <!-- Footer -->
          @include('includes.footer')
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <div class="buy-now">
    <a href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/" target="_blank"
      class="btn btn-danger btn-buy-now">Upgrade to Pro</a>
  </div>

  @stack('before-script')
  @include('includes.script')
  @stack('after-script')
</body>

</html>
