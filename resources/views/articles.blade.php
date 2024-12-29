@extends('layouts.main-layout')

@section('title', 'Articles')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Articles List</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('articles.add') }}" class="btn btn-primary form-control">Add Article</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="articlesTable">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Comments</th>
                    <th class="col-3">Action</th>
                </tr>
            </thead>        
            <tbody>
            </tbody>
        </table>
    </div>
</div>

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
                Authorization: `Bearer ${authToken}`
            }
        });

        function fetchArticles() {
            $.ajax({
                url: `${apiBaseUrl}/articles`,
                method: 'GET',
                success: function (data) {
                    renderArticlesTable(data.data);
                },
                error: function (error) {
                    console.error('Error fetching articles:', error.responseJSON || error);
                    alert('Gagal memuat artikel. Periksa konsol untuk detail.');
                }
            });
        }

        function renderArticlesTable(articles) {
            const tableBody = $('#articlesTable tbody');
            tableBody.empty();

            articles.forEach(article => {
                const row = `
                    <tr>
                        <td class="text-center">${article.id}</td>
                        <td>${article.title}</td>
                        <td>${article.author.name}</td>
                        <td>${article.created_at}</td>
                        <td class="text-center">${article.comment_total}</td>
                        <td class="text-center">
                            <button class="btn btn-primary detail-btn" data-id="${article.id}">Detail</button>
                            <button class="btn btn-warning edit-btn" data-id="${article.id}">Edit</button>
                            <button class="btn btn-danger delete-btn" data-id="${article.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            attachEventHandlers();
        }

        function attachEventHandlers() {
            $('.detail-btn').on('click', function () {
                const articleId = $(this).data('id');
                localStorage.setItem('articleId', articleId);
                window.location.href = `/articles/${articleId}/detail`;
            });

            $('.edit-btn').on('click', function () {
                const articleId = $(this).data('id');
                localStorage.setItem('articleId', articleId);
                window.location.href = `/articles/${articleId}/edit`;
            });

            $('.delete-btn').on('click', function () {
                const articleId = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
                    deleteArticle(articleId);
                }
            });
        }

        function deleteArticle(articleId) {
            $.ajax({
                url: `${apiBaseUrl}/articles/${articleId}`,
                method: 'DELETE',
                success: function () {
                    alert('Artikel berhasil dihapus!');
                    fetchArticles();
                },
                error: function (error) {
                    console.error('Gagal menghapus artikel:', error.responseJSON || error);
                    alert('Gagal menghapus artikel. Periksa konsol untuk detail.');
                }
            });
        }

        fetchArticles();
    });
</script>

@endsection