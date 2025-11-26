/**
 * Quill.js Rich Text Editor Configuration
 * A modern, free alternative to TinyMCE and CKEditor
 */

class QuillEditor {
    constructor(editorId, options = {}) {
        this.editorId = editorId;
        this.options = {
            placeholder: 'Start writing your blog post...',
            theme: 'snow',
            ...options
        };
        
        this.quill = null;
        this.isDarkMode = this.checkDarkMode();
        this.init();
    }

    checkDarkMode() {
        return localStorage.getItem('theme') === 'dark' || 
               (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }

    init() {
        // Wait for Quill to be loaded
        if (typeof Quill === 'undefined') {
            console.error('Quill.js is not loaded. Please include the Quill.js library.');
            return;
        }

        // Default configuration
        const quillConfig = {
            theme: this.options.theme,
            placeholder: this.options.placeholder,
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            formats: [
                'header', 'bold', 'italic', 'underline', 'strike',
                'color', 'background', 'list', 'bullet', 'indent',
                'align', 'blockquote', 'code-block', 'link', 'image', 'video'
            ]
        };

        // Initialize Quill
        this.quill = new Quill(this.editorId, quillConfig);

        // Set initial content if exists
        const textarea = document.getElementById('content');
        if (textarea && textarea.value) {
            this.quill.root.innerHTML = textarea.value;
        }

        // Update hidden textarea on content change
        this.quill.on('text-change', () => {
            if (textarea) {
                textarea.value = this.quill.root.innerHTML;
            }
        });

        // Setup image upload handler
        this.setupImageUpload();

        // Apply dark mode styles
        if (this.isDarkMode) {
            this.applyDarkMode();
        }

        // Setup form validation
        this.setupFormValidation();
    }

    setupImageUpload() {
        const toolbar = this.quill.getModule('toolbar');
        toolbar.addHandler('image', () => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    this.uploadImage(file);
                }
            };
        });
    }

    uploadImage(file) {
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            this.showAlert('Image size must be less than 5MB', 'error');
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            this.showAlert('Please select a valid image file', 'error');
            return;
        }

        // Create FormData for upload
        const formData = new FormData();
        formData.append('image', file);
        
        const csrfToken = document.querySelector('input[name="csrf_token"]');
        if (csrfToken) {
            formData.append('csrf_token', csrfToken.value);
        }

        // Show loading indicator
        const range = this.quill.getSelection();
        this.quill.insertText(range.index, 'Uploading image...', 'user');

        // Upload image
        fetch('/api/upload-image', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove loading text
                this.quill.deleteText(range.index, 20);
                // Insert image
                this.quill.insertEmbed(range.index, 'image', data.url);
                this.quill.setSelection(range.index + 1);
            } else {
                this.showAlert('Failed to upload image: ' + (data.message || 'Unknown error'), 'error');
                this.quill.deleteText(range.index, 20);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            this.showAlert('Failed to upload image', 'error');
            this.quill.deleteText(range.index, 20);
        });
    }

    applyDarkMode() {
        const editor = document.querySelector(this.editorId);
        if (editor) {
            editor.classList.add('ql-snow');
            editor.classList.add('ql-dark');

            // Add custom dark mode styles
            const style = document.createElement('style');
            style.textContent = `
                .ql-snow.ql-toolbar {
                    border-color: #374151 !important;
                    background-color: #1f2937 !important;
                }
                .ql-snow .ql-stroke {
                    stroke: #d1d5db !important;
                }
                .ql-snow .ql-fill {
                    fill: #d1d5db !important;
                }
                .ql-snow .ql-picker-label {
                    color: #d1d5db !important;
                }
                .ql-snow .ql-picker-options {
                    background-color: #1f2937 !important;
                    border-color: #374151 !important;
                }
                .ql-snow .ql-picker-item {
                    color: #d1d5db !important;
                }
                .ql-snow .ql-picker-item:hover {
                    background-color: #374151 !important;
                }
                .ql-editor {
                    color: #f9fafb !important;
                }
                .ql-editor.ql-blank::before {
                    color: #9ca3af !important;
                }
            `;
            document.head.appendChild(style);
        }
    }

    setupFormValidation() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                const content = this.quill.root.innerHTML;
                if (content.trim() === '<p><br></p>' || content.trim() === '') {
                    e.preventDefault();
                    this.showAlert('Please enter some content for your blog post.', 'error');
                    this.quill.focus();
                    return false;
                }
                
                const textarea = document.getElementById('content');
                if (textarea) {
                    textarea.value = content;
                }
            });
        }
    }

    showAlert(message, type = 'info') {
        // You can customize this to use your preferred notification system
        if (type === 'error') {
            alert(message);
        } else {
            console.log(message);
        }
    }

    // Public methods
    getContent() {
        return this.quill ? this.quill.root.innerHTML : '';
    }

    setContent(content) {
        if (this.quill) {
            this.quill.root.innerHTML = content;
        }
    }

    focus() {
        if (this.quill) {
            this.quill.focus();
        }
    }
}

// Auto-initialize if editor element exists
document.addEventListener('DOMContentLoaded', function() {
    const editorElement = document.querySelector('#editor');
    if (editorElement) {
        window.quillEditor = new QuillEditor('#editor');
    }
});
