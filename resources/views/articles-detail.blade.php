@extends('layouts.main-layout')

@section('title', 'Articles Detail')

@section('content')
<div class="container mt-5">
    <h1>Article Detail</h1>
    <div id="articleDetail">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="commentText" class="form-control" rows="4" placeholder="Tulis komentar..."></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" id="cancelBtn" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveCommentBtn" class="btn btn-success">Add</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="editCommentText" class="form-control" rows="4" placeholder="Edit komentar..."></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" id="editCancelBtn" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveEditCommentBtn" class="btn btn-success">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteCommentModalLabel">Delete Comment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this comment?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cencel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="mt-3">
    <button class="btn btn-secondary" onclick="history.back()">Back</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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

        $.ajax({
            url: `http://localhost:8000/api/articles/${articleId}`,
            method: 'GET',
            success: function (response) {
                const article = response.data;

                const articleHtml = `
                    <div class="card mb-3">
                        <img src="${article.image_url || '/default-image.jpg'}" class="card-img-top" alt="${article.title}">
                        <div class="card-body">
                            <h5 class="card-title">${article.title}</h5>
                            <p class="card-text">${article.articles_content}</p>
                            <p class="text-muted">Written by: ${article.author?.name || 'Unknown'}</p>
                            <p class="text-muted">Published at: ${article.created_at}</p>
                            <button class="btn btn-warning float-end" id="editArticleBtn">Edit Article</button>
                        </div>
                    </div>
                    <br>
                    <h4>
                        Comments (${article.comment_total})
                        <button class="btn btn-primary float-end" id="toggleCommentsBtn">Show Comments</button>
                        <button class="btn btn-success float-end me-2" id="addCommentBtn">Add Comment</button>
                    </h4>
                    <div id="comments" style="display: none;">
                        ${article.comments.length ? article.comments.map(comment => `
                            <div class="card my-3">
                                <div class="card-body">
                                    <p>${comment.comments_content}</p>
                                    <p class="text-muted">By: ${comment.commentator?.name || 'Anonymous'}</p>
                                    <button class="btn btn-danger float-end deleteCommentBtn" data-id="${comment.id}">Delete</button>
                                    <button class="btn btn-warning float-end me-2 editCommentBtn" data-id="${comment.id}">Edit</button>
                                </div>
                            </div>
                        `).join('') : '<p>No comments yet.</p>'}
                    </div>
                `;

                $('#articleDetail').html(articleHtml);

                $('#toggleCommentsBtn').on('click', function () {
                    const commentsDiv = $('#comments');
                    if (commentsDiv.is(':visible')) {
                        commentsDiv.hide();
                        $(this).text('Show Comments');
                    } else {
                        commentsDiv.show();
                        $(this).text('Close Comments');
                    }
                });

                $('#editArticleBtn').on('click', function () {
                    window.location.href = `/articles/${articleId}/edit`;
                });

                $('#addCommentBtn').on('click', function () {
                    $('#commentModal').modal('show');
                });

                $('#saveCommentBtn').on('click', function () {
                    const commentContent = $('#commentText').val();
                    if (commentContent) {
                        $.ajax({
                            url: `http://localhost:8000/api/comments`,
                            method: 'POST',
                            data: {
                                comments_content: commentContent,
                                article_id: articleId
                            },
                            success: function () {
                                alert('Komentar berhasil ditambahkan.');
                                $('#commentModal').modal('hide');
                                location.reload();
                            },
                            error: function (xhr) {
                                console.error('Error:', xhr.responseJSON || xhr);
                                alert('Gagal menambahkan komentar.');
                            },
                        });
                    } else {
                        alert('Komentar tidak boleh kosong!');
                    }
                });

                $('.editCommentBtn').on('click', function () {
                    const commentId = $(this).data('id');
                    const commentContent = $(this).closest('.card-body').find('p').first().text();
                    $('#editCommentText').val(commentContent);
                    $('#editCommentModal').modal('show');

                    $('#saveEditCommentBtn').on('click', function () {
                        const updatedContent = $('#editCommentText').val();
                        if (updatedContent) {
                            $.ajax({
                                url: `http://localhost:8000/api/comments/${commentId}`,
                                method: 'PATCH',
                                data: { comments_content: updatedContent },
                                success: function () {
                                    alert('Komentar berhasil diperbarui.');
                                    $('#editCommentModal').modal('hide');
                                    location.reload();
                                },
                                error: function (xhr) {
                                    console.error('Error:', xhr.responseJSON || xhr);
                                    alert('Gagal mengedit komentar.');
                                },
                            });
                        }
                    });
                });

                $('.deleteCommentBtn').on('click', function () {
                    const commentId = $(this).data('id');
                    $('#deleteCommentModal').modal('show');

                    $('#confirmDeleteBtn').on('click', function () {
                        $.ajax({
                            url: `http://localhost:8000/api/comments/${commentId}`,
                            method: 'DELETE',
                            success: function () {
                                alert('Komentar berhasil dihapus.');
                                $('#deleteCommentModal').modal('hide');
                                location.reload();
                            },
                            error: function (xhr) {
                                console.error('Error:', xhr.responseJSON || xhr);
                                alert('Gagal menghapus komentar.');
                            },
                        });
                    });
                });
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON || xhr);
                alert('Gagal memuat artikel.');
                window.location.href = '/articles';
            },
        });
    });
</script>
@endsection