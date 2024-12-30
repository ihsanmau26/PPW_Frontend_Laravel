@extends('layouts.main-layout')

@section('title', 'Medicines')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Medicines List</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('medicines.add') }}" class="btn btn-primary form-control">Add Medicine</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="medicinesTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Description</th>
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

        function fetchMedicines() {
            $.ajax({
                url: `${apiBaseUrl}/medicines`,
                method: 'GET',
                success: function (data) {
                    renderMedicinesTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching medicines:', error.responseJSON || error);
                    alert('Gagal memuat medicines. Periksa konsol untuk detail.');
                }
            });
        }

        function renderMedicinesTable(medicines) {
            const tableBody = $('#medicinesTable tbody');
            tableBody.empty();

            medicines.forEach(medicine => {
                const row = `
                    <tr>
                        <td class="text-center">${medicine.id}</td>
                        <td>${medicine.name}</td>
                        <td>${medicine.type}</td>
                        <td>${medicine.description}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-btn" data-id="${medicine.id}">Detail</button>
                            <button class="btn btn-warning edit-btn" data-id="${medicine.id}">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="${medicine.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-btn').on('click', function () {
                const medicineId = $(this).data('id');
                localStorage.setItem('medicineId', medicineId);
                window.location.href = `/medicines/${medicineId}/detail`;
            });

            $('.edit-btn').on('click', function () {
                const medicineId = $(this).data('id');
                localStorage.setItem('medicineId', medicineId);
                window.location.href = `/medicines/${medicineId}/edit`;
            });

            $('.delete-btn').on('click', function () {
                const medicineId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus medicine ini?')) {
                    deleteMedicine(medicineId);
                }
            });
        }

        function deleteMedicine(medicineId) {
            $.ajax({
                url: `${apiBaseUrl}/medicines/${medicineId}`,
                method: 'DELETE',
                success: function () {
                    alert('Medicine berhasil dihapus!');
                    fetchMedicines();
                },
                error: function (error) {
                    console.error('Gagal menghapus medicines:', error.responseJSON || error);
                    alert('Gagal menghapus medicines. Periksa konsol untuk detail.');
                }
            });
        }

        fetchMedicines();
    });
</script>
@endsection