@extends('layouts.dashboard')

@section('title', 'Feedback Management')

@section('content')
@php
    $__u = auth()->user();

    if (!$__u || (!$__u->isAdmin() && in_array('admin.testimonials', $__u->hidden_pages ?? []))) {
        abort(403);
    }
@endphp

<div class="news-admin-page">
    <section class="news-admin-banner">
        <div>
            <span class="news-admin-kicker">Admin Publishing</span>
            <h1>Feedback Management</h1>
            <p>Create, preview, edit, and publish the client feedback shown on the public landing page.</p>
        </div>

        <a
            href="{{ route('landing') }}#testimonials"
            target="_blank"
            rel="noopener noreferrer"
            class="news-admin-public-link"
        >
            View Public Page
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7v7m0-7L10 14M5 7v12h12v-5"/>
            </svg>
        </a>
    </section>

    @if(session('testimonial_success'))
        <div class="news-admin-alert success">{{ session('testimonial_success') }}</div>
    @endif

    @if(session('testimonial_error'))
        <div class="news-admin-alert error">{{ session('testimonial_error') }}</div>
    @endif

    @if($errors->any())
        <div class="news-admin-alert error">
            <strong>Please correct the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="news-admin-panel">
        <div class="news-admin-panel-head">
            <div>
                <span class="news-admin-section-number">01</span>
                <h2>Add New Feedback</h2>
            </div>

            <span class="news-admin-hint">Only “Published” feedback appears on the landing page (max 4 shown)</span>
        </div>

        <form
            method="POST"
            action="{{ route('admin.testimonials.store') }}"
            enctype="multipart/form-data"
            class="news-post-form"
            onsubmit="return confirm('Save this feedback?');"
        >
            @csrf

            <div class="news-admin-field">
                <label for="testimonial-name">Client Name</label>
                <input
                    id="testimonial-name"
                    type="text"
                    name="client_name"
                    value="{{ old('client_name') }}"
                    maxlength="120"
                    required
                    placeholder="e.g. Maria Santos"
                >
            </div>

            <div class="news-admin-field">
                <label for="testimonial-role">Client Role / Title</label>
                <input
                    id="testimonial-role"
                    type="text"
                    name="client_role"
                    value="{{ old('client_role') }}"
                    maxlength="120"
                    placeholder="e.g. First-Time Property Buyer"
                >
            </div>

            <div class="news-admin-field full">
                <label for="testimonial-quote">Feedback / Quote</label>
                <textarea
                    id="testimonial-quote"
                    name="quote"
                    rows="5"
                    maxlength="2000"
                    required
                    placeholder="Write the client's feedback..."
                >{{ old('quote') }}</textarea>
            </div>

            <div class="news-admin-field">
                <label for="testimonial-status">Posting Status</label>
                <select id="testimonial-status" name="status" required>
                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Save as Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publish</option>
                </select>
            </div>

            <div class="news-admin-field">
                <label for="testimonial-order">Display Order</label>
                <input
                    id="testimonial-order"
                    type="number"
                    name="sort_order"
                    value="{{ old('sort_order', 0) }}"
                    min="0"
                    max="9999"
                >
                <small>Lower numbers appear first.</small>
            </div>

            <div class="news-admin-field full">
                <label>Client Photo</label>

                <div data-single-media-picker>
                    <label class="news-upload-box" for="testimonial-avatar">
                        <img class="news-upload-preview" data-preview hidden alt="Selected client photo preview">

                        <svg data-upload-icon fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5.5 5.5 0 0116.9 6.1 4.5 4.5 0 0118 15H7zm5-7v8m0-8-3 3m3-3 3 3"/>
                        </svg>

                        <strong>Select a photo</strong>
                        <span>JPG, PNG, GIF, or WEBP — max 25 MB</span>
                        <span class="news-selected-files" data-selected-files>No file selected</span>
                    </label>

                    <input
                        id="testimonial-avatar"
                        type="file"
                        name="avatar"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        hidden
                        data-media-input
                    >

                    <div class="single-preview-actions">
                        <button type="button" class="single-preview-button" data-view-selected>
                            View selected photo
                        </button>
                        <small>Only one photo can be uploaded. It is not saved until you click “Save Feedback.”</small>
                    </div>
                </div>

                <small>If no photo is uploaded, the client's initials will be shown instead.</small>
            </div>

            <div class="news-admin-actions full">
                <button type="reset" class="news-admin-btn secondary">Clear Form</button>
                <button type="submit" class="news-admin-btn primary">Save Feedback</button>
            </div>
        </form>
    </section>

    <section class="news-admin-panel">
        <div class="news-admin-panel-head">
            <div>
                <span class="news-admin-section-number">02</span>
                <h2>All Feedback</h2>
            </div>

            <span class="news-admin-hint">
                {{ $testimonials->total() }} total feedback {{ $testimonials->total() === 1 ? 'entry' : 'entries' }}
            </span>
        </div>

        @forelse($testimonials as $testimonial)
            <article class="news-admin-post-card">
                <div class="news-admin-post-summary">
                    <div class="news-admin-post-main">
                        <div class="news-admin-post-status-row">
                            <span class="news-status-badge {{ $testimonial->status === 'published' ? 'published' : 'draft' }}">
                                {{ $testimonial->status === 'published' ? 'Published' : 'Draft' }}
                            </span>
                            <span class="news-admin-post-date">Order: {{ $testimonial->sort_order }}</span>
                        </div>

                        <h3>{{ $testimonial->client_name }}</h3>

                        @if($testimonial->client_role)
                            <p style="margin-bottom:4px;color:#4a5f78;font-weight:700;">
                                {{ $testimonial->client_role }}
                            </p>
                        @endif

                        <p>{{ \Illuminate\Support\Str::limit($testimonial->quote, 220) }}</p>

                        <div class="news-admin-post-meta">
                            <span>Created {{ $testimonial->created_at->diffForHumans() }}</span>

                            @if($testimonial->creator)
                                <span>By {{ $testimonial->creator->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="news-admin-post-controls">
                        @if($testimonial->has_avatar)
                            <button
                                type="button"
                                class="record-thumbnail round"
                                data-existing-image-url="{{ $testimonial->avatar_url }}"
                                data-existing-image-name="{{ $testimonial->client_name }}"
                                title="View current client photo"
                                aria-label="View current client photo"
                            >
                                <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->client_name }}">
                            </button>
                        @endif

                        <button
                            type="button"
                            class="news-admin-btn secondary small"
                            onclick="toggleNewsEdit({{ $testimonial->id }})"
                        >
                            Edit
                        </button>

                        @if($__u->isAdmin())
                            <form
                                method="POST"
                                action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                onsubmit="return confirm('Delete this feedback permanently?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="news-admin-btn danger small">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="news-admin-edit-panel" id="newsEditPanel{{ $testimonial->id }}">
                    <form
                        method="POST"
                        action="{{ route('admin.testimonials.update', $testimonial) }}"
                        enctype="multipart/form-data"
                        class="news-post-form"
                    >
                        @csrf
                        @method('PUT')

                        <div class="news-admin-field">
                            <label for="edit-name-{{ $testimonial->id }}">Client Name</label>
                            <input
                                id="edit-name-{{ $testimonial->id }}"
                                type="text"
                                name="client_name"
                                value="{{ $testimonial->client_name }}"
                                maxlength="120"
                                required
                            >
                        </div>

                        <div class="news-admin-field">
                            <label for="edit-role-{{ $testimonial->id }}">Client Role / Title</label>
                            <input
                                id="edit-role-{{ $testimonial->id }}"
                                type="text"
                                name="client_role"
                                value="{{ $testimonial->client_role }}"
                                maxlength="120"
                            >
                        </div>

                        <div class="news-admin-field full">
                            <label for="edit-quote-{{ $testimonial->id }}">Feedback / Quote</label>
                            <textarea
                                id="edit-quote-{{ $testimonial->id }}"
                                name="quote"
                                rows="5"
                                maxlength="2000"
                                required
                            >{{ $testimonial->quote }}</textarea>
                        </div>

                        <div class="news-admin-field">
                            <label for="edit-status-{{ $testimonial->id }}">Posting Status</label>
                            <select id="edit-status-{{ $testimonial->id }}" name="status" required>
                                <option value="draft" {{ $testimonial->status === 'draft' ? 'selected' : '' }}>Save as Draft</option>
                                <option value="published" {{ $testimonial->status === 'published' ? 'selected' : '' }}>Publish</option>
                            </select>
                        </div>

                        <div class="news-admin-field">
                            <label for="edit-order-{{ $testimonial->id }}">Display Order</label>
                            <input
                                id="edit-order-{{ $testimonial->id }}"
                                type="number"
                                name="sort_order"
                                value="{{ $testimonial->sort_order }}"
                                min="0"
                                max="9999"
                            >
                        </div>

                        <div class="news-admin-field full">
                            <label>Replace Client Photo</label>

                            <div data-single-media-picker>
                                <label class="news-upload-box" for="edit-avatar-{{ $testimonial->id }}">
                                    <img class="news-upload-preview" data-preview hidden alt="Selected replacement photo preview">

                                    <svg data-upload-icon fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5.5 5.5 0 0116.9 6.1 4.5 4.5 0 0118 15H7zm5-7v8m0-8-3 3m3-3 3 3"/>
                                    </svg>

                                    <strong>Select a replacement photo</strong>
                                    <span>JPG, PNG, GIF, or WEBP — max 25 MB</span>
                                    <span class="news-selected-files" data-selected-files>No file selected</span>
                                </label>

                                <input
                                    id="edit-avatar-{{ $testimonial->id }}"
                                    type="file"
                                    name="avatar"
                                    accept="image/jpeg,image/png,image/gif,image/webp"
                                    hidden
                                    data-media-input
                                >

                                <div class="single-preview-actions">
                                    <button type="button" class="single-preview-button" data-view-selected>
                                        View selected photo
                                    </button>

                                    <small>
                                        @if($testimonial->has_avatar)
                                            Uploading a new photo replaces the current one.
                                        @else
                                            Only one photo can be uploaded.
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="news-admin-actions full">
                            @if($testimonial->has_avatar)
                                <button
                                    type="submit"
                                    form="testimonial-avatar-destroy-{{ $testimonial->id }}"
                                    class="news-admin-btn secondary"
                                    onclick="return confirm('Remove this client photo?');"
                                >
                                    Remove Photo
                                </button>
                            @endif

                            <button
                                type="button"
                                class="news-admin-btn secondary"
                                onclick="toggleNewsEdit({{ $testimonial->id }})"
                            >
                                Cancel
                            </button>

                            <button type="submit" class="news-admin-btn primary">Save Changes</button>
                        </div>
                    </form>

                    @if($testimonial->has_avatar)
                        <form
                            id="testimonial-avatar-destroy-{{ $testimonial->id }}"
                            method="POST"
                            action="{{ route('admin.testimonials.avatar.destroy', $testimonial) }}"
                            style="display:none;"
                        >
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="news-admin-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.284 0-2.503-.24-3.605-.671L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3>No feedback yet</h3>
                <p>Add your first client feedback using the form above.</p>
            </div>
        @endforelse

        @if($testimonials->hasPages())
            <div class="news-admin-pagination">{{ $testimonials->links() }}</div>
        @endif
    </section>
