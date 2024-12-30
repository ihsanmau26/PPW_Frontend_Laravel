@extends('layouts.main-layout')

@section('title', 'Checkups')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Checkups List</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('checkups.add') }}" class="btn btn-primary form-control">Add Checkup</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="checkupsTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th class="col-4">Action</th>
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
        
        function fetchCheckups() {
            $.ajax({
                url: `${apiBaseUrl}/checkups`,
                method: 'GET',
                success: function (data) {
                    renderCheckupsTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching checkups:', error.responseJSON || error);
                    alert('Gagal memuat checkups. Periksa konsol untuk detail.');
                }
            });
        }

        function renderCheckupsTable(checkups) {
            const tableBody = $('#checkupsTable tbody');
            tableBody.empty();

            checkups.forEach(checkup => {
                let actionButton;

                if (checkup.status === 'Selesai') {
                    actionButton = `
                        <button class="btn btn-success prescription-btn" data-id="${checkup.id}">Prescription</button>
                    `;
                } else {
                    actionButton = `
                        <button class="btn btn-info status-btn" data-id="${checkup.id}" data-status="${checkup.status}">Status</button>
                    `;
                }

                const row = `
                    <tr>
                        <td class="text-center">${checkup.id}</td>
                        <td>${checkup.patient.name}</td>
                        <td>${checkup.doctor.name}</td>
                        <td>${checkup.checkup_type}</td>
                        <td>${checkup.checkup_date}</td>
                        <td>${checkup.checkup_time}</td>
                        <td class="text-center">${checkup.status}</td>
                        <td class="text-center">
                            ${actionButton}
                            <button class="btn btn-primary detail-btn" data-id="${checkup.id}">Detail</button>
                            <button class="btn btn-warning edit-btn" data-id="${checkup.id}">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="${checkup.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.status-btn').on('click', function () {
                const checkupId = $(this).data('id');
                const currentStatus = $(this).data('status');

                let newStatus;
                if (currentStatus === 'Belum Selesai') {
                    newStatus = 'Dalam Proses';
                } else if (currentStatus === 'Dalam Proses') {
                    newStatus = 'Selesai';
                } else {
                    alert('Status sudah selesai dan tidak dapat diubah.');
                    return;
                }

                updateStatus(checkupId, newStatus);
            });

            $('.prescription-btn').on('click', function () {
                const checkupId = $(this).data('id');
                localStorage.setItem('checkupId', checkupId);
                window.location.href = `/checkups/${checkupId}/prescription`;
            });

            $('.detail-btn').on('click', function () {
                const checkupId = $(this).data('id');
                localStorage.setItem('checkupId', checkupId);
                window.location.href = `/checkups/${checkupId}/detail`;
            });

            $('.edit-btn').on('click', function () {
                const checkupId = $(this).data('id');
                localStorage.setItem('checkupId', checkupId);
                window.location.href = `/checkups/${checkupId}/edit`;
            });

            $('.delete-btn').on('click', function () {
                const checkupId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus checkup ini?')) {
                    deleteCheckup(checkupId);
                }
            });
        }

        function updateStatus(checkupId, newStatus) {
            $.ajax({
                url: `${apiBaseUrl}/checkups/status/${checkupId}`,
                method: 'PATCH',
                contentType: 'application/json',
                data: JSON.stringify({ status: newStatus }),
                success: function (response) {
                    alert('Status berhasil diperbarui.');
                    fetchCheckups();
                },
                error: function (error) {
                    console.error('Gagal memperbarui status:', error.responseJSON || error);
                    alert('Gagal memperbarui status. Periksa konsol untuk detail.');
                }
            });
        }

        function deleteCheckup(checkupId) {
            $.ajax({
                url: `${apiBaseUrl}/checkups/${checkupId}`,
                method: 'DELETE',
                success: function () {
                    alert('Checkup berhasil dihapus!');
                    fetchCheckups();
                },
                error: function (error) {
                    console.error('Gagal menghapus checkup:', error.responseJSON || error);
                    alert('Gagal menghapus checkup. Periksa konsol untuk detail.');
                }
            });
        }

        fetchCheckups();
    });
</script>
@endsection