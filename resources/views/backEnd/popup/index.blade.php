@extends('backEnd.layouts.master')
@section('title', 'Popup Management')

@section('content')

{{-- Professional CSS --}}
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .pro-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }
    .table-pro thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 1px solid #e9ecef;
        padding: 15px;
    }
    .table-pro tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #495057;
        font-size: 14px;
        border-bottom: 1px solid #f1f1f1;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .status-active { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .status-inactive { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
    
    /* Upload Preview Area */
    .upload-area {
        border: 2px dashed #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }
    .upload-area:hover { border-color: #3b82f6; background: #eff6ff; }
    .upload-icon { font-size: 24px; color: #94a3b8; margin-bottom: 8px; }
    .preview-img { max-height: 100px; border-radius: 6px; display: none; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
</style>

<div class="container-fluid py-4">
    
    {{-- 1. Header Section --}}
    <div class="page-header">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Marketing Popups</h4>
            <small class="text-muted">Manage website promotional modals</small>
        </div>
        <button type="button" class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createPopupModal">
            <i class="fas fa-plus me-2"></i> Create New Popup
        </button>
    </div>

    {{-- 2. Data List Table --}}
    <div class="row">
        <div class="col-12">
            <div class="pro-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-pro mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th width="15%">Preview</th>
                                    <th width="28%">Title</th>
                                    <th width="32%">Popup URL</th>
                                    <th width="10%">Status</th>
                                    <th width="10%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($popups as $key => $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="bg-light rounded p-1 border d-inline-block">
                                            <img src="{{ url('public/'.$value->image) }}" width="60" height="40" style="object-fit: cover; border-radius: 4px;" alt="Popup">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $value->title }}</span>
                                    </td>
                                    <td>
                                        @if($value->link)
                                            <a href="{{ $value->link }}" target="_blank" rel="noopener" class="small text-truncate d-inline-block" style="max-width: 280px;">{{ $value->link }}</a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.popup.status', $value->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn status-badge {{ $value->status == 1 ? 'status-active' : 'status-inactive' }} btn-sm w-100">
                                                {{ $value->status == 1 ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.popup.edit', $value->id) }}" class="btn btn-outline-primary btn-sm px-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.popup.destroy', $value->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this popup?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="far fa-folder-open fa-3x mb-3 opacity-50"></i>
                                        <p class="mb-0">No popups found. Create one to get started!</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal: শুধু টাইটেল, URL, ইমেজ --}}
<div class="modal fade" id="createPopupModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">নতুন পপআপ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.popup.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3">
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">পপআপ টাইটেল <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="যেমন: Eid Campaign" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">পপআপ URL <span class="text-muted fw-normal">(ঐচ্ছিক)</span></label>
                        <input type="text" class="form-control" name="link" value="{{ old('link') }}" placeholder="https://...">
                        <small class="text-muted">খালি রাখলে ছবিতে ক্লিক লিংক থাকবে না।</small>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted">ইমেইজ আপলোড <span class="text-danger">*</span></label>
                        <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                            <input type="file" name="image" id="imageInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="previewImage(this)" required>
                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <p class="mb-1 text-dark fw-bold small">ছবি বাছাই করতে ক্লিক করুন</p>
                                <small class="text-muted d-block" style="font-size: 11px;">JPG, PNG, WebP — সর্বোচ্চ 5MB</small>
                            </div>
                            <img id="imgPreview" class="preview-img" src="#" alt="Preview">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="fas fa-save me-2"></i> সেভ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. JavaScript --}}
<script>
    // Image Preview Function
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadPlaceholder').style.display = 'none';
                var img = document.getElementById('imgPreview');
                img.src = e.target.result;
                img.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Auto Open Modal if Validation Fails
    @if($errors->any())
        var myModal = new bootstrap.Modal(document.getElementById('createPopupModal'));
        myModal.show();
    @endif
</script>

@endsection