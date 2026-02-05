<div
    id="{{ $id }}"
    class="fixed inset-0 bg-black/50 hidden z-50 flex items-start justify-center"
>
    <div class="relative mt-20 p-5 w-96 bg-white shadow-lg rounded">

        <h3 class="text-lg font-semibold text-center">
            {{ $title }}
        </h3>

        <p class="text-sm text-gray-600 text-center mt-2">
            {{ $message }}
        </p>

        <div class="mt-4 space-y-2">
            <button
                data-confirm
                class="w-full px-4 py-2 bg-gray-900 text-white hover:bg-black transition"
            >
                {{ $confirmText }}
            </button>

            <button
                data-cancel
                class="w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 transition"
            >
                {{ $cancelText }}
            </button>
        </div>
    </div>
</div>
<script>
    let confirmCallback = null;

    function openConfirmModal(modalId, callback) {
        const modal = document.getElementById(modalId);
        confirmCallback = callback;
        modal.classList.remove('hidden');
    }

    document.addEventListener('click', function (e) {

        if (e.target.matches('[data-confirm]')) {
            if (confirmCallback) confirmCallback();
            closeModal(e.target);
        }

        if (e.target.matches('[data-cancel]')) {
            closeModal(e.target);
        }

    });

    function closeModal(element) {
        const modal = element.closest('.fixed');
        modal.classList.add('hidden');
        confirmCallback = null;
    }
</script>
