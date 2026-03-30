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
                license_key: 'gpl', // Remplace par ta clé
                height: 400,
                plugins: 'lists link image table code fullscreen media preview',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table | code fullscreen preview',
                menubar: false,
                branding: false,
                promotion: false,
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; }',
                // Configuration pour les images
                image_advtab: true,
                // Autoriser tout le HTML
                valid_elements: '*[*]',
                extended_valid_elements: '*[*]',
            });
        }
    </script>
</body>
</html>
