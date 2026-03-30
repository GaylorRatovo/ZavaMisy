    </main>

    <footer class="footer">
        <p class="footer__copyright">
            &copy; <?= date('Y') ?> <a href="/">Iran Conflit</a>. Tous droits réservés. ETU003361 - ETU003345
        </p>
    </footer>

    <!-- Swiper JS (chargé uniquement si nécessaire) -->
    <?php if (isset($hasMultipleImages) && $hasMultipleImages): ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

    <!-- Script d'initialisation Swiper -->
    <script defer>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser tous les carrousels sur la page
        const carousels = document.querySelectorAll('.article-carousel .swiper');
        if (carousels.length > 0 && typeof Swiper !== 'undefined') {
            carousels.forEach(function(carouselEl) {
                new Swiper(carouselEl, {
                    loop: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    keyboard: {
                        enabled: true
                    },
                    a11y: {
                        prevSlideMessage: 'Image précédente',
                        nextSlideMessage: 'Image suivante',
                        paginationBulletMessage: 'Aller à l\'image {{index}}'
                    }
                });
            });
        }
    });
    </script>
    <?php endif; ?>
</body>
</html>
