@extends('layouts.main-layout')

@section('title', 'Patients')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Patients List</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('patients.add') }}" class="btn btn-primary form-control">Add Patient</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="patientsTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID User</th>
                    <th>ID Patient</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Date Of Birth</th>
                    <th>Phone Number</th>
                    <th class="col-3">Action</th>
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

        function fetchPatients() {
            $.ajax({
                url: `${apiBaseUrl}/users/patients`,
                method: 'GET',
                success: function (data) {
                    renderPatientsTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching patients:', error.responseJSON || error);
                    alert('Gagal memuat daftar pasien. Periksa konsol untuk detail.');
                }
            });
        }

        function renderPatientsTable(patients) {
            const tableBody = $('#patientsTable tbody');
            tableBody.empty();

            patients.forEach(patient => {
                const row = `
                    <tr>
                        <td class="text-center">${patient.id}</td>
                        <td class="text-center">${patient.patient_id ?? '-'}</td>
                        <td>${patient.name}</td>
                        <td>${patient.email}</td>
                        <td>${patient.gender ?? '-'}</td>
                        <td>${patient.age ?? '-'}</td>
                        <td>${patient.date_of_birth ?? '-'}</td>
                        <td>${patient.phone_number ?? '-'}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-btn" data-id="${patient.id}">Detail</button>
                            <button class="btn btn-warning edit-btn" data-id="${patient.id}">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="${patient.patient_id}">Delete</button>
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
                window.location.href = `/patients/${userId}/detail`;
            });

            $('.edit-btn').on('click', function () {
                const userId = $(this).data('id');
                localStorage.setItem('userId', userId);
                window.location.href = `/patients/${userId}/edit`;
            });

            $('.delete-btn').on('click', function () {
                const userId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus pasien ini?')) {
                    deletePatient(userId);
                }
            });
        }

        function deletePatient(userId) {
            $.ajax({
                url: `${apiBaseUrl}/users/patients/${userId}`,
                method: 'DELETE',
                success: function () {
                    alert('Pasien berhasil dihapus!');
                    fetchPatients();
                },
                error: function (error) {
                    console.error('Gagal menghapus pasien:', error.responseJSON || error);
                    alert('Gagal menghapus pasien. Periksa konsol untuk detail.');
                }
            });
        }

        fetchPatients();
    });
</script>
@endsection