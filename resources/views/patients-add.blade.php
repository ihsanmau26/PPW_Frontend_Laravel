@extends('layouts.form-layout')

@section('title', 'Add Patient')

@section('page', 'Add Patient')

@section('content')
<form id="patientForm" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Patient's Name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Patient's Email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    </div>    
    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select id="gender" name="gender" class="form-control" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Patient's Phone Number" required>
    </div>
    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="savePatientBtn">Save</button>
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
                Accept: 'application/json'
            }
        });

        $('#patientForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: 'http://127.0.0.1:8000/api/users/patients',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('Patient berhasil ditambahkan!');
                    window.location.href = '{{ route("patients.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Gagal menambahkan patient. Periksa input dan coba lagi.');
                }
            });
        });
    });
</script>
@endsection
