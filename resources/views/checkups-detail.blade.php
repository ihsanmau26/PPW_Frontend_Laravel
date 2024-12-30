@extends('layouts.main-layout')

@section('title', 'Checkup Detail')

@section('content')
<div class="container mt-5">
    <h1>Checkup Detail</h1>

    <div id="checkupDetail">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div id="actionButtons" class="d-flex justify-content-end mt-3" style="display: none;">
        <button class="btn btn-secondary me-2" onclick="history.back()">Back</button>
        <button class="btn btn-warning" id="editCheckupBtn">Edit</button>
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

        const checkupId = localStorage.getItem('checkupId');
        if (!checkupId) {
            alert('Checkup ID not found.');
            window.location.href = '/checkups';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        $.ajax({
            url: `http://localhost:8000/api/checkups/${checkupId}`,
            method: 'GET',
            success: function (response) {
                const checkup = response.data;

                const detailHtml = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Checkup Information</h5>
                            <p><strong>Type:</strong> ${checkup.checkup_type}</p>
                            <p><strong>Date:</strong> ${checkup.checkup_date}</p>
                            <p><strong>Time:</strong> ${checkup.checkup_time}</p>
                            <p><strong>Status:</strong> ${checkup.status}</p>
                            <hr>
                            <h5>Patient Information</h5>
                            <p><strong>Name:</strong> ${checkup.patient.name}</p>
                            <p><strong>Gender:</strong> ${checkup.patient.gender}</p>
                            <p><strong>Age:</strong> ${checkup.patient.age}</p>
                            <p><strong>Date of Birth:</strong> ${checkup.patient.date_of_birth}</p>
                            <p><strong>Phone Number:</strong> ${checkup.patient.phone_number}</p>
                            <hr>
                            <h5>Doctor Information</h5>
                            <p><strong>Name:</strong> ${checkup.doctor.name}</p>
                            <p><strong>Specialization:</strong> ${checkup.doctor.specialization}</p>
                        </div>
                    </div>
                `;

                $('#checkupDetail').html(detailHtml);

                $('#actionButtons').show();
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Failed to load checkup details.');
                window.location.href = '/checkups';
            },
        });

        $('#editCheckupBtn').on('click', function () {
            localStorage.setItem('checkupId', checkupId);
            window.location.href = `/checkups/${checkupId}/edit`;
        });
    });
</script>
@endsection
