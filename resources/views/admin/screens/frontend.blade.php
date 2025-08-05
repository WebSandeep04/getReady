@extends('admin.layouts.app')

@section('title', 'Frontend Management')

@push('styles')
<style>
    .setting-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
        transition: all 0.3s ease;
    }
    
    .setting-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .setting-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .setting-description {
        color: #666;
        font-size: 0.9em;
        margin-bottom: 15px;
    }
    
    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #ddd;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .btn-save {
        background: #28a745;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 0.9em;
    }
    
    .btn-save:hover {
        background: #218838;
    }
    
    .btn-save:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }
    
    .image-preview {
        max-width: 200px;
        max-height: 100px;
        border-radius: 4px;
        margin-top: 10px;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #666;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        color: #007bff;
        border-bottom: 2px solid #007bff;
        background: none;
    }
    
    .tab-content {
        padding-top: 20px;
    }
    
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .alert {
        border-radius: 6px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Frontend Management</h2>
                <button class="btn btn-primary" onclick="saveAllSettings()">
                    <i class="bi bi-save"></i> Save All Changes
                </button>
            </div>
            
            <!-- Alert Container -->
            <div id="alertContainer"></div>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs" id="frontendTabs" role="tablist">
                @foreach($sections as $sectionKey => $sectionName)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                            id="{{ $sectionKey }}-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#{{ $sectionKey }}-content" 
                            type="button" 
                            role="tab">
                        {{ $sectionName }}
                    </button>
                </li>
                @endforeach
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content" id="frontendTabContent">
                @foreach($sections as $sectionKey => $sectionName)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                     id="{{ $sectionKey }}-content" 
                     role="tabpanel">
                    
                    <div class="row">
                        @foreach($settings->where('section', $sectionKey) as $setting)
                        <div class="col-md-6 col-lg-4">
                            <div class="setting-card" data-setting-key="{{ $setting->key }}">
                                <div class="setting-label">{{ $setting->label }}</div>
                                <div class="setting-description">{{ $setting->description }}</div>
                                
                                @if($setting->type === 'text')
                                <input type="text" 
                                       class="form-control setting-input" 
                                       value="{{ $setting->value }}" 
                                       data-type="{{ $setting->type }}"
                                       placeholder="Enter {{ strtolower($setting->label) }}">
                                
                                @elseif($setting->type === 'textarea')
                                <textarea class="form-control setting-input" 
                                          rows="3" 
                                          data-type="{{ $setting->type }}"
                                          placeholder="Enter {{ strtolower($setting->label) }}">{{ $setting->value }}</textarea>
                                
                                @elseif($setting->type === 'image')
                                <div class="mb-2">
                                    @if($setting->value)
                                    <img src="{{ asset($setting->value) }}" 
                                         alt="{{ $setting->label }}" 
                                         class="image-preview d-block">
                                    @endif
                                </div>
                                <input type="file" 
                                       class="form-control setting-input" 
                                       accept="image/*"
                                       data-type="{{ $setting->type }}"
                                       data-current-value="{{ $setting->value }}">
                                <small class="text-muted">Current: {{ $setting->value ?: 'No image set' }}</small>
                                
                                @endif
                                
                                <div class="mt-3">
                                    <button class="btn btn-save btn-sm" 
                                            onclick="saveSetting('{{ $setting->key }}', this)">
                                        <i class="bi bi-check"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-frontend.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle file input changes for image preview
    $('.setting-input[data-type="image"]').on('change', function() {
        const file = this.files[0];
        const card = $(this).closest('.setting-card');
        const preview = card.find('.image-preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview.length === 0) {
                    card.find('.mb-2').html(`<img src="${e.target.result}" alt="Preview" class="image-preview d-block">`);
                } else {
                    preview.attr('src', e.target.result);
                }
            };
            reader.readAsDataURL(file);
        }
    });
});

function saveSetting(key, button) {
    const card = $(button).closest('.setting-card');
    const input = card.find('.setting-input');
    const type = input.data('type');
    const formData = new FormData();
    
    formData.append('key', key);
    formData.append('type', type);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    
    if (type === 'image') {
        const file = input[0].files[0];
        if (file) {
            formData.append('value', file);
        } else {
            formData.append('value', input.data('current-value'));
        }
    } else {
        formData.append('value', input.val());
    }
    
    // Disable button and show loading
    $(button).prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
    card.addClass('loading');
    
    $.ajax({
        url: '{{ route("admin.frontend.update") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showAlert('success', 'Setting updated successfully!');
                
                // Update current value for image inputs
                if (type === 'image' && response.value) {
                    input.data('current-value', response.value);
                }
            } else {
                showAlert('danger', response.message || 'Error updating setting');
            }
        },
        error: function(xhr) {
            showAlert('danger', 'Error updating setting. Please try again.');
        },
        complete: function() {
            $(button).prop('disabled', false).html('<i class="bi bi-check"></i> Save');
            card.removeClass('loading');
        }
    });
}

function saveAllSettings() {
    const allInputs = $('.setting-input');
    let savedCount = 0;
    let totalCount = allInputs.length;
    
    if (totalCount === 0) {
        showAlert('info', 'No settings to save');
        return;
    }
    
    showAlert('info', 'Saving all settings...');
    
    allInputs.each(function(index) {
        const input = $(this);
        const card = input.closest('.setting-card');
        const key = card.data('setting-key');
        const type = input.data('type');
        const formData = new FormData();
        
        formData.append('key', key);
        formData.append('type', type);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        if (type === 'image') {
            const file = input[0].files[0];
            if (file) {
                formData.append('value', file);
            } else {
                formData.append('value', input.data('current-value'));
            }
        } else {
            formData.append('value', input.val());
        }
        
        $.ajax({
            url: '{{ route("admin.frontend.update") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                savedCount++;
                if (savedCount === totalCount) {
                    showAlert('success', `All settings saved successfully! (${savedCount}/${totalCount})`);
                }
            },
            error: function(xhr) {
                savedCount++;
                if (savedCount === totalCount) {
                    showAlert('warning', `Some settings may not have been saved. (${savedCount}/${totalCount})`);
                }
            }
        });
    });
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('#alertContainer').html(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// Auto-save functionality (optional)
let autoSaveTimer;
$('.setting-input').on('input', function() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        // Uncomment the line below to enable auto-save
        // saveAllSettings();
    }, 3000); // Auto-save after 3 seconds of inactivity
});
</script>
@endpush
