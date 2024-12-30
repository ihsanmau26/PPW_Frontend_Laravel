@extends('layouts.form-layout')

@section('title', 'Edit Checkup')

@section('page', 'Edit Checkup')

@section('content')
<form id="checkupForm">
    @csrf
    @method('PATCH')
    <input type="hidden" id="checkupId" name="checkupId">
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

        const checkupId = localStorage.getItem('checkupId');
        if (!checkupId) {
            alert('Checkup ID tidak ditemukan.');
            window.location.href = '/checkups';
            return;
        }

        $.ajaxSetup({
            headers: { 
                applicationAccept: 'application/json',
                Authorization: `Bearer ${authToken}` 
            },
        });

        $.ajax({
            url: `http://localhost:8000/api/checkups/${checkupId}`,
            method: 'GET',
            success: function (response) {
                const checkup = response.data;

                $('#checkupId').val(checkup.id);
                $('#checkup_date').val(checkup.checkup_date);
                $('#checkup_time').val(checkup.checkup_time);
                $('#checkup_type').val(checkup.checkup_type);
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Gagal memuat data checkup.');
                window.location.href = '/checkups';
            },
        });

        $('#checkupForm').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                checkup_date: $('#checkup_date').val(),
                checkup_time: $('#checkup_time').val(),
                checkup_type: $('#checkup_type').val(),
                _method: 'PATCH',
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                url: `http://localhost:8000/api/checkups/${checkupId}`,
                method: 'POST',
                data: formData,
                success: function (response) {
                    alert('Checkup berhasil diperbarui!');
                    window.location.href = '{{ route("checkups.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Gagal memperbarui checkup.');
                },
            });
        });
    });
</script>
@endsection
