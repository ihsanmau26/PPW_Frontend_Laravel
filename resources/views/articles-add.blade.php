@extends('layouts.form-layout')

@section('title', 'Add Articles')

@section('page', 'Add Articles')

@section('content')
<form id="articleForm" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" id="title" name="title" class="form-control" placeholder="Article Title" required>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Image</label>
        <input type="file" id="file" name="file" class="form-control">
    </div>
    <div class="mb-3">
        <label for="articles_content" class="form-label">Content</label>
        <textarea id="articles_content" name="articles_content" class="form-control" rows="4" placeholder="Article Content" required></textarea>
    </div>
    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary me-2" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="saveArticleBtn">Save</button>
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

        $('#articleForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: 'http://localhost:8000/api/articles',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('Artikel berhasil ditambahkan!');
                    window.location.href = '{{ route("articles.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    alert('Gagal menambahkan artikel. Periksa input dan coba lagi.');
                }
            });
        });
    });
</script>
@endsection
