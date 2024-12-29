<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | Telkom Medika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/home">Telkom Medika</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('articles') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                            <i class="fas fa-newspaper"></i> Article
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('checkups') ? 'active' : '' }}" href="{{ route('checkups.index') }}">
                            <i class="fas fa-clock"></i> Checkup
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('histories') ? 'active' : '' }}" href="{{ route('histories.index') }}">
                            <i class="fas fa-history"></i> History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('medicines') ? 'active' : '' }}" href="{{ route('medicines.index') }}">
                            <i class="fas fa-tablets"></i> Medicine
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('shifts') ? 'active' : '' }}" href="{{ route('shifts.index') }}">
                            <i class="fas fa-calendar-days"></i> Shift
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('patients') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                            <i class="fa fa-address-book"></i> Patient
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('doctors') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                            <i class="fas fa-user-md"></i> Doctor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('profiles') ? 'active' : '' }}" href="{{ route('profiles.index') }}">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>                    
                </ul>
                </ul>
                <ul class="navbar-nav">
                    <a class="nav-link text-white" href="javascript:void(0)" id="logoutButton">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @yield('content')
        @yield('content-2')
        @yield('content-3')
        @yield('content-4')
        @yield('content-5')
        @yield('content-6')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const apiBaseUrl = 'http://127.0.0.1:8000/api';
        let authToken = localStorage.getItem('authToken');

        $(document).ready(function () {
            $('#logoutButton').click(function () {
                if (!authToken) {
                    alert('Anda sudah logout.');
                    window.location.href = '/login';
                    return;
                }

                $.ajax({
                    url: `${apiBaseUrl}/logout`,
                    method: 'POST',
                    headers: { Authorization: `Bearer ${authToken}` },
                    success: function () {
                        localStorage.removeItem('authToken');
                        alert('Logout berhasil.');
                        window.location.href = '/login';
                    },
                    error: function (jqXHR) {
                        console.error('Logout gagal:', jqXHR.responseJSON);
                        alert('Terjadi kesalahan saat logout.');
                    }
                });
            });

            if (!localStorage.getItem('authToken')) {
                alert('Anda harus login untuk mengakses halaman ini.');
                window.location.href = '/login';
            }
        });
    </script>
</body>
</html>
