@extends('layouts.auth-layout')

@section('title', 'Forgot Password')

@section('content')
<div class="text-center mt-3">
    <i class="fa-solid fa-square-plus text-danger fs-1"></i>
    <h4 class="mt-3">LUPA PASSWORD</h4>
    <p>Masukan email Anda untuk mengatur ulang password</p>
</div>

<form id="forgotPasswordForm" class="mt-4">
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fa-solid fa-envelope"></i>
            </span>
            <input type="email" class="form-control" id="email" placeholder="Email" required>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <a href="/login" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-success w-75" id="submitBtn">Reset Password</button>
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

<div class="modal fade" id="emailSentModal" tabindex="-1" aria-labelledby="emailSentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title text-success" id="emailSentModalLabel">
          <i class="fa-solid fa-circle-check"></i> Email Terkirim
        </h5>
      </div>
      <div class="modal-body">
        <p>Instruksi untuk mengatur ulang password telah dikirim ke email Anda. Silakan cek inbox atau folder spam Anda.</p>
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
      $('#forgotPasswordForm').submit(function (e) {
          e.preventDefault();

          const email = $('#email').val();

          localStorage.setItem('email', email);

          $('#loadingModal').modal('show');
          $('#submitBtn').prop('disabled', true);

          $.ajax({
              url: `${apiBaseUrl}/forgot-password`,
              method: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({ email }),
              success: function (data) {
                  $('#loadingModal').modal('hide');
                  $('#emailSentModal').modal('show');
              },
              error: function (jqXHR) {
                  $('#loadingModal').modal('hide');
                  $('#submitBtn').prop('disabled', false);
                  alert('Terjadi kesalahan. Silakan coba lagi.');
              }
          });
      });
  });
</script>
@endsection
