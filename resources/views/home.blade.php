@extends('layouts.main-layout')

@section('title', 'Home')

@section('content')
<div class="container mt-4 position-relative">
    <h1 class="mb-4">Articles List</h1>
    <a href="{{ route('articles.index') }}" class="position-absolute top-0 end-0 text-dark fs-3 mt-2 me-3">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="articlesTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Comments</th>
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

        function fetchArticles() {
            $.ajax({
                url: `${apiBaseUrl}/articles`,
                method: 'GET',
                success: function (data) {
                    renderArticlesTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching articles:', error.responseJSON || error);
                    alert('Gagal memuat artikel. Periksa konsol untuk detail.');
                }
            });
        }

        function renderArticlesTable(articles) {
            const tableBody = $('#articlesTable tbody');
            tableBody.empty();

            const limitedArticles = articles.slice(0, 5);

            limitedArticles.forEach(article => {
                const row = `
                    <tr>
                        <td class="text-center">${article.id}</td>
                        <td>${article.title}</td>
                        <td>${article.author.name}</td>
                        <td>${article.created_at}</td>
                        <td class="text-center">${article.comment_total}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-article-btn" data-id="${article.id}">Detail</button>
                            <button class="btn btn-warning edit-article-btn" data-id="${article.id}">Edit</button>
                            <button class="btn btn-danger delete-article-btn" data-id="${article.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-article-btn').on('click', function () {
                const articleId = $(this).data('id');
                localStorage.setItem('articleId', articleId);
                window.location.href = `/articles/${articleId}/detail`;
            });

            $('.edit-article-btn').on('click', function () {
                const articleId = $(this).data('id');
                localStorage.setItem('articleId', articleId);
                window.location.href = `/articles/${articleId}/edit`;
            });

            $('.delete-article-btn').on('click', function () {
                const articleId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
                    deleteArticle(articleId);
                }
            });
        }

        function deleteArticle(articleId) {
            $.ajax({
                url: `${apiBaseUrl}/articles/${articleId}`,
                method: 'DELETE',
                success: function () {
                    alert('Artikel berhasil dihapus!');
                    fetchArticles();
                },
                error: function (error) {
                    console.error('Gagal menghapus artikel:', error.responseJSON || error);
                    alert('Gagal menghapus artikel. Periksa konsol untuk detail.');
                }
            });
        }

        fetchArticles();
    });
</script>
@endsection

@section('content-2')
<div class="container mt-4 position-relative">
    <h1 class="mb-4">Checkups List</h1>
    <a href="{{ route('checkups.index') }}" class="position-absolute top-0 end-0 text-dark fs-3 mt-2 me-3">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
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

            const limitedCheckups = checkups.slice(0, 5);

            limitedCheckups.forEach(checkup => {
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
                            <button class="btn btn-primary detail-checkup-btn" data-id="${checkup.id}">Detail</button>
                            <button class="btn btn-warning edit-checkup-btn" data-id="${checkup.id}">Edit</button>
                            <button class="btn btn-danger delete-checkup-btn" data-id="${checkup.id}">Delete</button>
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

            $('.detail-checkup-btn').on('click', function () {
                const checkupId = $(this).data('id');
                localStorage.setItem('checkupId', checkupId);
                window.location.href = `/checkups/${checkupId}/detail`;
            });

            $('.edit-checkup-btn').on('click', function () {
                const checkupId = $(this).data('id');
                localStorage.setItem('checkupId', checkupId);
                window.location.href = `/checkups/${checkupId}/edit`;
            });

            $('.delete-checkup-btn').on('click', function () {
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

@section('content-3')
<div class="container mt-4 position-relative">
    <h1 class="mb-4">Checkup Histories List</h1>
    <a href="{{ route('histories.index') }}" class="position-absolute top-0 end-0 text-dark fs-3 mt-2 me-3">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
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

            const limitedHistories = histories.slice(0, 5);

            limitedHistories.forEach(history => {
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
                            <button class="btn btn-primary detail-history-btn" data-id="${history.id}">Detail</button>
                            <button class="btn btn-warning edit-history-btn" data-id="${history.id}">Edit</button>
                            <button class="btn btn-danger delete-history-btn" data-id="${history.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-history-btn').on('click', function () {
                const historyId = $(this).data('id');
                localStorage.setItem('historyId', historyId);
                window.location.href = `/histories/${historyId}/detail`;
            });

            $('.edit-history-btn').on('click', function () {
                const historyId = $(this).data('id');
                localStorage.setItem('historyId', historyId);
                window.location.href = `/histories/${historyId}/edit`;
            });

            $('.delete-history-btn').on('click', function () {
                const historyId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus riwayat ini?')) {
                    deleteCheckupHistory(historyId);
                }
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

        fetchCheckupHistories();
    });
</script>
@endsection

@section('content-4')
<div class="container mt-4 position-relative">
    <h1 class="mb-4">Medicines List</h1>
    <a href="{{ route('medicines.index') }}" class="position-absolute top-0 end-0 text-dark fs-3 mt-2 me-3">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
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

            const limitedMedicines = medicines.slice(0, 5);

            limitedMedicines.forEach(medicine => {
                const row = `
                    <tr>
                        <td class="text-center">${medicine.id}</td>
                        <td>${medicine.name}</td>
                        <td>${medicine.type}</td>
                        <td>${medicine.description}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-medicine-btn" data-id="${medicine.id}">Detail</button>
                            <button class="btn btn-warning edit-medicine-btn" data-id="${medicine.id}">Edit</button>
                            <button class="btn btn-danger delete-medicine-btn" data-id="${medicine.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-medicine-btn').on('click', function () {
                const medicineId = $(this).data('id');
                localStorage.setItem('medicineId', medicineId);
                window.location.href = `/medicines/${medicineId}/detail`;
            });

            $('.edit-medicine-btn').on('click', function () {
                const medicineId = $(this).data('id');
                localStorage.setItem('medicineId', medicineId);
                window.location.href = `/medicines/${medicineId}/edit`;
            });

            $('.delete-medicine-btn').on('click', function () {
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

@section('content-5')
<div class="container mt-4 position-relative">
    <h1 class="mb-4">Patients List</h1>
    <a href="{{ route('patients.index') }}" class="position-absolute top-0 end-0 text-dark fs-3 mt-2 me-3">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
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

            const limitedPatients = patients.slice(0, 5);

            limitedPatients.forEach(patient => {
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
                            <button class="btn btn-primary detail-patient-btn" data-id="${patient.id}">Detail</button>
                            <button class="btn btn-warning edit-patient-btn" data-id="${patient.id}">Edit</button>
                            <button class="btn btn-danger delete-patient-btn" data-id="${patient.patient_id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-patient-btn').on('click', function () {
                const userId = $(this).data('id');
                localStorage.setItem('userId', userId);
                window.location.href = `/patients/${userId}/detail`;
            });

            $('.edit-patient-btn').on('click', function () {
                const userId = $(this).data('id');
                localStorage.setItem('userId', userId);
                window.location.href = `/patients/${userId}/edit`;
            });

            $('.delete-patient-btn').on('click', function () {
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

@section('content-6')
<div class="container mt-4 position-relative">
    <h1 class="mb-4">Doctors List</h1>
    <a href="{{ route('doctors.index') }}" class="position-absolute top-0 end-0 text-dark fs-3 mt-2 me-3">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
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

            const limitedDoctors = doctors.slice(0, 5);

            limitedDoctors.forEach(doctor => {
                const row = `
                    <tr>
                        <td class="text-center">${doctor.id}</td>
                        <td class="text-center">${doctor.doctor_id ?? '-'}</td>
                        <td>${doctor.name}</td>
                        <td>${doctor.email}</td>
                        <td>${doctor.specialization ?? '-'}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-doctor-btn" data-id="${doctor.id}">Detail</button>
                            <button class="btn btn-warning edit-doctor-btn" data-id="${doctor.id}">Edit</button>
                            <button class="btn btn-danger delete-doctor-btn" data-id="${doctor.doctor_id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-doctor-btn').on('click', function () {
                const userId = $(this).data('id');
                localStorage.setItem('userId', userId);
                window.location.href = `/doctors/${userId}`;
            });

            $('.edit-doctor-btn').on('click', function () {
                const userId = $(this).data('id');
                localStorage.setItem('userId', userId);
                window.location.href = `/doctors/${userId}/edit`;
            });

            $('.delete-doctor-btn').on('click', function () {
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