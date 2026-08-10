@extends('backEnd.layouts.master')
@section('title', 'Edit Popup')

@section('content')
<style>
    .popup-edit-card {
        background: #fff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        max-width: 640px;
    }
    .popup-edit-card .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
    }
    .image-upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
        padding: 12px;
        text-align: center;
        cursor: pointer;
    }
    .image-upload-box:hover { border-color: #6366f1; background: #eef2ff; }
    .current-img-preview {
        max-width: 100%;
        max-height: 280px;
        border-radius: 8px;
        object-fit: contain;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">পপআপ এডিট</h4>
            <small class="text-muted">টাইটেল, URL ও ছবি আপডেট</small>
        </div>
        <a href="{{ route('admin.popup.index') }}" class="btn btn-light border text-muted fw-bold">
            <i class="fas fa-arrow-left me-1"></i> তালিকা
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="max-width:640px;">
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.popup.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="hidden_id" value="{{ $edit->id }}">

        <div class="card popup-edit-card">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">পপআপ টাইটেল <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{ old('title', $edit->title) }}" required maxlength="255" placeholder="ক্যাম্পেইনের নাম">
                </div>

                <div class="mb-3">
                    <label class="form-label">পপআপ URL <span class="text-muted fw-normal small">(ঐচ্ছিক)</span></label>
                    <input type="text" class="form-control" name="link" value="{{ old('link', $edit->link) }}" placeholder="https://...">
                    <small class="text-muted">খালি রাখলে ফ্রন্টে ছবিতে ক্লিক লিংক হবে না।</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">ইমেইজ</label>
                    <div class="image-upload-box" onclick="document.getElementById('editImageInput').click()">
                        <img id="editImgPreview" src="{{ url('public/'.$edit->image) }}" class="current-img-preview mb-2" alt="Popup">
                        <div>
                            <span class="badge bg-light text-dark border px-3 py-2"><i class="fas fa-camera me-1"></i> ছবি পরিবর্তন</span>
                        </div>
                        <input type="file" name="image" id="editImageInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="previewEditImage(this)">
                    </div>
                    <small class="text-muted d-block mt-2">নতুন ফাইল না দিলে পুরনো ছবি থাকবে।</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="fas fa-save me-2"></i> আপডেট
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewEditImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('editImgPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
