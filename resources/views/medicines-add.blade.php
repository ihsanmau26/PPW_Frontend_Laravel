@extends('layouts.form-layout')

@section('title', 'Add Medicine')

@section('page', 'Add Medicine')

@section('content')
<form id="medicineForm">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Medicine Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Medicine Name" required>
    </div>
    
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select id="type" name="type" class="form-control" required>
            <option value="Tablet">Tablet</option>
            <option value="Syrup">Syrup</option>
            <option value="Capsule">Capsule</option>
            <option value="Injection">Injection</option>
            <option value="Ointment">Ointment</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Medicine Description"></textarea>
    </div>

    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="saveMedicineBtn">Save Medicine</button>
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

        $('#medicineForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: 'http://localhost:8000/api/medicines',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('Medicine added successfully!');
                    window.location.href = '{{ route("medicines.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Failed to add medicine. Please check the input and try again.');
                }
            });
        });
    });
</script>
@endsection
