@extends('layouts.form-layout')

@section('title', 'Edit Medicine')

@section('page', 'Edit Medicine')

@section('content')
<form id="medicineForm">
    @csrf
    @method('PATCH')
    <input type="hidden" id="medicineId" name="medicineId">
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
        <button class="btn btn-secondary me-2" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="updateMedicineBtn">Update Medicine</button>
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

        const medicineId = localStorage.getItem('medicineId');
        if (!medicineId) {
            alert('Medicine not found.');
            window.location.href = '{{ route("medicines.index") }}';
            return;
        }

        $.ajaxSetup({
            headers: { Authorization: `Bearer ${authToken}` }
        });

        function loadMedicineData() {
            $.ajax({
                url: `http://localhost:8000/api/medicines/${medicineId}`,
                method: 'GET',
                success: function (response) {
                    const medicine = response.data;

                    $('#medicineId').val(medicine.id);
                    $('#name').val(medicine.name);
                    $('#type').val(medicine.type);
                    $('#description').val(medicine.description);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Failed to load medicine data. Redirecting...');
                    window.location.href = '{{ route("medicines.index") }}';
                }
            });
        }

        $('#medicineForm').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                name: $('#name').val(),
                type: $('#type').val(),
                description: $('#description').val(),
                _method: 'PATCH',
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                url: `http://localhost:8000/api/medicines/${medicineId}`,
                method: 'POST',
                data: formData,
                success: function (response) {
                    alert('Medicine updated successfully!');
                    window.location.href = '{{ route("medicines.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update medicine. Please try again.';
                    alert(errorMessage);
                }
            });
        });

        loadMedicineData();
    });
</script>
@endsection
