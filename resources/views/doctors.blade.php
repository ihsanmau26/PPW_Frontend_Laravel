@extends('layouts.main-layout')

@section('title', 'Doctors')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Doctors List</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('doctors.add') }}" class="btn btn-primary form-control">Add Doctor</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="doctorsTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID User</th>
                    <th>ID Doctor</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Specialization</th>
                    <th>Action</th>
                </tr>
            </thead>        
            <tbody>
            </tbody>
        </table>
    </div>
</div>

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
                Authorization: `Bearer ${authToken}`
            }
        });

        function fetchDoctors() {
            $.ajax({
                url: `${apiBaseUrl}/users/doctors`,
                method: 'GET',
                success: function (data) {
                    renderDoctorsTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching doctors:', error.responseJSON || error);
                    alert('Gagal memuat daftar dokter. Periksa konsol untuk detail.');
                }
            });
        }

        function renderDoctorsTable(doctors) {
            const tableBody = $('#doctorsTable tbody');
            tableBody.empty();

            doctors.forEach(doctor => {
                const row = `
                    <tr>
                        <td class="text-center">${doctor.id}</td>
                        <td class="text-center">${doctor.doctor_id ?? '-'}</td>
                        <td>${doctor.name}</td>
                        <td>${doctor.email}</td>
                        <td>${doctor.specialization ?? '-'}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-btn" data-id="${doctor.id}">Detail</button>
                            <button class="btn btn-warning edit-btn" data-id="${doctor.id}">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="${doctor.doctor_id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-btn').on('click', function () {
                const userId = $(this).data('id');
                localStorage.setItem('userId', userId);
                window.location.href = `/doctors/${userId}/detail`;
            });

            $('.edit-btn').on('click', function () {
                const userId = $(this).data('id');
                localStorage.setItem('userId', userId);
                window.location.href = `/doctors/${userId}/edit`;
            });

            $('.delete-btn').on('click', function () {
                const userId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus dokter ini?')) {
                    deleteDoctor(userId);
                }
            });
        }

        function deleteDoctor(userId) {
            $.ajax({
                url: `${apiBaseUrl}/users/doctors/${userId}`,
                method: 'DELETE',
                success: function () {
                    alert('Dokter berhasil dihapus!');
                    fetchDoctors();
                },
                error: function (error) {
                    console.error('Gagal menghapus dokter:', error.responseJSON || error);
                    alert('Gagal menghapus dokter. Periksa konsol untuk detail.');
                }
            });
        }

        fetchDoctors();
    });
</script>
@endsection