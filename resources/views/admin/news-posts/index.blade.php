@extends('layouts.dashboard')

@section('title', 'News & Updates Posting')

@section('content')
@php
    $__u = auth()->user();

    if (!$__u || (!$__u->isAdmin() && in_array('admin.news-updates', $__u->hidden_pages ?? []))) {
        abort(403);
    }
@endphp

<div class="news-admin-page">
    <section class="news-admin-banner">
        <div>
            <span class="news-admin-kicker">Admin Publishing</span>
            <h1>News &amp; Updates Posting</h1>
            <p>Create, preview, edit, and publish announcements for the public News &amp; Updates page.</p>
        </div>

        <a
            href="{{ route('news-updates') }}"
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

    @if(session('news_success'))
        <div class="news-admin-alert success">{{ session('news_success') }}</div>
    @endif

    @if(session('news_error'))
        <div class="news-admin-alert error">{{ session('news_error') }}</div>
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
                <h2>Create a New Post</h2>
            </div>

            <span class="news-admin-hint">Up to 10 images/videos per save</span>
        </div>

        <form
            method="POST"
            action="{{ route('admin.news-updates.store') }}"
            enctype="multipart/form-data"
            class="news-post-form"
            onsubmit="return confirm('Save this news update?');"
        >
            @csrf

            <div class="news-admin-field full">
                <label for="news-title">Post Title</label>
                <input
                    id="news-title"
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    maxlength="180"
                    required
                    placeholder="Enter the news or announcement title"
                >
            </div>

            <div class="news-admin-field full">
                <label for="news-description">Description</label>
                <textarea
                    id="news-description"
                    name="description"
                    rows="7"
                    maxlength="30000"
                    required
                    placeholder="Write the complete news or announcement details..."
                >{{ old('description') }}</textarea>
            </div>

            <div class="news-admin-field">
                <label for="news-status">Posting Status</label>
                <select id="news-status" name="status" required>
                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Save as Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publish</option>
                </select>
            </div>

            <div class="news-admin-field news-auto-date-note">
                <label>Post Date &amp; Time</label>
                <div class="news-auto-date-box">Automatically recorded when you publish the post.</div>
                <small>Editing a published post keeps its original posting date and time.</small>
            </div>

            <div class="news-admin-field full">
                <label>Attach Images or Videos</label>

                <div class="media-picker" data-multi-media-picker data-max-files="10">
                    <label class="news-upload-box" for="news-media">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5.5 5.5 0 0116.9 6.1 4.5 4.5 0 0118 15H7zm5-7v8m0-8-3 3m3-3 3 3"/>
                        </svg>
                        <strong>Select a picture or video</strong>
                        <span>JPG, PNG, GIF, WEBP, MP4, MOV, or WEBM — maximum 100 MB each</span>
                        <span class="news-selected-files" data-selected-files>No files selected</span>
                    </label>

                    <input
                        id="news-media"
                        type="file"
                        name="media[]"
                        accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/webm"
                        multiple
                        hidden
                        data-media-input
                    >

                    <div class="media-picker-actions">
                        <button type="button" class="media-add-button" data-add-media>
                            <span aria-hidden="true">+</span>
                            Add another picture or video
                        </button>
                        <small>Files are not uploaded until you click “Save News Post.”</small>
                    </div>

                    <div class="media-preview-grid" data-media-preview-grid hidden></div>
                </div>
            </div>

            <div class="news-admin-actions full">
                <button type="reset" class="news-admin-btn secondary">Clear Form</button>
                <button type="submit" class="news-admin-btn primary">Save News Post</button>
            </div>
        </form>
    </section>

    <section class="news-admin-panel">
        <div class="news-admin-panel-head">
            <div>
                <span class="news-admin-section-number">02</span>
                <h2>All Posts</h2>
            </div>

            <span class="news-admin-hint">{{ $posts->total() }} total post{{ $posts->total() === 1 ? '' : 's' }}</span>
        </div>

        @forelse($posts as $post)
            <article class="news-admin-post-card">
                <div class="news-admin-post-summary">
                    <div class="news-admin-post-main">
                        <div class="news-admin-post-status-row">
                            <span class="news-status-badge {{ strtolower($post->publication_state) }}">
                                {{ $post->publication_state }}
                            </span>

                            <span class="news-admin-post-date">
                                @if($post->published_at)
                                    {{ $post->published_at->format('M d, Y · g:i A') }}
                                @else
                                    Not published
                                @endif
                            </span>
                        </div>

                        <h3>{{ $post->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($post->description, 220) }}</p>

                        <div class="news-admin-post-meta">
                            <span>Created {{ $post->created_at->diffForHumans() }}</span>
                            <span>{{ $post->media->count() }} attachment{{ $post->media->count() === 1 ? '' : 's' }}</span>

                            @if($post->creator)
                                <span>By {{ $post->creator->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="news-admin-post-controls">
                        <button
                            type="button"
                            class="news-admin-btn secondary small"
                            onclick="toggleNewsEdit({{ $post->id }})"
                        >
                            Edit
                        </button>

                        @if($__u->isAdmin())
                            <form
                                method="POST"
                                action="{{ route('admin.news-updates.destroy', $post) }}"
                                onsubmit="return confirm('Delete this news post and all of its attached media permanently?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="news-admin-btn danger small">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($post->media->isNotEmpty())
                    <div class="news-admin-media-strip">
                        @foreach($post->media as $media)
                            <div class="news-admin-media-item">
                                <button
                                    type="button"
                                    class="stored-media-view"
                                    data-existing-media-url="{{ $media->url }}"
                                    data-existing-media-type="{{ $media->media_type }}"
                                    data-existing-media-name="{{ $media->original_name }}"
                                    aria-label="View {{ $media->original_name }}"
                                >
                                    @if($media->media_type === 'image')
                                        <img src="{{ $media->url }}" alt="{{ $post->title }} attachment">
                                    @else
                                        <video src="{{ $media->url }}" muted playsinline preload="metadata"></video>
                                        <span class="stored-video-badge">Video</span>
                                    @endif
                                </button>

                                <div class="news-admin-media-caption">
                                    <span title="{{ $media->original_name }}">
                                        {{ \Illuminate\Support\Str::limit($media->original_name, 28) }}
                                    </span>
                                    <small>{{ $media->size_label }}</small>
                                </div>

                                <form
                                    method="POST"
                                    action="{{ route('admin.news-updates.media.destroy', $media) }}"
                                    onsubmit="return confirm('Remove this attachment from the post?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="news-media-remove"
                                        title="Remove attachment"
                                        aria-label="Remove attachment"
                                    >
                                        ×
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="news-admin-edit-panel" id="newsEditPanel{{ $post->id }}">
                    <form
                        method="POST"
                        action="{{ route('admin.news-updates.update', $post) }}"
                        enctype="multipart/form-data"
                        class="news-post-form"
                    >
                        @csrf
                        @method('PUT')

                        <div class="news-admin-field full">
                            <label for="edit-title-{{ $post->id }}">Post Title</label>
                            <input
                                id="edit-title-{{ $post->id }}"
                                type="text"
                                name="title"
                                value="{{ $post->title }}"
                                maxlength="180"
                                required
                            >
                        </div>

                        <div class="news-admin-field full">
                            <label for="edit-description-{{ $post->id }}">Description</label>
                            <textarea
                                id="edit-description-{{ $post->id }}"
                                name="description"
                                rows="7"
                                maxlength="30000"
                                required
                            >{{ $post->description }}</textarea>
                        </div>

                        <div class="news-admin-field">
                            <label for="edit-status-{{ $post->id }}">Posting Status</label>
                            <select id="edit-status-{{ $post->id }}" name="status" required>
                                <option value="draft" {{ $post->status === 'draft' ? 'selected' : '' }}>Save as Draft</option>
                                <option value="published" {{ $post->status === 'published' ? 'selected' : '' }}>Publish</option>
                            </select>
                        </div>

                        <div class="news-admin-field news-auto-date-note">
                            <label>Post Date &amp; Time</label>
                            <div class="news-auto-date-box">
                                @if($post->published_at)
                                    Posted {{ $post->published_at->format('M d, Y \a\t g:i A') }}
                                @else
                                    The date and time will be recorded when this draft is published.
                                @endif
                            </div>
                            <small>Editing does not change the original posting time.</small>
                        </div>

                        <div class="news-admin-field full">
                            <label>Add More Images or Videos</label>

                            <div class="media-picker" data-multi-media-picker data-max-files="10">
                                <label class="news-upload-box compact" for="edit-media-{{ $post->id }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14"/>
                                    </svg>
                                    <strong>Select a picture or video</strong>
                                    <span>Existing attachments remain unless removed above.</span>
                                    <span class="news-selected-files" data-selected-files>No files selected</span>
                                </label>

                                <input
                                    id="edit-media-{{ $post->id }}"
                                    type="file"
                                    name="media[]"
                                    accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/webm"
                                    multiple
                                    hidden
                                    data-media-input
                                >

                                <div class="media-picker-actions">
                                    <button type="button" class="media-add-button" data-add-media>
                                        <span aria-hidden="true">+</span>
                                        Add another picture or video
                                    </button>
                                    <small>New files are added after you click “Save Changes.”</small>
                                </div>

                                <div class="media-preview-grid" data-media-preview-grid hidden></div>
                            </div>
                        </div>

                        <div class="news-admin-actions full">
                            <button
                                type="button"
                                class="news-admin-btn secondary"
                                onclick="toggleNewsEdit({{ $post->id }})"
                            >
                                Cancel
                            </button>
                            <button type="submit" class="news-admin-btn primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </article>
        @empty
            <div class="news-admin-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2zM15 4v6h6M7 14h10M7 17h7"/>
                </svg>
                <h3>No news posts yet</h3>
                <p>Create the first post using the form above.</p>
            </div>
        @endforelse

        @if($posts->hasPages())
            <nav class="news-admin-pagination" aria-label="News post pages">
                @if($posts->onFirstPage())
                    <span class="disabled">Previous</span>
                @else
                    <a href="{{ $posts->previousPageUrl() }}">Previous</a>
                @endif

                <span>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</span>

                @if($posts->hasMorePages())
                    <a href="{{ $posts->nextPageUrl() }}">Next</a>
                @else
                    <span class="disabled">Next</span>
                @endif
            </nav>
        @endif
    </section>
</div>

<div class="media-preview-modal" data-media-modal hidden>
    <div class="media-preview-modal-backdrop" data-media-modal-close></div>
    <div class="media-preview-modal-dialog" role="dialog" aria-modal="true" aria-label="Media preview">
        <button type="button" class="media-preview-modal-close" data-media-modal-close aria-label="Close preview">×</button>
        <div class="media-preview-modal-content" data-media-modal-content></div>
        <p class="media-preview-modal-name" data-media-modal-name></p>
    </div>
</div>

<style>
.news-admin-page{display:flex;flex-direction:column;gap:22px}
.news-admin-banner{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:32px 36px;border-radius:20px;background:linear-gradient(135deg,#163a63 0%,#2563eb 62%,#173f70 100%);box-shadow:0 12px 34px rgba(30,69,117,.24);color:#fff}
.news-admin-kicker{display:block;margin-bottom:7px;color:#f7df9a;font-size:10px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase}
.news-admin-banner h1{margin:0;color:#fff;font-size:29px}
.news-admin-banner p{max-width:760px;margin:7px 0 0;color:rgba(255,255,255,.76);font-size:13px}
.news-admin-public-link{display:inline-flex;align-items:center;gap:8px;flex-shrink:0;padding:11px 15px;border:1px solid rgba(255,255,255,.35);border-radius:9px;color:#fff;font-size:12px;font-weight:700;text-decoration:none;transition:.2s ease}
.news-admin-public-link:hover{background:#fff;color:#163a63}.news-admin-public-link svg{width:17px;height:17px}
.news-admin-alert{padding:13px 16px;border-radius:9px;font-size:13px}.news-admin-alert.success{border:1px solid #86efac;background:#dcfce7;color:#166534}.news-admin-alert.error{border:1px solid #fecaca;background:#fef2f2;color:#b91c1c}.news-admin-alert ul{margin:8px 0 0 20px}
.news-admin-panel{padding:26px 28px;border:1px solid #dbe4ef;border-radius:16px;background:#fff;box-shadow:0 8px 26px rgba(15,42,71,.06)}
.news-admin-panel-head{display:flex;align-items:center;justify-content:space-between;gap:18px;padding-bottom:18px;border-bottom:1px solid #e5edf5}.news-admin-panel-head>div{display:flex;align-items:center;gap:12px}.news-admin-panel-head h2{margin:0;color:#12345a;font-size:20px}.news-admin-section-number{color:#c38a17;font-family:monospace;font-size:11px}.news-admin-hint{color:#70839a;font-size:11px;text-align:right}
.news-post-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-top:22px}.news-admin-field{display:flex;flex-direction:column;gap:7px}.news-admin-field.full,.news-admin-actions.full{grid-column:1/-1}.news-admin-field>label{color:#173b63;font-size:11px;font-weight:800;letter-spacing:.45px;text-transform:uppercase}.news-admin-field input,.news-admin-field select,.news-admin-field textarea{width:100%;padding:12px 14px;border:1px solid #ccd9e7;border-radius:9px;background:#fff;color:#18344f;font:inherit;font-size:13px;outline:none;transition:.2s}.news-admin-field textarea{resize:vertical}.news-admin-field input:focus,.news-admin-field select:focus,.news-admin-field textarea:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}.news-admin-field small{color:#7b8ca0;font-size:10px}.news-auto-date-box{min-height:44px;padding:12px 14px;border:1px solid #d7e2ee;border-radius:9px;background:#f7fafc;color:#64748b;font-size:12px}
.news-upload-box{display:flex;min-height:150px;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:20px;border:2px dashed #9ab7d8;border-radius:13px;background:#f6faff;text-align:center;cursor:pointer;transition:.2s}.news-upload-box.compact{min-height:120px}.news-upload-box:hover{border-color:#2563eb;background:#eff6ff}.news-upload-box svg{width:34px;height:34px;color:#2563eb}.news-upload-box strong{color:#12345a;font-size:13px}.news-upload-box span{color:#6b7f96;font-size:10px}.news-selected-files{max-width:100%;color:#b2790a!important;font-weight:700;overflow-wrap:anywhere}
.media-picker-actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:10px}.media-add-button{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border:1px solid #2563eb;border-radius:9px;background:#fff;color:#1d4ed8;font-size:12px;font-weight:800;cursor:pointer;transition:.2s}.media-add-button span{display:grid;width:21px;height:21px;place-items:center;border-radius:50%;background:#2563eb;color:#fff;font-size:17px;line-height:1}.media-add-button:hover{background:#eff6ff}.media-picker-actions small{color:#7b8ca0;font-size:10px;text-align:right}
.media-preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;margin-top:16px}.media-preview-card{position:relative;overflow:hidden;border:1px solid #d7e2ee;border-radius:12px;background:#fff;box-shadow:0 5px 16px rgba(15,42,71,.08)}.media-preview-thumb{display:block;width:100%;height:132px;padding:0;border:0;background:#0f2033;cursor:pointer}.media-preview-thumb img,.media-preview-thumb video{display:block;width:100%;height:100%;object-fit:cover}.media-preview-card-copy{padding:10px 38px 10px 11px}.media-preview-card-copy strong,.media-preview-card-copy small{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.media-preview-card-copy strong{color:#173b63;font-size:11px}.media-preview-card-copy small{margin-top:3px;color:#74879d;font-size:9px}.media-preview-remove{position:absolute;right:8px;bottom:10px;width:25px;height:25px;border:0;border-radius:50%;background:#fee2e2;color:#b91c1c;font-size:18px;line-height:1;cursor:pointer}.media-preview-type{position:absolute;top:8px;left:8px;padding:4px 7px;border-radius:999px;background:rgba(10,25,42,.78);color:#fff;font-size:9px;font-weight:800;text-transform:uppercase}
.news-admin-actions{display:flex;justify-content:flex-end;gap:10px;padding-top:4px}.news-admin-btn{display:inline-flex;align-items:center;justify-content:center;padding:11px 16px;border:1px solid transparent;border-radius:8px;font-size:12px;font-weight:800;cursor:pointer;transition:.2s}.news-admin-btn.primary{background:#2563eb;color:#fff}.news-admin-btn.primary:hover{background:#1d4ed8}.news-admin-btn.secondary{border-color:#cbd7e5;background:#fff;color:#173b63}.news-admin-btn.secondary:hover{background:#f7fafc}.news-admin-btn.danger{background:#fff1f2;color:#be123c}.news-admin-btn.small{padding:8px 11px;font-size:11px}
.news-admin-post-card{margin-top:18px;border:1px solid #dbe4ef;border-radius:13px;background:#fff;overflow:hidden}.news-admin-post-summary{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;padding:20px}.news-admin-post-main{min-width:0}.news-admin-post-main h3{margin:9px 0 6px;color:#12345a;font-size:18px}.news-admin-post-main>p{margin:0;color:#61758b;font-size:12px;line-height:1.65}.news-admin-post-status-row,.news-admin-post-meta,.news-admin-post-controls{display:flex;align-items:center;gap:10px;flex-wrap:wrap}.news-admin-post-date,.news-admin-post-meta{color:#7b8ca0;font-size:10px}.news-admin-post-meta{margin-top:12px}.news-status-badge{padding:5px 8px;border-radius:999px;font-size:9px;font-weight:900;letter-spacing:.5px;text-transform:uppercase}.news-status-badge.published{background:#dcfce7;color:#166534}.news-status-badge.draft{background:#fef3c7;color:#92400e}
.news-admin-media-strip{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;padding:0 20px 20px}.news-admin-media-item{position:relative;overflow:hidden;border:1px solid #dbe4ef;border-radius:10px;background:#f8fafc}.stored-media-view{position:relative;display:block;width:100%;height:110px;padding:0;border:0;background:#102237;cursor:pointer}.stored-media-view img,.stored-media-view video{width:100%;height:100%;object-fit:cover}.stored-video-badge{position:absolute;left:8px;top:8px;padding:4px 7px;border-radius:999px;background:rgba(0,0,0,.72);color:#fff;font-size:9px;font-weight:800}.news-admin-media-caption{padding:9px 34px 9px 10px}.news-admin-media-caption span,.news-admin-media-caption small{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.news-admin-media-caption span{color:#173b63;font-size:10px;font-weight:700}.news-admin-media-caption small{margin-top:2px;color:#7b8ca0;font-size:9px}.news-media-remove{position:absolute;right:7px;bottom:8px;width:23px;height:23px;border:0;border-radius:50%;background:#fee2e2;color:#b91c1c;font-size:17px;cursor:pointer}
.news-admin-edit-panel{display:none;padding:0 20px 22px;border-top:1px solid #e8eef5}.news-admin-edit-panel.open{display:block}.news-admin-empty{padding:54px 20px;text-align:center;color:#70839a}.news-admin-empty svg{width:42px;height:42px}.news-admin-empty h3{margin:10px 0 4px;color:#173b63}.news-admin-empty p{margin:0;font-size:12px}.news-admin-pagination{display:flex;align-items:center;justify-content:center;gap:14px;margin-top:22px;color:#64748b;font-size:11px}.news-admin-pagination a,.news-admin-pagination span{padding:8px 11px;border:1px solid #d4dfeb;border-radius:7px;text-decoration:none}.news-admin-pagination a{color:#1d4ed8}.news-admin-pagination .disabled{opacity:.45}
.media-preview-modal[hidden]{display:none}.media-preview-modal{position:fixed;inset:0;z-index:5000;display:grid;place-items:center;padding:24px}.media-preview-modal-backdrop{position:absolute;inset:0;background:rgba(4,13,24,.82);backdrop-filter:blur(4px)}.media-preview-modal-dialog{position:relative;z-index:1;width:min(1000px,94vw);max-height:90vh;padding:18px;border:1px solid rgba(255,255,255,.18);border-radius:14px;background:#0d1d30;box-shadow:0 26px 70px rgba(0,0,0,.5)}.media-preview-modal-content{display:grid;max-height:78vh;place-items:center;overflow:auto}.media-preview-modal-content img,.media-preview-modal-content video{display:block;max-width:100%;max-height:75vh;border-radius:9px;background:#000}.media-preview-modal-name{margin:12px 38px 0;color:#dbeafe;font-size:12px;text-align:center;overflow-wrap:anywhere}.media-preview-modal-close{position:absolute;right:10px;top:8px;z-index:2;width:34px;height:34px;border:0;border-radius:50%;background:rgba(255,255,255,.12);color:#fff;font-size:24px;cursor:pointer}
body.media-modal-open{overflow:hidden}
@media(max-width:900px){.news-admin-banner{align-items:flex-start;flex-direction:column;padding:25px}.news-admin-panel{padding:21px}.news-post-form{grid-template-columns:1fr}.news-admin-field.full,.news-admin-actions.full{grid-column:auto}.news-admin-post-summary{flex-direction:column}.news-admin-post-controls{width:100%}.media-picker-actions{align-items:flex-start;flex-direction:column}.media-picker-actions small{text-align:left}}
@media(max-width:600px){.news-admin-banner h1{font-size:23px}.news-admin-panel-head{align-items:flex-start;flex-direction:column}.news-admin-hint{text-align:left}.news-admin-actions{flex-wrap:wrap}.news-admin-btn{flex:1}.media-preview-grid{grid-template-columns:1fr 1fr}.media-preview-thumb{height:105px}.news-admin-media-strip{grid-template-columns:1fr 1fr;padding:0 14px 16px}.news-admin-post-summary{padding:16px}}
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

    function formatFileSize(bytes) {
        if (!bytes) return '0 B';

        var units = ['B', 'KB', 'MB', 'GB'];
        var index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
        var value = bytes / Math.pow(1024, index);

        return (index === 0 ? value.toFixed(0) : value.toFixed(1)) + ' ' + units[index];
    }

    function fileKey(file) {
        return [file.name, file.size, file.lastModified, file.type].join('|');
    }

    function isAllowedMedia(file) {
        return file && (file.type.indexOf('image/') === 0 || file.type.indexOf('video/') === 0);
    }

    function closeModal() {
        if (!modal) return;

        var video = modal.querySelector('video');
        if (video) video.pause();

        modal.hidden = true;
        document.body.classList.remove('media-modal-open');

        if (modalContent) modalContent.innerHTML = '';
        if (modalName) modalName.textContent = '';

        if (modalObjectUrl) {
            URL.revokeObjectURL(modalObjectUrl);
            modalObjectUrl = null;
        }
    }

    function openModalWithUrl(url, type, name, revokeAfterClose) {
        if (!modal || !modalContent) return;

        closeModal();

        var element;

        if (type === 'video') {
            element = document.createElement('video');
            element.controls = true;
            element.autoplay = true;
            element.playsInline = true;
        } else {
            element = document.createElement('img');
            element.alt = name || 'Selected image preview';
        }

        element.src = url;
        modalContent.appendChild(element);

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

    document.querySelectorAll('[data-existing-media-url]').forEach(function (button) {
        button.addEventListener('click', function () {
            openModalWithUrl(
                button.getAttribute('data-existing-media-url'),
                button.getAttribute('data-existing-media-type'),
                button.getAttribute('data-existing-media-name'),
                false
            );
        });
    });

    document.querySelectorAll('[data-multi-media-picker]').forEach(function (picker) {
        var input = picker.querySelector('[data-media-input]');
        var addButton = picker.querySelector('[data-add-media]');
        var grid = picker.querySelector('[data-media-preview-grid]');
        var selectedText = picker.querySelector('[data-selected-files]');
        var form = picker.closest('form');
        var maxFiles = parseInt(picker.getAttribute('data-max-files') || '10', 10);
        var selectedFiles = [];
        var previewUrls = [];

        if (!input || !grid) return;

        function revokePreviewUrls() {
            previewUrls.forEach(function (url) {
                URL.revokeObjectURL(url);
            });
            previewUrls = [];
        }

        function syncInputFiles() {
            if (typeof DataTransfer === 'undefined') {
                return;
            }

            var transfer = new DataTransfer();

            selectedFiles.forEach(function (file) {
                transfer.items.add(file);
            });

            input.files = transfer.files;
        }

        function renderPreviews() {
            revokePreviewUrls();
            grid.innerHTML = '';

            if (selectedFiles.length === 0) {
                grid.hidden = true;

                if (selectedText) {
                    selectedText.textContent = 'No files selected';
                }

                return;
            }

            grid.hidden = false;

            if (selectedText) {
                selectedText.textContent = selectedFiles.length + ' file' + (selectedFiles.length === 1 ? '' : 's') + ' ready to upload';
            }

            selectedFiles.forEach(function (file, index) {
                var url = URL.createObjectURL(file);
                previewUrls.push(url);

                var card = document.createElement('article');
                card.className = 'media-preview-card';

                var previewButton = document.createElement('button');
                previewButton.type = 'button';
                previewButton.className = 'media-preview-thumb';
                previewButton.title = 'View ' + file.name;

                var media;

                if (file.type.indexOf('video/') === 0) {
                    media = document.createElement('video');
                    media.muted = true;
                    media.playsInline = true;
                    media.preload = 'metadata';
                } else {
                    media = document.createElement('img');
                    media.alt = file.name;
                }

                media.src = url;
                previewButton.appendChild(media);

                previewButton.addEventListener('click', function () {
                    var modalUrl = URL.createObjectURL(file);

                    openModalWithUrl(
                        modalUrl,
                        file.type.indexOf('video/') === 0 ? 'video' : 'image',
                        file.name,
                        true
                    );
                });

                var typeBadge = document.createElement('span');
                typeBadge.className = 'media-preview-type';
                typeBadge.textContent = file.type.indexOf('video/') === 0 ? 'Video' : 'Image';

                var copy = document.createElement('div');
                copy.className = 'media-preview-card-copy';

                var filename = document.createElement('strong');
                filename.textContent = file.name;
                filename.title = file.name;

                var size = document.createElement('small');
                size.textContent = formatFileSize(file.size) + ' · Click preview to view';

                copy.appendChild(filename);
                copy.appendChild(size);

                var removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'media-preview-remove';
                removeButton.setAttribute('aria-label', 'Remove ' + file.name);
                removeButton.title = 'Remove file';
                removeButton.textContent = '×';

                removeButton.addEventListener('click', function () {
                    selectedFiles.splice(index, 1);
                    syncInputFiles();
                    renderPreviews();
                });

                card.appendChild(previewButton);
                card.appendChild(typeBadge);
                card.appendChild(copy);
                card.appendChild(removeButton);
                grid.appendChild(card);
            });
        }

        function addFiles(files) {
            var existingKeys = {};
            var rejectedForLimit = 0;

            selectedFiles.forEach(function (file) {
                existingKeys[fileKey(file)] = true;
            });

            files.forEach(function (file) {
                if (!isAllowedMedia(file)) {
                    return;
                }

                var key = fileKey(file);

                if (existingKeys[key]) {
                    return;
                }

                if (selectedFiles.length >= maxFiles) {
                    rejectedForLimit++;
                    return;
                }

                selectedFiles.push(file);
                existingKeys[key] = true;
            });

            if (rejectedForLimit > 0) {
                window.alert('You may attach up to ' + maxFiles + ' pictures or videos per save.');
            }

            syncInputFiles();
            renderPreviews();
        }

        input.addEventListener('change', function () {
            addFiles(Array.prototype.slice.call(input.files || []));
        });

        if (addButton) {
            addButton.addEventListener('click', function () {
                input.click();
            });
        }

        if (form) {
            form.addEventListener('reset', function () {
                window.setTimeout(function () {
                    selectedFiles = [];
                    syncInputFiles();
                    renderPreviews();
                }, 0);
            });
        }
    });
})();
</script>
@endsection
