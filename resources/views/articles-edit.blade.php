@extends('layouts.form-layout')

@section('title', 'Edit Article')

@section('page', 'Edit Article')

@section('content')
<form id="articleForm" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <input type="hidden" id="articleId" name="articleId">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" id="title" name="title" class="form-control" placeholder="Article Title" required>
    </div>
    <div class="mb-3">
        <label for="currentImage" class="form-label">Current Image</label>
        <div>
            <img id="currentImage" src="" alt="Current Article Image" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
        </div>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Change Image</label>
        <input type="file" id="file" name="file" class="form-control">
    </div>
    <div class="mb-3">
        <label for="articles_content" class="form-label">Content</label>
        <textarea id="articles_content" name="articles_content" class="form-control" rows="4" placeholder="Article Content" required></textarea>
    </div>
    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" onclick="history.back()">Back</button>
        <button type="submit" class="btn btn-success" id="saveArticleBtn">Update</button>
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

        const articleId = localStorage.getItem('articleId');
        if (!articleId) {
            alert('Artikel tidak ditemukan.');
            window.location.href = '{{ route("articles.index") }}';
            return;
        }

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${authToken}`,
            },
        });

        function loadArticleData() {
            if (!articleId) {
                alert('Article ID tidak valid.');
                window.location.href = '{{ route("articles.index") }}';
                return;
            }

            $.ajax({
                url: `http://localhost:8000/api/articles/${articleId}`,
                method: 'GET',
                success: function (response) {
                    const article = response.data;

                    $('#articleId').val(article.id);
                    $('#title').val(article.title);
                    $('#articles_content').val(article.articles_content);

                    if (article.image_url) {
                        $('#currentImage')
                            .attr('src', article.image_url)
                            .attr('alt', article.title || 'Article Image')
                            .show();
                    } else {
                        $('#currentImage')
                            .attr('src', 'path/to/placeholder-image.jpg') 
                            .attr('alt', 'No Image Available')
                            .show();
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);

                    const errorMessage = xhr.responseJSON?.message || 'Gagal memuat data artikel. Periksa konsol untuk detail.';
                    alert(errorMessage);

                    if (xhr.status === 404) {
                        window.location.href = '{{ route("articles.index") }}';
                    }
                }
            });
        }

        $('#articleForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: `http://localhost:8000/api/articles/${articleId}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('Artikel berhasil diperbarui!');
                    window.location.href = '{{ route("articles.index") }}';
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseJSON || xhr);
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        for (let field in errors) {
                            alert(`${field}: ${errors[field].join(', ')}`);
                        }
                    } else {
                        alert('Gagal memperbarui artikel. Periksa input dan coba lagi.');
                    }
                }
            });
        });

        loadArticleData();
    });
</script>
@endsection
