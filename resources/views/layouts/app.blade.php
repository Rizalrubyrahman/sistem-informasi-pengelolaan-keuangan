<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link href="{{ asset('admin/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/solid.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/solid.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/regular.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/regular.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/brands.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    @yield('style')
    <title>@yield('title')</title>
</head>

<body>
    @include('sweetalert::alert')
    <div class="wrapper">
        @include('layouts.sidebar')
        <div class="main">
            @include('layouts.navbar')
            <div style="background-color: #FFECC7;height:300px;width:100%" ></div>
            <main class="content" style="background-color: #f5f5f5">

                <div class="container-fluid p-0" style="z-index: 1; margin-top:-300px;">
                    @yield('content')
                </div>
            </main>
            @include('layouts.footer')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="{{ asset('admin/js/app.js') }}"></script>
    <script src="{{ asset('admin/js/fontawesome.js') }}"></script>
    <script src="{{ asset('admin/js/fontawesome.min.js') }}"></script>
    <script src="{{ asset('admin/js/all.js') }}"></script>
    <script src="{{ asset('admin/js/all.min.js') }}"></script>
    <script src="{{ asset('admin/js/solid.js') }}"></script>
    <script src="{{ asset('admin/js/solid.min.js') }}"></script>
    <script src="{{ asset('admin/js/regular.js') }}"></script>
    <script src="{{ asset('admin/js/regular.min.js') }}"></script>
    <script src="{{ asset('admin/js/brands.js') }}"></script>
    <script src="{{ asset('admin/js/brands.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
			var date = new Date(Date.now() - 0 * 24 * 60 * 60 * 1000);
			var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
			document.getElementById("datetimepicker-dashboard").flatpickr({
				inline: true,
				prevArrow: "<span title=\"Previous month\">&laquo;</span>",
				nextArrow: "<span title=\"Next month\">&raquo;</span>",
				defaultDate: defaultDate
			});
		});
	</script>


    @yield('script')

</body>
</html>
