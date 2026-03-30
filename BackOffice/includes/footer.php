    </main>

    <footer style="text-align: center; padding: 2rem; color: #666; font-size: 0.9rem;">
        <p>Iran Conflit - BackOffice &copy; <?= date('Y') ?></p>
    </footer>

    <!-- TinyMCE -->
    <script src="/js/tinymce/tinymce.min.js"></script>
    <script>
        // Initialiser TinyMCE uniquement si le textarea #contenu existe
        if (document.getElementById('contenu')) {
            tinymce.init({
                selector: '#contenu',
                license_key: 'gpl',
                height: 400,
                plugins: 'lists link image table code fullscreen media preview',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table | code fullscreen preview',
                menubar: false,
                branding: false,
                promotion: false,
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; }',
                image_advtab: true,
                valid_elements: '*[*]',
                extended_valid_elements: '*[*]',
                setup: function(editor) {
                    // Synchroniser le contenu avant la soumission du formulaire
                    editor.on('submit', function() {
                        editor.save();
                    });
                }
            });

            // Synchroniser TinyMCE avec le textarea avant soumission
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    tinymce.triggerSave();

                    // Valider que le contenu n'est pas vide
                    var contenu = document.getElementById('contenu');
                    if (contenu && contenu.value.trim() === '') {
                        e.preventDefault();
                        alert('Le contenu est requis.');
                        tinymce.get('contenu').focus();
                    }
                });
            });
        }
    </script>
</body>
</html>
