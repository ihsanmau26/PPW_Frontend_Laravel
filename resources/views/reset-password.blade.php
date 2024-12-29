@extends('layouts.auth-layout')

@section('title', 'Reset Password')

@section('content')
<div class="text-center mt-3">
    <i class="fa-solid fa-square-plus text-danger fs-1"></i>
    <h4 class="mt-3">RESET PASSWORD</h4>
    <p>Masukan password baru</p>
</div>

<form id="resetPasswordForm" class="mt-4">
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-key"></i>
            </span>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
        </div>
    </div>
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-lock"></i>
            </span>
            <input type="password" class="form-control" id="password_confirmation" placeholder="Password Confirmation" required>
        </div>
    </div>
    <input type="hidden" id="token" value="{{ $token }}">

    <div class="d-flex justify-content-between mt-3">
        <a href="/login" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-success w-75">Reset Password</button>
    </div>
</form>

<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0 text-center">
        <h5 class="modal-title w-100" id="loadingModalLabel">
          <i class="fa-solid fa-spinner fa-spin fa-3x text-success"></i><br>
          Loading...
        </h5>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title text-success" id="emailSentModalLabel">
          <i class="fa-solid fa-circle-check"></i> Reset Password Berhasil
        </h5>
      </div>
      <div class="modal-body">
        <p>Password berhasil diubah. Silahkan kembali kehalaman Login untuk melanjutkan.</p>
      </div>
      <div class="modal-footer border-0">
        <a href="/login" class="text-decoration-none">Login</a>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  const apiBaseUrl = 'http://localhost:8000/api';

  $(document).ready(function () {
      $('#resetPasswordForm').submit(function (e) {
          e.preventDefault();

          const password = $('#password').val();
          const passwordConfirmation = $('#password_confirmation').val();
          const token = $('#token').val();

          const email = localStorage.getItem('email');

          if (!email) {
              alert('Email tidak ditemukan di localStorage.');
              return;
          }

          if (password !== passwordConfirmation) {
              alert("Password dan konfirmasi password tidak cocok.");
              return;
          }

          $('#loadingModal').modal('show');

          $.ajax({
              url: `${apiBaseUrl}/reset-password`,
              method: 'POST',
              contentType: 'application/json',
              dataType: 'json',
              headers: {
                  'Accept': 'application/json',
              },
              data: JSON.stringify({
                  token: token,
                  email: email,
                  password: password,
                  password_confirmation: passwordConfirmation
              }),
              success: function (data) {
                  $('#loadingModal').modal('hide');
                  $('#resetPasswordModalLabel').text("Password Berhasil Diperbarui");
                  $('#resetPasswordMessage').text(data.message);
                  $('#resetPasswordModal').modal('show');
              },
              error: function (jqXHR) {
                  $('#loadingModal').modal('hide');
                  const errorMessage = jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                  $('#resetPasswordModalLabel').text("Gagal Mengatur Ulang Password");
                  $('#resetPasswordMessage').text(errorMessage);
                  $('#resetPasswordModal').modal('show');
              }
          });
      });
  });
</script>
@endsection
