@extends('layouts.form-layout')

@section('title', 'Create Prescription')

@section('page', 'Create Prescription')

@section('content')
<form id="prescriptionForm">
    @csrf
    <input type="hidden" id="checkup_id" name="checkup_id">

    <div class="form-group">
        <label for="diagnosis">Diagnosis</label>
        <textarea name="diagnosis" id="diagnosis" class="form-control" required></textarea>
    </div>

    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea name="notes" id="notes" class="form-control"></textarea>
    </div>

    <div class="form-group mt-3">
        <label for="medicine_id">Medicine</label>
        <select name="prescription_details[0][medicine_id]" id="medicine_id" class="form-control" required>
            <option value="">-- Select Medicine --</option>
        </select>
    </div>

    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" name="prescription_details[0][quantity]" id="quantity" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="instructions">Instructions</label>
        <textarea name="prescription_details[0][instructions]" id="instructions" class="form-control" required></textarea>
    </div>

    <button type="button" id="addMedicine" class="btn btn-secondary my-3">Add Another Medicine</button>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('checkups.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-success">Save Prescription</button>
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

    const checkupId = localStorage.getItem('checkupId');
    if (checkupId) {
        $('#checkup_id').val(checkupId);
        localStorage.removeItem('checkupId');
    } else {
        alert('Checkup ID tidak ditemukan.');
    }

    function fetchMedicines() {
        $.ajax({
            url: 'http://127.0.0.1:8000/api/medicines',
            method: 'GET',
            success: function (data) {
                renderMedicinesSelect(data.data);
            },
            error: function (error) {
                console.error('Error fetching medicines:', error.responseJSON || error);
                alert('Gagal memuat medicines. Periksa konsol untuk detail.');
            }
        });
    }

    function renderMedicinesSelect(medicines) {
        const medicineSelect = $('#medicine_id');
        medicineSelect.empty();
        medicineSelect.append('<option value="">-- Select Medicine --</option>');

        medicines.forEach(medicine => {
            medicineSelect.append(`
                <option value="${medicine.id}">${medicine.name}</option>
            `);
        });
    }

    fetchMedicines();

    let medicineCount = 1;

    $('#addMedicine').on('click', function () {
        medicineCount++;

        const medicineField = `
            <div class="form-group medicine-group mt-3">
                <label for="medicine_id_${medicineCount}">Medicine</label>
                <select name="prescription_details[${medicineCount}][medicine_id]" id="medicine_id_${medicineCount}" class="form-control" required>
                    <option value="">-- Select Medicine --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity_${medicineCount}">Quantity</label>
                <input type="number" name="prescription_details[${medicineCount}][quantity]" id="quantity_${medicineCount}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="instructions_${medicineCount}">Instructions</label>
                <textarea name="prescription_details[${medicineCount}][instructions]" id="instructions_${medicineCount}" class="form-control" required></textarea>
            </div>
        `;

        // Insert the new fields before the 'Add Another Medicine' button
        $(medicineField).insertBefore('#addMedicine');
        renderMedicinesSelectInNewField(medicineCount);
    });


    function renderMedicinesSelectInNewField(medicineCount) {
        $.ajax({
            url: 'http://127.0.0.1:8000/api/medicines',
            method: 'GET',
            success: function (data) {
                const newMedicineSelect = $(`#medicine_id_${medicineCount}`);
                newMedicineSelect.empty();
                newMedicineSelect.append('<option value="">-- Select Medicine --</option>');

                data.data.forEach(medicine => {
                    newMedicineSelect.append(`
                        <option value="${medicine.id}">${medicine.name}</option>
                    `);
                });
            },
            error: function (error) {
                console.error('Error fetching medicines:', error.responseJSON || error);
                alert('Gagal memuat medicines. Periksa konsol untuk detail.');
            }
        });
    }

    $('#prescriptionForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: 'http://127.0.0.1:8000/api/prescriptions',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (prescriptionResponse) {
                console.log('Prescription created:', prescriptionResponse);
                alert('Prescription berhasil dibuat!');
                const checkupId = $('#checkup_id').val();
                const prescriptionId = prescriptionResponse.id;
                createCheckupHistory(checkupId, prescriptionId);
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Gagal membuat prescription. Periksa input dan coba lagi.');
            }
        });
    });

    function createCheckupHistory(checkupId, prescriptionId) {
        const historyData = {
            checkup_id: checkupId, 
            prescription_id: prescriptionId,
            diagnosis: $('#diagnosis').val(),
            notes: $('#notes').val()
        };

        $.ajax({
            url: 'http://127.0.0.1:8000/api/checkup-histories',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(historyData),
            success: function (historyResponse) {
                alert('Checkup history berhasil dibuat!');
                window.location.href = '{{ route("histories.index") }}';
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Gagal membuat checkup history. Periksa input dan coba lagi.');
            }
        });
    }
});
</script>

@endsection
