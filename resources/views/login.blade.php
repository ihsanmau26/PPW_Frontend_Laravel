@extends('layouts.auth-layout')

@section('title', 'Login')

@section('content')
<div class="text-center mt-3">
    <i class="fa-solid fa-square-plus text-danger fs-1"></i>
    <h4 class="mt-3">TELKOM MEDIKA</h4>
    <p>Silahkan login untuk melanjutkan!</p>
</div>

<form id="loginForm" class="mt-4">
    @csrf
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-envelope"></i>
            </span>
            <input type="email" class="form-control" id="email" placeholder="Email" required>
        </div>
    </div>
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-lock"></i>
            </span>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-success w-100 mt-3 mb-3">Login</button>
    <div class="text-center">
        <a href="/forgot-password" class="text-decoration-none">Lupa Password?</a>
    </div>    
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const apiBaseUrl = 'http://localhost:8000/api';

    $(document).ready(function () {
        $('#loginForm').submit(function (e) {
            e.preventDefault();

            const email = $('#email').val();
            const password = $('#password').val();

            $.ajax({
                url: `${apiBaseUrl}/login`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ email, password }),
                success: function (data) {
                    localStorage.setItem('authToken', data);
                    window.location.href = '/home';
                },
                error: function (jqXHR) {
                    console.error('Login gagal:', jqXHR.responseJSON);
                    alert('Login gagal. Periksa kembali email dan password Anda.');
                }
            });
        });
    });
</script>

@endsection