</div>

<div class="media-preview-modal" data-media-modal hidden>
    <div class="media-preview-modal-backdrop" data-media-modal-close></div>
    <div class="media-preview-modal-dialog" role="dialog" aria-modal="true" aria-label="Photo preview">
        <button type="button" class="media-preview-modal-close" data-media-modal-close aria-label="Close preview">×</button>
        <div class="media-preview-modal-content" data-media-modal-content></div>
        <p class="media-preview-modal-name" data-media-modal-name></p>
    </div>
</div>

<style>
.news-admin-page{display:flex;flex-direction:column;gap:22px}
.news-admin-banner{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:32px 36px;border-radius:20px;background:linear-gradient(135deg,#163a63 0%,#2563eb 62%,#173f70 100%);box-shadow:0 12px 34px rgba(30,69,117,.24);color:#fff}
.news-admin-kicker{display:block;margin-bottom:7px;color:#f7df9a;font-size:10px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase}.news-admin-banner h1{margin:0;color:#fff;font-size:29px}.news-admin-banner p{max-width:760px;margin:7px 0 0;color:rgba(255,255,255,.76);font-size:13px}
.news-admin-public-link{display:inline-flex;align-items:center;gap:8px;flex-shrink:0;padding:11px 15px;border:1px solid rgba(255,255,255,.35);border-radius:9px;color:#fff;font-size:12px;font-weight:700;text-decoration:none;transition:.2s}.news-admin-public-link:hover{background:#fff;color:#163a63}.news-admin-public-link svg{width:17px;height:17px}
.news-admin-alert{padding:13px 16px;border-radius:9px;font-size:13px}.news-admin-alert.success{border:1px solid #86efac;background:#dcfce7;color:#166534}.news-admin-alert.error{border:1px solid #fecaca;background:#fef2f2;color:#b91c1c}.news-admin-alert ul{margin:8px 0 0 20px}
.news-admin-panel{padding:26px 28px;border:1px solid #dbe4ef;border-radius:16px;background:#fff;box-shadow:0 8px 26px rgba(15,42,71,.06)}.news-admin-panel-head{display:flex;align-items:center;justify-content:space-between;gap:18px;padding-bottom:18px;border-bottom:1px solid #e5edf5}.news-admin-panel-head>div{display:flex;align-items:center;gap:12px}.news-admin-panel-head h2{margin:0;color:#12345a;font-size:20px}.news-admin-section-number{color:#c38a17;font-family:monospace;font-size:11px}.news-admin-hint{color:#70839a;font-size:11px;text-align:right}
.news-post-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-top:22px}.news-admin-field{display:flex;flex-direction:column;gap:7px}.news-admin-field.full,.news-admin-actions.full{grid-column:1/-1}.news-admin-field>label{color:#173b63;font-size:11px;font-weight:800;letter-spacing:.45px;text-transform:uppercase}.news-admin-field input,.news-admin-field select,.news-admin-field textarea{width:100%;padding:12px 14px;border:1px solid #ccd9e7;border-radius:9px;background:#fff;color:#18344f;font:inherit;font-size:13px;outline:none;transition:.2s}.news-admin-field textarea{resize:vertical}.news-admin-field input:focus,.news-admin-field select:focus,.news-admin-field textarea:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}.news-admin-field small{color:#7b8ca0;font-size:10px}
.news-upload-box{display:flex;min-height:165px;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:20px;border:2px dashed #9ab7d8;border-radius:13px;background:#f6faff;text-align:center;cursor:pointer;transition:.2s}.news-upload-box:hover{border-color:#2563eb;background:#eff6ff}.news-upload-box svg{width:34px;height:34px;color:#2563eb}.news-upload-box strong{color:#12345a;font-size:13px}.news-upload-box span{color:#6b7f96;font-size:10px}.news-selected-files{max-width:100%;color:#b2790a!important;font-weight:700;overflow-wrap:anywhere}.news-upload-preview{display:block;width:110px;height:88px;border-radius:10px;object-fit:cover;box-shadow:0 4px 14px rgba(15,42,71,.16)}
.single-preview-actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:10px}.single-preview-button{display:none;align-items:center;gap:7px;padding:9px 13px;border:1px solid #2563eb;border-radius:8px;background:#fff;color:#1d4ed8;font-size:11px;font-weight:800;cursor:pointer}.single-preview-button.visible{display:inline-flex}.single-preview-actions small{text-align:right}
.news-admin-actions{display:flex;justify-content:flex-end;gap:10px;padding-top:4px}.news-admin-btn{display:inline-flex;align-items:center;justify-content:center;padding:11px 16px;border:1px solid transparent;border-radius:8px;font-size:12px;font-weight:800;cursor:pointer;transition:.2s}.news-admin-btn.primary{background:#2563eb;color:#fff}.news-admin-btn.primary:hover{background:#1d4ed8}.news-admin-btn.secondary{border-color:#cbd7e5;background:#fff;color:#173b63}.news-admin-btn.secondary:hover{background:#f7fafc}.news-admin-btn.danger{background:#fff1f2;color:#be123c}.news-admin-btn.small{padding:8px 11px;font-size:11px}
.news-admin-post-card{margin-top:18px;border:1px solid #dbe4ef;border-radius:13px;background:#fff;overflow:hidden}.news-admin-post-summary{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;padding:20px}.news-admin-post-main{min-width:0}.news-admin-post-main h3{margin:9px 0 6px;color:#12345a;font-size:18px}.news-admin-post-main>p{margin:0;color:#61758b;font-size:12px;line-height:1.65}.news-admin-post-status-row,.news-admin-post-meta,.news-admin-post-controls{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.news-admin-post-date,.news-admin-post-meta{color:#7b8ca0;font-size:10px}.news-admin-post-meta{margin-top:12px}.news-status-badge{padding:5px 8px;border-radius:999px;font-size:9px;font-weight:900;letter-spacing:.5px;text-transform:uppercase}.news-status-badge.published{background:#dcfce7;color:#166534}.news-status-badge.draft{background:#fef3c7;color:#92400e}.record-thumbnail{width:48px;height:48px;border:0;border-radius:9px;padding:0;overflow:hidden;background:#edf3f8;cursor:pointer}.record-thumbnail.round{border-radius:50%}.record-thumbnail img{width:100%;height:100%;object-fit:cover}
.news-admin-edit-panel{display:none;padding:0 20px 22px;border-top:1px solid #e8eef5}.news-admin-edit-panel.open{display:block}.news-admin-empty{padding:54px 20px;text-align:center;color:#70839a}.news-admin-empty svg{width:42px;height:42px}.news-admin-empty h3{margin:10px 0 4px;color:#173b63}.news-admin-empty p{margin:0;font-size:12px}.news-admin-pagination{display:flex;justify-content:center;margin-top:22px}
.media-preview-modal[hidden]{display:none}.media-preview-modal{position:fixed;inset:0;z-index:5000;display:grid;place-items:center;padding:24px}.media-preview-modal-backdrop{position:absolute;inset:0;background:rgba(4,13,24,.82);backdrop-filter:blur(4px)}.media-preview-modal-dialog{position:relative;z-index:1;width:min(950px,94vw);max-height:90vh;padding:18px;border:1px solid rgba(255,255,255,.18);border-radius:14px;background:#0d1d30;box-shadow:0 26px 70px rgba(0,0,0,.5)}.media-preview-modal-content{display:grid;max-height:78vh;place-items:center;overflow:auto}.media-preview-modal-content img{display:block;max-width:100%;max-height:75vh;border-radius:9px;background:#000}.media-preview-modal-name{margin:12px 38px 0;color:#dbeafe;font-size:12px;text-align:center;overflow-wrap:anywhere}.media-preview-modal-close{position:absolute;right:10px;top:8px;z-index:2;width:34px;height:34px;border:0;border-radius:50%;background:rgba(255,255,255,.12);color:#fff;font-size:24px;cursor:pointer}body.media-modal-open{overflow:hidden}
@media(max-width:900px){.news-admin-banner{align-items:flex-start;flex-direction:column;padding:25px}.news-admin-panel{padding:21px}.news-post-form{grid-template-columns:1fr}.news-admin-field.full,.news-admin-actions.full{grid-column:auto}.news-admin-post-summary{flex-direction:column}.news-admin-post-controls{width:100%}.single-preview-actions{align-items:flex-start;flex-direction:column}.single-preview-actions small{text-align:left}}
@media(max-width:600px){.news-admin-banner h1{font-size:23px}.news-admin-panel-head{align-items:flex-start;flex-direction:column}.news-admin-hint{text-align:left}.news-admin-actions{flex-wrap:wrap}.news-admin-btn{flex:1}.news-admin-post-summary{padding:16px}}
</style>

<script>
function toggleNewsEdit(id) {
    var panel = document.getElementById('newsEditPanel' + id);

    if (panel) {
        panel.classList.toggle('open');
    }
}

(function () {
    'use strict';

    var modal = document.querySelector('[data-media-modal]');
    var modalContent = modal ? modal.querySelector('[data-media-modal-content]') : null;
    var modalName = modal ? modal.querySelector('[data-media-modal-name]') : null;
    var modalObjectUrl = null;

    function closeModal() {
        if (!modal) return;

        modal.hidden = true;
        document.body.classList.remove('media-modal-open');

        if (modalContent) modalContent.innerHTML = '';
        if (modalName) modalName.textContent = '';

        if (modalObjectUrl) {
            URL.revokeObjectURL(modalObjectUrl);
            modalObjectUrl = null;
        }
    }

    function openImagePreview(url, name, revokeAfterClose) {
        if (!modal || !modalContent) return;

        closeModal();

        var image = document.createElement('img');
        image.src = url;
        image.alt = name || 'Image preview';
        modalContent.appendChild(image);

        if (modalName) modalName.textContent = name || '';
        if (revokeAfterClose) modalObjectUrl = url;

        modal.hidden = false;
        document.body.classList.add('media-modal-open');
    }

    document.querySelectorAll('[data-media-modal-close]').forEach(function (button) {
        button.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal && !modal.hidden) {
            closeModal();
        }
    });

    document.querySelectorAll('[data-existing-image-url]').forEach(function (button) {
        button.addEventListener('click', function () {
            openImagePreview(
                button.getAttribute('data-existing-image-url'),
                button.getAttribute('data-existing-image-name'),
                false
            );
        });
    });

    document.querySelectorAll('[data-single-media-picker]').forEach(function (picker) {
        var input = picker.querySelector('[data-media-input]');
        var preview = picker.querySelector('[data-preview]');
        var icon = picker.querySelector('[data-upload-icon]');
        var selectedText = picker.querySelector('[data-selected-files]');
        var viewButton = picker.querySelector('[data-view-selected]');
        var form = picker.closest('form');
        var previewUrl = null;

        if (!input || !preview) return;

        function clearPreview() {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            preview.hidden = true;
            preview.removeAttribute('src');

            if (icon) icon.hidden = false;
            if (viewButton) viewButton.classList.remove('visible');
            if (selectedText) selectedText.textContent = 'No file selected';
        }

        input.addEventListener('change', function () {
            clearPreview();

            var file = input.files && input.files[0];

            if (!file) return;

            if (file.type.indexOf('image/') !== 0) {
                window.alert('Please select an image file.');
                input.value = '';
                return;
            }

            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;
            preview.alt = file.name;
            preview.hidden = false;

            if (icon) icon.hidden = true;
            if (viewButton) viewButton.classList.add('visible');
            if (selectedText) selectedText.textContent = file.name;
        });

        preview.addEventListener('click', function (event) {
            event.preventDefault();

            var file = input.files && input.files[0];

            if (file) {
                openImagePreview(URL.createObjectURL(file), file.name, true);
            }
        });

        if (viewButton) {
            viewButton.addEventListener('click', function () {
                var file = input.files && input.files[0];

                if (file) {
                    openImagePreview(URL.createObjectURL(file), file.name, true);
                }
            });
        }

        if (form) {
            form.addEventListener('reset', function () {
                window.setTimeout(clearPreview, 0);
            });
        }
    });
})();
</script>

@endsection
