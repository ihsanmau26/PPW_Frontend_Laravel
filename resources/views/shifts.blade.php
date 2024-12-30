@extends('layouts.main-layout')

@section('title', 'Shifts')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Shifts List</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('shifts.add') }}" class="btn btn-primary form-control">Add Shift</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="shiftsTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Day</th>
                    <th>Shift Start</th>
                    <th>Shift End</th>
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

        function fetchShifts() {
            $.ajax({
                url: `${apiBaseUrl}/shifts`,
                method: 'GET',
                success: function (data) {
                    renderShiftsTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching shifts:', error.responseJSON || error);
                    alert('Gagal memuat shifts. Periksa konsol untuk detail.');
                }
            });
        }

        function renderShiftsTable(shifts) {
            const tableBody = $('#shiftsTable tbody');
            tableBody.empty();

            shifts.forEach(shift => {
                const row = `
                    <tr>
                        <td class="text-center">${shift.id}</td>
                        <td>${shift.day}</td>
                        <td>${shift.shift_start}</td>
                        <td>${shift.shift_end}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-btn" data-id="${shift.id}">Detail</button>
                            <button class="btn btn-warning edit-btn" data-id="${shift.id}">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="${shift.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-btn').on('click', function () {
                const shiftId = $(this).data('id');
                localStorage.setItem('shiftId', shiftId);
                window.location.href = `/shifts/${shiftId}/detail`;
            });

            $('.edit-btn').on('click', function () {
                const shiftId = $(this).data('id');
                localStorage.setItem('shiftId', shiftId);
                window.location.href = `/shifts/${shiftId}/edit`;
            });

            $('.delete-btn').on('click', function () {
                const shiftId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus shift ini?')) {
                    deleteShift(shiftId);
                }
            });
        }

        function deleteShift(shiftId) {
            $.ajax({
                url: `${apiBaseUrl}/shifts/${shiftId}`,
                method: 'DELETE',
                success: function () {
                    alert('Shift berhasil dihapus!');
                    fetchShifts();
                },
                error: function (error) {
                    console.error('Gagal menghapus shift:', error.responseJSON || error);
                    alert('Gagal menghapus shift. Ada Dokter yang shift di waktu tersebut.');
                }
            });
        }

        fetchShifts();
    });
</script>
@endsection
