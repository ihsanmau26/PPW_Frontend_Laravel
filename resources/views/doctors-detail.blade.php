@extends('layouts.main-layout')

@section('title', 'Doctor Detail')

@section('content')
<div class="container mt-5">
    <h1>Doctor Detail</h1>

    <div id="doctorDetail">
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

        const doctorId = localStorage.getItem('userId');
        if (!doctorId) {
            alert('Doctor ID not found.');
            window.location.href = '/doctors';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        $.ajax({
            url: `http://localhost:8000/api/users/doctors/${doctorId}`,
            method: 'GET',
            success: function (response) {
                const doctor = response.data;

                const detailHtml = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Doctor Information</h5>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Name</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${doctor.name}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Email</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${doctor.email}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <div class="d-flex justify-content-between">
                                        <strong>Specialization</strong><span class="ms-2">:</span>
                                    </div>
                                </div>
                                <div class="col-8">${doctor.specialization}</div>
                            </div>
                            <hr>
                            <h5>Shift Schedule</h5>
                            ${doctor.shifts.map(function(shift) {
                                return `
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Day</strong><span class="ms-2">:</span>
                                                    </div>
                                                </div>
                                                <div class="col-8">${shift.day}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Start</strong><span class="ms-2">:</span>
                                                    </div>
                                                </div>
                                                <div class="col-8">${shift.shift_start}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>End</strong><span class="ms-2">:</span>
                                                    </div>
                                                </div>
                                                <div class="col-8">${shift.shift_end}</div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                            <hr>
                            <h5 class="d-inline">Checkups</h5>
                            <button id="toggleCheckup" class="btn btn-primary float-end">View Checkup</button>
                            <div id="checkupContainer" class="mt-3" style="display: none;">
                                ${doctor.checkups.map(function(checkup) {
                                    return `
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h6>Checkup ID: ${checkup.id}</h6>
                                                <p><strong>Type:</strong> ${checkup.checkup_type}</p>
                                                <p><strong>Date:</strong> ${checkup.checkup_date}</p>
                                                <p><strong>Time:</strong> ${checkup.checkup_time}</p>
                                                <p><strong>Status:</strong> ${checkup.status}</p>
                                                <hr>
                                                <h6>Patient Information:</h6>
                                                <p><strong>Name:</strong> ${checkup.patient.name}</p>
                                                <p><strong>Gender:</strong> ${checkup.patient.gender}</p>
                                                <p><strong>Age:</strong> ${checkup.patient.age}</p>
                                                <p><strong>Phone Number:</strong> ${checkup.patient.phone_number}</p>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                `;

                $('#doctorDetail').html(detailHtml);

                $('#toggleCheckup').on('click', function () {
                    const checkupContainer = $('#checkupContainer');
                    if (checkupContainer.is(':visible')) {
                        checkupContainer.hide();
                        $(this).text('View Checkup');
                    } else {
                        checkupContainer.show();
                        $(this).text('Close Checkup');
                    }
                });
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Failed to load doctor details.');
                window.location.href = '/doctors';
            },
        });
    });
</script>
@endsection
