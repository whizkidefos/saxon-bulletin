document.addEventListener('DOMContentLoaded', function() {
    const templateEditor = document.getElementById('template-editor');
    const previewModal = document.getElementById('preview-modal');
    const templateForm = document.getElementById('template-form');
    
    // Template Editor Functions
    function openEditor(template = null) {
        // Reset form
        templateForm.reset();
        
        if (template) {
            // Fill form with template data
            document.getElementById('template_slug').value = template.dataset.slug;
            document.getElementById('template_name').value = template.querySelector('h2').textContent;
            document.getElementById('template_description').value = template.querySelector('.description').textContent;
            document.getElementById('template_subject').value = template.dataset.subject;
            
            // Set content in TinyMCE
            if (typeof tinymce !== 'undefined') {
                const editor = tinymce.get('template_content');
                if (editor) {
                    editor.setContent(template.dataset.content);
                }
            }
        } else {
            // New template
            document.getElementById('template_slug').value = '';
            if (typeof tinymce !== 'undefined') {
                const editor = tinymce.get('template_content');
                if (editor) {
                    editor.setContent('');
                }
            }
        }

        templateEditor.style.display = 'block';
    }

    function closeEditor() {
        templateEditor.style.display = 'none';
    }

    // Preview Functions
    function openPreview(content) {
        const previewFrame = previewModal.querySelector('.preview-frame');
        previewFrame.innerHTML = content;
        previewModal.style.display = 'block';
    }

    function closePreview() {
        previewModal.style.display = 'none';
    }

    // Event Listeners
    document.querySelector('.add-template').addEventListener('click', (e) => {
        e.preventDefault();
        openEditor();
    });

    document.querySelectorAll('.edit-template').forEach(button => {
        button.addEventListener('click', () => {
            const template = button.closest('.template-card');
            openEditor(template);
        });
    });

    document.querySelectorAll('.preview-template').forEach(button => {
        button.addEventListener('click', async () => {
            const template = button.closest('.template-card');
            const content = template.dataset.content;

            try {
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'saxon_preview_template',
                        _ajax_nonce: saxonNewsletter.nonce,
                        content: content
                    })
                });

                const data = await response.json();
                if (data.success) {
                    openPreview(data.data.content);
                } else {
                    throw new Error(data.data);
                }
            } catch (error) {
                alert('Error loading preview: ' + error.message);
            }
        });
    });

    document.querySelectorAll('.delete-template').forEach(button => {
        button.addEventListener('click', async () => {
            if (!confirm(saxonNewsletter.confirmDelete)) return;

            const template = button.closest('.template-card');
            const slug = template.dataset.slug;

            try {
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'saxon_delete_template',
                        _ajax_nonce: saxonNewsletter.nonce,
                        template_slug: slug
                    })
                });

                const data = await response.json();
                if (data.success) {
                    template.remove();
                } else {
                    throw new Error(data.data);
                }
            } catch (error) {
                alert('Error deleting template: ' + error.message);
            }
        });
    });

    templateForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(templateForm);
        formData.append('action', 'saxon_save_template');
        formData.append('_ajax_nonce', saxonNewsletter.nonce);

        // Get content from TinyMCE
        if (typeof tinymce !== 'undefined') {
            const editor = tinymce.get('template_content');
            if (editor) {
                formData.set('template_content', editor.getContent());
            }
        }

        try {
            const response = await fetch(ajaxurl, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                closeEditor();
                window.location.reload(); // Refresh to show updated template
            } else {
                throw new Error(data.data);
            }
        } catch (error) {
            alert('Error saving template: ' + error.message);
        }
    });

    // Close modals
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', () => {
            closeEditor();
            closePreview();
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === templateEditor) closeEditor();
        if (e.target === previewModal) closePreview();
    });

    // Variable insertion buttons
    document.querySelectorAll('.variables-list code').forEach(code => {
        code.style.cursor = 'pointer';
        code.title = 'Click to insert';
        code.addEventListener('click', () => {
            const variable = code.textContent;
            if (typeof tinymce !== 'undefined') {
                const editor = tinymce.get('template_content');
                if (editor) {
                    editor.insertContent(variable);
                }
            }
        });
    });
});