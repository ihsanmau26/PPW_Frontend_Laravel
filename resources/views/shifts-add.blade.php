@extends('layouts.form-layout')

@section('title', 'Add Shift')

@section('page', 'Add Shift')

@section('content')
<form id="shiftForm">
    @csrf
    <div class="mb-3">
        <label for="day" class="form-label">Day</label>
        <select id="day" name="day" class="form-control" required>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="shift_start" class="form-label">Shift Start</label>
        <input type="time" id="shift_start" name="shift_start" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="shift_end" class="form-label">Shift End</label>
        <input type="time" id="shift_end" name="shift_end" class="form-control" required>
    </div>

    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="saveShiftBtn">Save Shift</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
            alert('You need to log in first!');
            window.location.href = '/login';
            return;
        }

        $.ajaxSetup({
            headers: { Authorization: `Bearer ${authToken}` }
        });

        $('#shiftForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: 'http://localhost:8000/api/shifts',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('Shift added successfully!');
                    window.location.href = '{{ route("shifts.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Failed to add shift. Please check the input and try again.');
                }
            });
        });
    });
</script>
@endsection
