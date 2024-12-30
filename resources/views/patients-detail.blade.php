@extends('layouts.main-layout')

@section('title', 'Patient Detail')

@section('content')
<div class="container mt-5">
    <h1>Patient Detail</h1>

    <div id="patientDetail">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
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

        const patientId = localStorage.getItem('userId');
        if (!patientId) {
            alert('Patient ID not found.');
            window.location.href = '/patients';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        $.ajax({
            url: `http://localhost:8000/api/users/patients/${patientId}`,
            method: 'GET',
            success: function (response) {
                const patient = response.data;

                const detailHtml = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Patient Information</h5>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Name</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${patient.name}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Email</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${patient.email}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Gender</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${patient.patient_details.gender}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Age</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${patient.patient_details.age} years</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Date of Birth</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${patient.patient_details.date_of_birth}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Phone Number</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${patient.patient_details.phone_number}</div>
                            </div>
                            <hr>

                            <div class="d-flex align-items-center mb-2">
                                <h5 class="d-inline">Checkup History</h5>
                                <button id="toggleHistory" class="btn btn-primary ms-auto">View Checkup History</button>
                            </div>
                            <div id="historyContainer" class="mt-3" style="display: none;">
                                ${patient.history.map(function(history) {
                                    return `
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h6>Checkup ID: ${history.checkup_id}</h6>
                                                <p><strong>Diagnosis:</strong> ${history.diagnosis}</p>
                                                <p><strong>Notes:</strong> ${history.notes}</p>
                                                <h6>Doctor Information:</h6>
                                                <p><strong>Name:</strong> ${history.prescription.doctor.name}</p>
                                                <p><strong>Specialization:</strong> ${history.prescription.doctor.specialization}</p>
                                                <hr>
                                                <h6>Prescription Information:</h6>
                                                <p><strong>Prescription Date:</strong> ${history.prescription.prescription_date}</p>
                                                ${history.prescription.prescription_details.map(function(detail) {
                                                    return `
                                                        <div class="card mb-2">
                                                            <div class="card-body">
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
                                                    `;
                                                }).join('')}
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                `;

                $('#patientDetail').html(detailHtml);

                $('#toggleHistory').on('click', function () {
                    const historyContainer = $('#historyContainer');
                    if (historyContainer.is(':visible')) {
                        historyContainer.hide();
                        $(this).text('View Checkup History');
                    } else {
                        historyContainer.show();
                        $(this).text('Close Checkup History');
                    }
                });
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Failed to load patient details.');
                window.location.href = '/patients';
            },
        });
    });
</script>
@endsection
