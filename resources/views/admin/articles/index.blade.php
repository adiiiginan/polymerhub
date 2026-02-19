@extends('admin.layout.partials.app')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div class="container py-4">
            <div class="card">
                <div class="card-body">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <h1
                                class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                Articles</h1>
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <li class="breadcrumb-item text-muted ">List Articles</li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="{{ route('admin.articles.create') }}"
                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                <i class="ki-duotone ki-plus fs-2"></i> Add Article
                            </a>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Heading</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Gambar</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($articles as $index => $article)
                                    <tr>
                                        <td>{{ $articles->firstItem() + $index }}</td>
                                        <td>{{ $article->heading }}</td>
                                        <td>{{ $article->category->category ?? 'Uncategorized' }}</td>
                                        <td>
                                            @if ($article->publish)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $article->created_at->format('d M Y') }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $article->gambar) }}" alt="Article Image"
                                                style="max-width: 100px;">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.articles.edit', $article->id) }}"
                                                class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('admin.articles.destroy', $article->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this article?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No articles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $articles->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
