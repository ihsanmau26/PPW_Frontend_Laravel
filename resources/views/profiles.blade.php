@extends('layouts.main-layout')

@section('title', 'Profiles')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="text-center pe-3">
                <div id="profile-photo" class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white" style="width: 100px; height: 100px; font-size: 36px; margin: 0 auto;">
                </div>
                <h2 id="profile-name" class="fs-5 mt-3 mb-3"></h2>
                <button id="editPhotoBtn" class="btn btn-warning mb-3 w-100" data-bs-toggle="modal" data-bs-target="#editPhotoModal">Edit Photo Profil</button>
                <button id="deletePhotoBtn" class="btn btn-danger mb-3 w-100">Delete Photo Profil</button>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="fs-4 mb-4">Profil Pengguna</h2>
            <div class="bg-light p-4 rounded shadow-sm">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">ID</label>
                    <div class="col-sm-9">
                        <span id="user-id" class="form-control-plaintext"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">Nama</label>
                    <div class="col-sm-9">
                        <span id="user-name" class="form-control-plaintext"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">Email</label>
                    <div class="col-sm-9">
                        <span id="user-email" class="form-control-plaintext"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label fw-bold">Role</label>
                    <div class="col-sm-9">
                        <span id="user-role" class="form-control-plaintext"></span>
                    </div>
                </div>
            </div>
            <button class="btn btn-warning mt-3 float-end" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>
        </div>
    </div>
</div>

<div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPhotoModalLabel">Ubah Foto Profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editPhotoForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="photo" class="form-label">Pilih Foto</label>
            <input type="file" class="form-control" id="photo" name="photo" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Ubah Foto Profil</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordModalLabel">Ubah Kata Sandi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="changePasswordForm">
          <div class="mb-3">
            <label for="oldPassword" class="form-label">Kata Sandi Lama</label>
            <input type="password" class="form-control" id="oldPassword" required>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">Kata Sandi Baru</label>
            <input type="password" class="form-control" id="newPassword" required>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input type="password" class="form-control" id="confirmPassword" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Ubah Kata Sandi</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
            alert('Anda belum login!');
            window.location.href = '/login';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`
            }
        });

        let userId = null;
        let userHasPhoto = false;

        function refreshPage() {
            location.reload();
        }

        $.ajax({
            url: `http://localhost:8000/api/me`,
            method: 'GET',
            success: function (data) {
                userId = data.id;

                $('#profile-name').text(data.name);
                $('#user-id').text(data.id);
                $('#user-name').text(data.name);
                $('#user-email').text(data.email);
                $('#user-role').text(data.role);

                $.ajax({
                    url: `http://localhost:8001/photos/user/${userId}`,
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                    },
                    success: function (data) {
                        if (data.photos && data.photos.length > 0) {
                            const photoPath = data.photos[0].photo;
                            $('#profile-photo').html(
                                `<img src="http://127.0.0.1/laravel-tubes-api${photoPath}" alt="Profile Photo" class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white" style="width: 100px; height: 100px; font-size: 36px; margin: 0 auto;">`
                            );
                            userHasPhoto = true;
                        } else {
                            const initials = data.name && data.name.split(' ')
                                .map(word => word[0])
                                .join('');
                            $('#profile-photo').text(initials || '');
                            userHasPhoto = false;
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 404) {
                            const initials = data.name && data.name.split(' ')
                                .map(word => word[0])
                                .join('');
                            $('#profile-photo').text(initials || '');
                            userHasPhoto = false;
                        } else {
                            alert('Gagal mengambil foto pengguna.');
                        }
                    }
                });

                $('#editPhotoBtn').click(function () {
                    if (userHasPhoto) {
                        $('#editPhotoForm').unbind('submit').submit(function (e) {
                            e.preventDefault();

                            const formData = new FormData();
                            formData.append('photo', $('#photo')[0].files[0]);

                            $.ajax({
                                url: `http://localhost:8001/photos/${userId}`,
                                method: 'PUT',
                                headers: {
                                    'Authorization': `Bearer ${authToken}`
                                },
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function () {
                                    alert('Foto berhasil diperbarui!');
                                    $('#editPhotoModal').modal('hide');
                                    refreshPage();
                                },
                                error: function () {
                                    alert('Gagal memperbarui foto.');
                                }
                            });
                        });
                    } else {
                        $('#editPhotoForm').unbind('submit').submit(function (e) {
                            e.preventDefault();

                            const formData = new FormData();
                            formData.append('photo', $('#photo')[0].files[0]);
                            formData.append('user_id', userId);

                            $.ajax({
                                url: `http://localhost:8001/photos`,
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${authToken}`
                                },
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function () {
                                    alert('Foto berhasil diunggah!');
                                    $('#editPhotoModal').modal('hide');
                                    refreshPage();
                                },
                                error: function () {
                                    alert('Gagal mengunggah foto.');
                                }
                            });
                        });
                    }
                });

                $('#deletePhotoBtn').click(function () {
                    if (!userHasPhoto) {
                        alert('Tidak ada foto untuk dihapus!');
                        return;
                    }

                    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                        $.ajax({
                            url: `http://localhost:8001/photos/${userId}`,
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${authToken}`
                            },
                            success: function () {
                                alert('Foto berhasil dihapus!');
                                refreshPage();
                            },
                            error: function () {
                                alert('Gagal menghapus foto.');
                            }
                        });
                    }
                });
            },
            error: function () {
                alert('Gagal mengambil data pengguna.');
            }
        });

        $('#changePasswordForm').submit(function (e) {
            e.preventDefault();

            const oldPassword = $('#oldPassword').val();
            const newPassword = $('#newPassword').val();
            const confirmPassword = $('#confirmPassword').val();

            if (newPassword !== confirmPassword) {
                alert('Konfirmasi kata sandi tidak cocok.');
                return;
            }

            $.ajax({
                url: 'http://localhost:8000/api/change-password',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    old_password: oldPassword,
                    new_password: newPassword,
                    new_password_confirmation: confirmPassword
                }),
                success: function () {
                    alert('Kata sandi berhasil diubah.');
                    $('#changePasswordModal').modal('hide');
                    $('#changePasswordForm')[0].reset();
                    refreshPage();
                },
                error: function (xhr) {
                    if (xhr.status === 400) {
                        alert(xhr.responseJSON.message || 'Gagal mengubah kata sandi.');
                    } else {
                        alert('Terjadi kesalahan pada server.');
                    }
                }
            });
        });
    });
</script>
@endsection
