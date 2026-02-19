const CSRF = $('meta[name="csrf-token"]').attr("content");

// Abrir modal para CRIAR
$('[data-bs-target="#modalBoard"]').on("click", function () {
    $("#modalBoardTitulo").text("Novo Quadro");
    $("#board-id").val("");
    $("#board-name").val("");
    $("#board-description").val("");
});

// Abrir modal para EDITAR
$(document).on("click", ".btn-edit-board", function () {
    const btn = $(this);
    $("#modalBoardTitulo").text("Editar Quadro");
    $("#board-id").val(btn.data("id"));
    $("#board-name").val(btn.data("name"));
    $("#board-description").val(btn.data("description"));
    new bootstrap.Modal($("#modalBoard")[0]).show();
});

// Salvar (criar ou editar)
$("#btn-salvar-board").on("click", function () {
    const btn = $("#btn-salvar-board");
    btn.addClass("btn-loading").prop("disabled", true);
    const id = $("#board-id").val();

    const isEditing = id !== "";
    const url = isEditing ? "/boards/" + id : STORE_URL;
    const method = isEditing ? "PUT" : "POST";

    $.ajax({
        url: url,
        method: method,
        headers: { "X-CSRF-TOKEN": CSRF },
        data: {
            name: $("#board-name").val(),
            description: $("#board-description").val(),
        },
        success: function () {
            location.reload();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            btn.removeClass("btn-loading").prop("disabled", false);
        },
    });
});

$(document).on("click", ".btn-delete-board", function () {
    if (!confirm("Deseja excluir este quadro?")) return;
    const btn = $(this);
    const id = btn.data("id");
    btn.html('<i class="bi bi-hourglass-split"></i>').prop("disabled", true);
    $.ajax({
        url: "/boards/" + id,
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": CSRF },
        success: function () {
            $("#board-card-" + id).remove();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            btn.html('<i class="bi bi-trash"></i>').prop("disabled", false);
        },
    });
});
