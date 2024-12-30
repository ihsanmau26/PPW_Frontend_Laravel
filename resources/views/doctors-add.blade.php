@extends('layouts.form-layout')

@section('title', 'Add Doctor')

@section('page', 'Add Doctor')

@section('content')
<form id="doctorForm" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Doctor's Name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Doctor's Email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <div class="mb-3">
        <label for="specialization" class="form-label">Specialization</label>
        <select id="specialization" name="specialization" class="form-control" required>
            <option value="">Select Specialization</option>
            <option value="Dental">Dental</option>
            <option value="General">General</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="shifts" class="form-label">Shifts</label>
        <div id="shiftInputs"></div>
    </div>

    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="saveDoctorBtn">Save</button>
    </div>
</form>

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
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        $.get('http://localhost:8000/api/shifts', function (response) {
            const shifts = response.data;
            const shiftInputs = $('#shiftInputs');

            const groupedShifts = {};

            shifts.forEach((shift) => {
                if (!groupedShifts[shift.day]) {
                    groupedShifts[shift.day] = [];
                }
                groupedShifts[shift.day].push(shift);
            });

            for (const day in groupedShifts) {
                shiftInputs.append(`
                    <div class="mb-1">
                        <strong>${day}</strong>
                    </div>
                `);

                groupedShifts[day].forEach((shift) => {
                    shiftInputs.append(`
                        <div class="form-check mb-1">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="shifts[]" 
                                value="${shift.id}" 
                                id="shift-${shift.id}">
                            <label class="form-check-label" for="shift-${shift.id}">
                                ${shift.shift_start} - ${shift.shift_end}
                            </label>
                        </div>
                    `);
                });
            }
        });

        $('#doctorForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: 'http://localhost:8000/api/users/doctors',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    alert('Dokter berhasil ditambahkan!');
                    window.location.href = '/doctors';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Gagal menambahkan dokter. Periksa input dan coba lagi.');
                },
            });
        });
    });
</script>
@endsection
