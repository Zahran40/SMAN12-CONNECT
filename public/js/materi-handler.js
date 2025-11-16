// Materi Form Handler
document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle Pertemuan Accordion
    const pertemuanHeaders = document.querySelectorAll('[data-pertemuan-toggle]');
    pertemuanHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('svg');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    // Radio Button Handler untuk Pilih Berkas (Materi/Tugas)
    const radioButtons = document.querySelectorAll('input[name="tipe_berkas"]');
    const waktuSection = document.getElementById('waktu-section');
    
    // Initialize waktu section based on current selection
    const checkedRadio = document.querySelector('input[name="tipe_berkas"]:checked');
    if (waktuSection && checkedRadio) {
        if (checkedRadio.value === 'tugas') {
            waktuSection.classList.remove('hidden');
        } else {
            waktuSection.classList.add('hidden');
        }
    }
    
    radioButtons.forEach(radio => {
        // Trigger click on label to change radio
        const label = radio.closest('label');
        if (label) {
            label.addEventListener('click', function(e) {
                // Prevent default only if clicking on the label itself, not the input
                if (e.target !== radio) {
                    e.preventDefault();
                }
                
                radio.checked = true;
                
                // Update UI radio visual
                radioButtons.forEach(r => {
                    const rLabel = r.closest('label');
                    if (rLabel) {
                        const visualCircle = rLabel.querySelector('div.w-6');
                        if (visualCircle) {
                            const visualDot = visualCircle.querySelector('div');
                            
                            if (r.checked) {
                                if (visualDot) visualDot.classList.remove('hidden');
                                visualCircle.classList.remove('border-slate-300');
                                visualCircle.classList.add('border-blue-400');
                            } else {
                                if (visualDot) visualDot.classList.add('hidden');
                                visualCircle.classList.add('border-slate-300');
                                visualCircle.classList.remove('border-blue-400');
                            }
                        }
                    }
                });
                
                // Toggle waktu section visibility
                if (waktuSection) {
                    if (radio.value === 'tugas') {
                        waktuSection.classList.remove('hidden');
                    } else {
                        waktuSection.classList.add('hidden');
                    }
                }
            });
        }
    });

    // File Upload Preview
    const fileInput = document.getElementById('file-upload');
    const uploadArea = document.querySelector('.border-dashed');
    
    if (fileInput && uploadArea) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2); // MB
                
                // Create file preview
                const preview = document.createElement('div');
                preview.className = 'mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between';
                preview.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-slate-700">${fileName}</p>
                            <p class="text-xs text-slate-500">${fileSize} MB</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFilePreview()" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                `;
                
                uploadArea.parentNode.insertBefore(preview, uploadArea.nextSibling);
                uploadArea.classList.add('hidden');
            }
        });
    }

    // Form Validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('Validating form...');
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            console.log('Required fields count:', requiredFields.length);
            
            requiredFields.forEach(field => {
                let fieldValue = '';
                
                // Special handling for file input
                if (field.type === 'file') {
                    fieldValue = field.files && field.files.length > 0 ? field.files[0].name : '';
                    console.log(`File field ${field.name}: ${fieldValue} (has files: ${field.files ? field.files.length : 0})`);
                } else {
                    fieldValue = field.value ? field.value.trim() : '';
                }
                
                console.log(`Field ${field.name}: "${fieldValue}" (required: ${field.hasAttribute('required')})`);
                
                if (!fieldValue) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Show error message
                    let errorMsg = field.nextElementSibling;
                    if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                        errorMsg = document.createElement('p');
                        errorMsg.className = 'error-message text-red-500 text-sm mt-1';
                        errorMsg.textContent = 'Field ini wajib diisi';
                        field.parentNode.insertBefore(errorMsg, field.nextSibling);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    const errorMsg = field.nextElementSibling;
                    if (errorMsg && errorMsg.classList.contains('error-message')) {
                        errorMsg.remove();
                    }
                }
            });
            
            console.log('Form valid:', isValid);
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi');
                return false;
            }
        });
    });

    // Delete Confirmation
    const deleteButtons = document.querySelectorAll('[data-delete-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Helper function untuk remove file preview
function removeFilePreview() {
    const fileInput = document.getElementById('file-upload');
    const uploadArea = document.querySelector('.border-dashed');
    const preview = uploadArea.nextElementSibling;
    
    if (fileInput) fileInput.value = '';
    if (preview && preview.classList.contains('mt-2')) preview.remove();
    if (uploadArea) uploadArea.classList.remove('hidden');
}

// AJAX Upload untuk tugas siswa
function uploadTugasSiswa(tugasId) {
    const formData = new FormData();
    const fileInput = document.getElementById('tugas-file-' + tugasId);
    
    if (!fileInput || !fileInput.files[0]) {
        alert('Pilih file terlebih dahulu');
        return;
    }
    
    formData.append('file', fileInput.files[0]);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    fetch(`/siswa/materi/upload-tugas/${tugasId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Gagal mengupload tugas');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupload');
    });
}
