const CSRF = $('meta[name="csrf-token"]').attr("content");

// Drag and drop
document.querySelectorAll(".task-list").forEach(function (el) {
    new Sortable(el, {
        group: "tasks",
        animation: 150,
        draggable: ".task-card",
        onEnd: function (evt) {
            const taskId = evt.item.dataset.taskId;
            const columnId = evt.to.dataset.columnId;
            const order = [...evt.to.querySelectorAll(".task-card")].map(
                (el) => el.dataset.taskId,
            );
            $.ajax({
                url: "/tasks/" + taskId + "/move",
                method: "POST",
                headers: { "X-CSRF-TOKEN": CSRF },
                data: { column_id: columnId, order: order },
            });
        },
    });
});

// =====================
// COLUNAS
// =====================

// Abrir modal para CRIAR coluna
function abrirModalColuna() {
    $("#modalColunaTitulo").text("Nova Coluna");
    $("#column-id").val("");
    $("#column-name").val("");
}

// Abrir modal para EDITAR coluna
$(document).on("click", ".btn-edit-column", function () {
    const btn = $(this);
    $("#modalColunaTitulo").text("Editar Coluna");
    $("#column-id").val(btn.data("column-id"));
    $("#column-name").val(btn.data("name"));
    new bootstrap.Modal($("#modalColuna")[0]).show();
});

// Salvar coluna (criar ou editar)
$("#btn-salvar-coluna").on("click", function () {
    const btn = $("#btn-salvar-coluna");
    btn.addClass("btn-loading").prop("disabled", true);
    const id = $("#column-id").val();

    const isEditing = id !== "";
    const url = isEditing
        ? "/columns/" + id
        : "/boards/" + BOARD_ID + "/columns";
    const method = isEditing ? "PUT" : "POST";

    $.ajax({
        url: url,
        method: method,
        headers: { "X-CSRF-TOKEN": CSRF },
        data: { name: $("#column-name").val() },
        success: function () {
            location.reload();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            btn.removeClass("btn-loading").prop("disabled", false);
        },
    });
});

// Deletar coluna
$(document).on("click", ".btn-delete-column", function () {
    if (!confirm("Deseja excluir esta coluna?")) return;
    const btn = $(this);
    btn.html('<i class="bi bi-hourglass-split"></i>').prop("disabled", true);
    const columnId = btn.data("column-id");
    $.ajax({
        url: "/columns/" + columnId,
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": CSRF },
        success: function () {
            location.reload();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            btn.html('<i class="bi bi-trash"></i>').prop("disabled", false);
        },
    });
});

// =====================
// TASKS
// =====================

// Abrir modal para CRIAR task
$(document).on("click", ".btn-add-task", function () {
    $("#modalTaskTitulo").text("Nova Tarefa");
    $("#task-id").val("");
    $("#task-column-id").val($(this).data("column-id"));
    $("#task-title").val("");
    $("#task-description").val("");
    new bootstrap.Modal($("#modalTask")[0]).show();
});

// Abrir modal para EDITAR task
$(document).on("click", ".btn-edit-task", function () {
    const btn = $(this);
    $("#modalTaskTitulo").text("Editar Tarefa");
    $("#task-id").val(btn.data("task-id"));
    $("#task-column-id").val(btn.data("column-id"));
    $("#task-title").val(btn.data("title"));
    $("#task-description").val(btn.data("description"));
    new bootstrap.Modal($("#modalTask")[0]).show();
});

// Salvar task (criar ou editar)
$("#btn-salvar-task").on("click", function () {
    const btn = $("#btn-salvar-task");
    btn.addClass("btn-loading").prop("disabled", true);
    const id = $("#task-id").val();

    const isEditing = id !== "";
    const columnId = $("#task-column-id").val();
    const url = isEditing ? "/tasks/" + id : "/columns/" + columnId + "/tasks";
    const method = isEditing ? "PUT" : "POST";

    $.ajax({
        url: url,
        method: method,
        headers: { "X-CSRF-TOKEN": CSRF },
        data: {
            title: $("#task-title").val(),
            description: $("#task-description").val(),
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

// Deletar task
$(document).on("click", ".btn-delete-task", function () {
    if (!confirm("Deseja excluir esta tarefa?")) return;
    const btn = $(this);
    btn.html('<i class="bi bi-hourglass-split"></i>').prop("disabled", true);
    const taskId = btn.data("task-id");
    const taskCard = $("#task-" + taskId);
    const columnList = taskCard.closest(".task-list");

    $.ajax({
        url: "/tasks/" + taskId,
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": CSRF },
        success: function () {
            taskCard.remove();

            // Atualiza o contador
            const columnId = columnList.data("column-id");
            const counter = $('[data-column-id="' + columnId + '"]')
                .closest(".kanban-column")
                .find(".task-count");
            const atual = parseInt(counter.text());
            counter.text(atual - 1);
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            btn.html('<i class="bi bi-x"></i>').prop("disabled", false);
        },
    });
});
