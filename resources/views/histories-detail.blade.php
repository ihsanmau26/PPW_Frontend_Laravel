@extends('layouts.main-layout')

@section('title', 'Checkup History Detail')

@section('content')
<div class="container mt-5">
    <h1>Checkup History Detail</h1>

    <div id="checkupHistoryDetail">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
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

        const historyId = localStorage.getItem('historyId');
        if (!historyId) {
            alert('History ID not found.');
            window.location.href = '/checkup-histories';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        $.ajax({
            url: `http://localhost:8000/api/checkup-histories/${historyId}`,
            method: 'GET',
            success: function (response) {
                const history = response.data;

                const detailHtml = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Checkup History Information</h5>
                            <p><strong>Diagnosis:</strong> ${history.diagnosis}</p>
                            <p><strong>Notes:</strong> ${history.notes}</p>
                            <hr>
                            <h5>Checkup Information</h5>
                            <p><strong>Type:</strong> ${history.checkup.checkup_type}</p>
                            <p><strong>Date:</strong> ${history.checkup.checkup_date}</p>
                            <p><strong>Time:</strong> ${history.checkup.checkup_time}</p>
                            <p><strong>Status:</strong> ${history.checkup.status}</p>
                            <hr>
                            <h5>Patient Information</h5>
                            <p><strong>Name:</strong> ${history.checkup.patient.name}</p>
                            <p><strong>Gender:</strong> ${history.checkup.patient.gender}</p>
                            <p><strong>Age:</strong> ${history.checkup.patient.age}</p>
                            <p><strong>Date of Birth:</strong> ${history.checkup.patient.date_of_birth}</p>
                            <p><strong>Phone Number:</strong> ${history.checkup.patient.phone_number}</p>
                            <hr>
                            <h5>Doctor Information</h5>
                            <p><strong>Name:</strong> ${history.checkup.doctor.name}</p>
                            <p><strong>Specialization:</strong> ${history.checkup.doctor.specialization}</p>
                            <hr>
                            <h5>Prescription Information</h5>
                            <p><strong>Prescription Date:</strong> ${history.prescription.prescription_date}</p>
                            ${history.prescription.prescription_details
                                .map(
                                    (detail) => `
                                    <div class="card mb-3">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <strong>Medicine:</strong> ${detail.medicine_name} (${detail.medicine_type})<br>
                                                        <strong>Description:</strong> ${detail.medicine_description || '-'}<br>
                                                        <strong>Quantity:</strong> ${detail.quantity}<br>
                                                        <strong>Instructions:</strong> ${detail.instructions || '-'}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                `
                                )
                                .join('')}
                        </div>
                    </div>
                `;

                $('#checkupHistoryDetail').html(detailHtml);
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Failed to load checkup history details.');
                window.location.href = '/checkup-histories';
            },
        });
    });
</script>
@endsection
