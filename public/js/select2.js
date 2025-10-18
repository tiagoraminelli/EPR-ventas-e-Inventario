$(document).ready(function () {
    // Inicializar Select2 en los selectores de categoría y marca
    $("#categoria-select").select2({
        placeholder: "Seleccionar Categoría",
        allowClear: true,
        theme: "default",
    });

    $("#marca-select").select2({
        placeholder: "Seleccionar Marca",
        allowClear: true,
        theme: "default",
    });

    // Lógica para el modal de confirmación de eliminación
    const confirmModal = document.getElementById("confirm-modal");
    const confirmBtn = document.getElementById("confirm-btn");
    const cancelBtn = document.getElementById("cancel-btn");
    const confirmMessage = document.getElementById("confirm-message");
    let formToSubmit = null;

    document.querySelectorAll(".delete-form").forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            formToSubmit = this;
            confirmModal.classList.remove("hidden");
        });
    });

    confirmBtn.addEventListener("click", function () {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });

    cancelBtn.addEventListener("click", function () {
        confirmModal.classList.add("hidden");
    });
});
