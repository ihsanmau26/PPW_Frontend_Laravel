@extends('layouts.main-layout')

@section('title', 'Check Up Histories')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Check Up Histories List</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="checkupHistoriesTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Diagnosis</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th class="col-3">File</th>
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

        function fetchCheckupHistories() {
            $.ajax({
                url: `${apiBaseUrl}/checkup-histories`,
                method: 'GET',
                success: function (data) {
                    renderCheckupHistoriesTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching checkup histories:', error.responseJSON || error);
                    alert('Gagal memuat data riwayat check-up. Periksa konsol untuk detail.');
                }
            });
        }

        function renderCheckupHistoriesTable(histories) {
            const tableBody = $('#checkupHistoriesTable tbody');
            tableBody.empty();

            histories.forEach(history => {
                const row = `
                    <tr>
                        <td>${history.id}</td>
                        <td>${history.checkup.patient.name || '-'}</td>
                        <td>${history.checkup.doctor.name || '-'}</td>
                        <td>${history.diagnosis || '-'}</td>
                        <td>${history.checkup.checkup_type || '-'}</td>
                        <td>${history.checkup.checkup_date || '-'}</td>
                        <td>${history.checkup.checkup_time || '-'}</td>
                        <td class="text-center">
                            <button class="btn btn-secondary pdf1-btn" data-id="${history.id}"><i class="fa-solid fa-download"></i> Letter</button>
                            <button class="btn btn-secondary pdf2-btn" data-id="${history.id}"><i class="fa-solid fa-download"></i> Prescription</button>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-btn" data-id="${history.id}">Detail</button>
                            <button class="btn btn-warning edit-btn" data-id="${history.id}">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="${history.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-btn').on('click', function () {
                const historyId = $(this).data('id');
                localStorage.setItem('historyId', historyId);
                window.location.href = `/histories/${historyId}/detail`;
            });

            $('.edit-btn').on('click', function () {
                const historyId = $(this).data('id');
                localStorage.setItem('historyId', historyId);
                window.location.href = `/histories/${historyId}/edit`;
            });

            $('.delete-btn').on('click', function () {
                const historyId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus riwayat ini?')) {
                    deleteCheckupHistory(historyId);
                }
            });

            $('.pdf1-btn').on('click', function () {
                const historyId = $(this).data('id');
                letterPDF(historyId);
            });

            $('.pdf2-btn').on('click', function () {
                const historyId = $(this).data('id');
                prescriptionPDF(historyId);
            });
        }

        function deleteCheckupHistory(historyId) {
            $.ajax({
                url: `${apiBaseUrl}/checkup-histories/${historyId}`,
                method: 'DELETE',
                success: function () {
                    alert('Check-up History berhasil dihapus!');
                    fetchCheckupHistories();
                },
                error: function (error) {
                    console.error('Error deleting checkup history:', error.responseJSON || error);
                    alert('Gagal menghapus checkup history. Periksa konsol untuk detail.');
                }
            });
        }

        function letterPDF(historyId) {
            $.ajax({
                url: `${apiBaseUrl}/checkup-histories/${historyId}/sick-leave-letter-pdf`,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (data, status, xhr) {
                    const blob = new Blob([data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;

                    const contentDisposition = xhr.getResponseHeader('Content-Disposition');
                    const fileName = contentDisposition
                        ? contentDisposition.split('filename=')[1].replace(/"/g, '')
                        : `sick-leave-letter_${historyId}.pdf`;

                    link.setAttribute('download', fileName);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function (error) {
                    console.error('Error downloading PDF:', error.responseJSON || error);
                    alert('Gagal mengunduh file PDF. Periksa konsol untuk detail.');
                }
            });
        }

        function prescriptionPDF(historyId) {
            $.ajax({
                url: `${apiBaseUrl}/checkup-histories/${historyId}/prescription-pdf`,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (data, status, xhr) {
                    const blob = new Blob([data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;

                    const contentDisposition = xhr.getResponseHeader('Content-Disposition');
                    const fileName = contentDisposition
                        ? contentDisposition.split('filename=')[1].replace(/"/g, '')
                        : `prescription_${historyId}.pdf`;

                    link.setAttribute('download', fileName);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function (error) {
                    console.error('Error downloading PDF:', error.responseJSON || error);
                    alert('Gagal mengunduh file PDF. Periksa konsol untuk detail.');
                }
            });
        }

        fetchCheckupHistories();
    });
</script>
@endsection