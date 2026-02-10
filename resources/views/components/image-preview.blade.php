<div>
    <!-- Imagen en el grid -->
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="w-full h-full object-cover cursor-pointer"
        onclick="openImagePreview('{{ $src }}')"
    >
</div>
<script>
    function openImagePreview(src) {
        const modal = document.getElementById('image-preview-modal');
        const img = document.getElementById('image-preview-img');

        img.src = src;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeImagePreview() {
        const modal = document.getElementById('image-preview-modal');
        const img = document.getElementById('image-preview-img');

        img.src = '';
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Cerrar con ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeImagePreview();
        }
    });

    // Cerrar clickeando fondo
    document.getElementById('image-preview-modal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeImagePreview();
        }
    });
</script>
