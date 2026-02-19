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
                                Tambah Articles</h1>
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <li class="breadcrumb-item text-muted ">Articles</li>
                            </ul>
                        </div>

                    </div>
                    <br>
                    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="heading">Heading</label>
                            <input type="text" name="heading" id="heading" class="form-control" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="judul">Title (Indonesia)</label>
                            <input type="text" name="judul" id="judul" class="form-control" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="content">Content (English)</label>
                            <textarea name="content" id="content" class="form-control" rows="10" required></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="indocontent">Content (Indonesia)</label>
                            <textarea name="indocontent" id="indocontent" class="form-control" rows="10" required></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="idcategory">Category</label>
                            <select name="idcategory" id="idcategory" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="gambar">Image</label>
                            <input type="file" name="gambar" id="gambar" class="form-control">
                        </div>
                        <div class="form-group mb-4">
                            <label>Publish Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="publish" id="publish_yes"
                                    value="1" checked>
                                <label class="form-check-label" for="publish_yes">
                                    Publish Now
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="publish" id="publish_no"
                                    value="0">
                                <label class="form-check-label" for="publish_no">
                                    Save as Draft
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Article</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
