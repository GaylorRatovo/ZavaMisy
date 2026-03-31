    </main>

    <footer class="footer">
        <p class="footer__copyright">
            &copy; <?= date('Y') ?> <a href="/">Iran Conflit</a>. Tous droits réservés. ETU003361 - ETU003345
        </p>
    </footer>

    <!-- Swiper JS (charge uniquement si necessaire) -->
    <?php if (isset($hasMultipleImages) && $hasMultipleImages): ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <script src="/assets/js/bundle.min.js" defer></script>
    <?php endif; ?>
</body>
</html>
