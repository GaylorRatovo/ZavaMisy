    </main>

    <footer class="footer">
        <p>Iran Conflit &copy; <?= date('Y') ?></p>
    </footer>

    <!-- TinyMCE -->
    <script src="/js/tinymce/tinymce.min.js" defer></script>
    <script defer>
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
                content_style: 'body { font-family: "Libre Baskerville", Georgia, "Times New Roman", serif; font-size: 15px; line-height: 1.7; color: #2d2d2d; } h1, h2, h3, h4, h5, h6 { font-family: "Cormorant Garamond", Georgia, serif; color: #1a2f4e; } img { max-width: 100%; height: auto; display: block; margin: 1rem 0; border-radius: 4px; } figure { max-width: 100%; margin: 1rem 0; } figure img { width: 100%; } figcaption { font-size: 0.85rem; color: #6b6b6b; text-align: center; margin-top: 0.5rem; font-style: italic; }',
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
