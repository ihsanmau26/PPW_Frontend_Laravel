@extends('layouts.form-layout')

@section('title', 'Edit Checkup History')

@section('page', 'Edit Checkup History')

@section('content')
<form id="checkupHistoryForm">
    @csrf
    @method('PATCH')
    <input type="hidden" id="historyId" name="historyId">
    <div class="mb-3">
        <label for="diagnosis" class="form-label">Diagnosis</label>
        <input type="text" id="diagnosis" name="diagnosis" class="form-control" placeholder="Enter Diagnosis" required>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="Enter Notes (Optional)"></textarea>
    </div>
    <div class="d-flex justify-content-between">
        <a href="{{ route('histories.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-success" id="saveCheckupHistoryBtn">Update</button>
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

        const historyId = localStorage.getItem('historyId');
        if (!historyId) {
            alert('Data Checkup History tidak ditemukan.');
            window.location.href = '{{ route("histories.index") }}';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        function loadCheckupHistoryData() {
            $.ajax({
                url: `http://localhost:8000/api/checkup-histories/${historyId}`,
                method: 'GET',
                success: function (response) {
                    const history = response.data;

                    $('#historyId').val(history.id);
                    $('#diagnosis').val(history.diagnosis);
                    $('#notes').val(history.notes);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);

                    const errorMessage = xhr.responseJSON?.message || 'Gagal memuat data checkup history. Periksa konsol untuk detail.';
                    alert(errorMessage);

                    if (xhr.status === 404) {
                        window.location.href = '{{ route("histories.index") }}';
                    }
                }
            });
        }

        $('#checkupHistoryForm').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                diagnosis: $('#diagnosis').val(),
                notes: $('#notes').val(),
            };

            $.ajax({
                url: `http://localhost:8000/api/checkup-histories/${historyId}`,
                method: 'PATCH',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function (response) {
                    alert('Checkup history berhasil diperbarui!');
                    window.location.href = '{{ route("histories.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        for (let field in errors) {
                            alert(`${field}: ${errors[field].join(', ')}`);
                        }
                    } else {
                        alert('Gagal memperbarui checkup history. Periksa input dan coba lagi.');
                    }
                }
            });
        });

        loadCheckupHistoryData();
    });
</script>
@endsection
