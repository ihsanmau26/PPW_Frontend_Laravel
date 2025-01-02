@extends('layouts.form-layout')

@section('title', 'Edit Patient')

@section('page', 'Edit Patient')

@section('content')
<form id="patientForm" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Patient's Name" required>
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
        const userId = localStorage.getItem('userId');

        if (!authToken) {
            alert('Anda belum login!');
            window.location.href = '/login';
            return;
        }

        if (!userId) {
            alert('ID pasien tidak ditemukan!');
            window.location.href = '{{ route("patients.index") }}';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`
            }
        });

        $.ajax({
            url: `http://127.0.0.1:8000/api/users/patients/${userId}`,
            method: 'GET',
            success: function (response) {
                if (response && response.data) {
                    const patient = response.data;
                    $('#name').val(patient.name);
                    $('#gender').val(patient.patient_details.gender);
                    $('#date_of_birth').val(patient.patient_details.date_of_birth);
                    $('#phone_number').val(patient.patient_details.phone_number);
                } else {
                    console.error('Response data is invalid.');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Gagal memuat data pasien.');
            }
        });

        $('#patientForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: `http://127.0.0.1:8000/api/users/patients/${userId}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('Patient berhasil diperbarui!');
                    window.location.href = '{{ route("patients.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Gagal memperbarui pasien. Periksa input dan coba lagi.');
                }
            });
        });
    });
</script>
@endsection
