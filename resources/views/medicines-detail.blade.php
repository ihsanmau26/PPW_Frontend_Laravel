@extends('layouts.main-layout')

@section('title', 'Medicine Detail')

@section('content')
<div class="container mt-5">
    <h1>Medicine Detail</h1>

    <div id="medicineDetail">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div id="actionButtons" class="d-flex justify-content-end mt-3" style="display: none;">
        <button class="btn btn-secondary me-2" onclick="history.back()">Back</button>
        <button class="btn btn-warning" id="editMedicineBtn">Edit</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
            alert('You are not logged in!');
            window.location.href = '/login';
            return;
        }

        const medicineId = localStorage.getItem('medicineId');
        if (!medicineId) {
            alert('Medicine ID not found.');
            window.location.href = '/medicines';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        $.ajax({
            url: `http://localhost:8000/api/medicines/${medicineId}`,
            method: 'GET',
            success: function (response) {
                const medicine = response.data;

                const detailHtml = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${medicine.name}</h5>
                            <p class="card-text">
                                <strong>Type:</strong> ${medicine.type}<br>
                                <strong>Description:</strong> ${medicine.description || 'No description available'}
                            </p>
                        </div>
                    </div>
                `;

                $('#medicineDetail').html(detailHtml);

                $('#actionButtons').show();
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Failed to load medicine details.');
                window.location.href = '/medicines';
            },
        });

        $('#editMedicineBtn').on('click', function () {
            localStorage.setItem('medicineId', medicineId);
            window.location.href = `/medicines/${medicineId}/edit`;
        });
    });
</script>
@endsection
