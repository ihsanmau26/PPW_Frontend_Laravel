@extends('layouts.main-layout')

@section('title', 'Shift Detail')

@section('content')
<div class="container mt-5">
    <h1>Shift Detail</h1>

    <div id="shiftDetail">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div id="shiftContent" style="display: none;">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" id="shiftDay"></h5>
                <p class="card-text">
                    <strong>Shift Start:</strong> <span id="shiftStart"></span><br>
                    <strong>Shift End:</strong> <span id="shiftEnd"></span>
                </p>
            </div>
        </div>

        <h4 class="mt-4">Doctors in this Shift</h4>
        <ul class="list-group" id="doctorsList"></ul>

        <div class="d-flex justify-content-start mt-3">
            <button class="btn btn-secondary me-2" onclick="history.back()">Back</button>
        </div>
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

        const shiftId = localStorage.getItem('shiftId');

        if (!shiftId) {
            alert('Shift ID not found.');
            window.location.href = '/shifts';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        $.ajax({
            url: `http://localhost:8000/api/shifts/${shiftId}`,
            method: 'GET',
            success: function (response) {
                const shift = response.data;

                $('#shiftDay').text(`${shift.day} Shift`);
                $('#shiftStart').text(shift.shift_start);
                $('#shiftEnd').text(shift.shift_end);

                let doctorsHtml = '';
                shift.doctors.forEach(function (doctor) {
                    doctorsHtml += `
                        <li class="list-group-item">
                            <strong>${doctor.user.name}</strong> 
                            (${doctor.specialization})<br>
                            <strong>Email:</strong> ${doctor.user.email}
                        </li>
                    `;
                });

                $('#doctorsList').html(doctorsHtml);
                $('#shiftContent').show();
                $('#shiftDetail').hide();
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Failed to load shift details.');
                window.location.href = '/shifts';
            }
        });
    });
</script>
@endsection
