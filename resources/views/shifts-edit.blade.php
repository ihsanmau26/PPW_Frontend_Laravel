@extends('layouts.form-layout')

@section('title', 'Edit Shift')

@section('page', 'Edit Shift')

@section('content')
<form id="shiftForm">
    @csrf
    @method('PATCH')
    <input type="hidden" id="shiftId" name="shiftId">
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
        <button class="btn btn-secondary me-2" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="updateShiftBtn">Update Shift</button>
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

        const shiftId = localStorage.getItem('shiftId');
        if (!shiftId) {
            alert('Shift not found.');
            window.location.href = '{{ route("shifts.index") }}';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`
            }
        });

        function loadShiftData() {
            $.ajax({
                url: `http://localhost:8000/api/shifts/${shiftId}`,
                method: 'GET',
                success: function (response) {
                    const shift = response.data;

                    $('#shiftId').val(shift.id);
                    $('#day').val(shift.day);
                    $('#shift_start').val(shift.shift_start);
                    $('#shift_end').val(shift.shift_end);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Failed to load shift data. Redirecting...');
                    window.location.href = '{{ route("shifts.index") }}';
                }
            });
        }

        $('#shiftForm').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                day: $('#day').val(),
                shift_start: $('#shift_start').val(),
                shift_end: $('#shift_end').val(),
                _method: 'PATCH',
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                url: `http://localhost:8000/api/shifts/${shiftId}`,
                method: 'POST',
                data: formData,
                success: function (response) {
                    alert('Shift updated successfully!');
                    window.location.href = '{{ route("shifts.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update shift. Please try again.';
                    alert(errorMessage);
                }
            });
        });

        loadShiftData();
    });
</script>
@endsection
