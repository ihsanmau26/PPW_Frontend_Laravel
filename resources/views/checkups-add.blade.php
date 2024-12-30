@extends('layouts.form-layout')

@section('title', 'Add Checkups')

@section('page', 'Add Checkups')

@section('content')
<form id="checkupForm">
    @csrf
    <div class="mb-3">
        <label for="patient_id" class="form-label">Patient ID</label>
        <input type="number" id="patient_id" name="patient_id" class="form-control" placeholder="Enter Patient ID" required>
    </div>
    <div class="mb-3">
        <label for="checkup_date" class="form-label">Checkup Date</label>
        <input type="date" id="checkup_date" name="checkup_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="checkup_time" class="form-label">Checkup Time</label>
        <input type="time" id="checkup_time" name="checkup_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="checkup_type" class="form-label">Checkup Type</label>
        <select id="checkup_type" name="checkup_type" class="form-select" required>
            <option value="" selected disabled>Select Checkup Type</option>
            <option value="Dental">Dental</option>
            <option value="General">General</option>
        </select>
    </div>
    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="saveCheckupBtn">Save</button>
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
            headers: { Authorization: `Bearer ${authToken}` }
        });

        $('#checkupForm').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                patient_id: $('#patient_id').val(),
                checkup_date: $('#checkup_date').val(),
                checkup_time: $('#checkup_time').val(),
                checkup_type: $('#checkup_type').val(),
            };

            $.ajax({
                url: 'http://localhost:8000/api/checkups',
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function (response) {
                    alert('Checkup berhasil ditambahkan!');
                    window.location.href = '{{ route("checkups.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Gagal menambahkan checkup. Periksa input dan coba lagi.');
                }
            });
        });
    });
</script>
@endsection
