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
                headers: {
                    "X-CSRF-TOKEN": CSRF,
                },
                data: {
                    column_id: columnId,
                    order: order,
                },
            });
        },
    });
});

// Nova coluna
$("#btn-salvar-coluna").on("click", function () {
    const btn = $("#btn-salvar-coluna");
    btn.addClass("btn-loading").prop("disabled", true);
    $.ajax({
        url: "/boards/" + BOARD_ID + "/columns",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": CSRF,
        },
        data: {
            name: $("#column-name").val(),
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

// Abrir modal task
$(document).on("click", ".btn-add-task", function () {
    $("#task-column-id").val($(this).data("column-id"));
    $("#task-title").val("");
    $("#task-description").val("");
    new bootstrap.Modal($("#modalNovaTask")[0]).show();
});

// Salvar task
$("#btn-salvar-task").on("click", function () {
    const btn = $(this);
    btn.addClass("btn-loading").prop("disabled", true);
    const columnId = $("#task-column-id").val();
    $.ajax({
        url: "/columns/" + columnId + "/tasks",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": CSRF,
        },
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

// Deletar coluna
$(document).on("click", ".btn-delete-column", function () {
    if (!confirm("Deseja excluir esta coluna?")) return;
    const btn = $(this);
    btn.html('<i class="bi bi-hourglass-split"></i>').prop("disabled", true);
    const columnId = $(this).data("column-id");
    $.ajax({
        url: "/columns/" + columnId,
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": CSRF,
        },
        success: function () {
            location.reload();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            btn.html('<i class="bi bi-trash"></i>').prop("disabled", false);
        },
    });
});

// Deletar task
$(document).on("click", ".btn-delete-task", function () {
    if (!confirm("Deseja excluir esta task?")) return;
    const btn = $(this);
    btn.addClass("btn-loading").prop("disabled", true);
    const taskId = $(this).data("task-id");
    $.ajax({
        url: "/tasks/" + taskId,
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": CSRF,
        },
        success: function () {
            $("#task-" + taskId).remove();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            btn.removeClass("btn-loading").prop("disabled", false);
        },
    });
});
