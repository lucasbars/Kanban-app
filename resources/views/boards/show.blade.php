@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('boards.index') }}" class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <strong>{{ $board->name }}</strong>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovaColuna">
        <i class="bi bi-plus-lg"></i> Nova Coluna
    </button>
</div>

<div class="d-flex gap-3 overflow-auto pb-3" id="kanban-board">
    @foreach($columns as $column)
    <div class="kanban-column" style="min-width:280px; max-width:280px;">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <span>{{ $column->name }}</span>
                <div>
                    <button class="btn btn-sm text-white btn-add-task" data-column-id="{{ $column->id }}">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <button class="btn btn-sm text-white btn-delete-column" data-column-id="{{ $column->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body task-list p-2" id="column-{{ $column->id }}" data-column-id="{{ $column->id }}">
                @foreach($column->tasks as $task)
                <div class="card mb-2 task-card" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ $task->title }}</span>
                            <button class="btn btn-sm text-danger btn-delete-task" data-task-id="{{ $task->id }}">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @if($task->description)
                        <small class="text-muted">{{ $task->description }}</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal Nova Coluna -->
<div class="modal fade" id="modalNovaColuna" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Coluna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" id="column-name">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btn-salvar-coluna">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Task -->
<div class="modal fade" id="modalNovaTask" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="task-column-id">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" id="task-title">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" id="task-description" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btn-salvar-task">Salvar</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
const CSRF = $('meta[name="csrf-token"]').attr('content');
const BOARD_ID = {{ $board->id }};

// Drag and drop
document.querySelectorAll('.task-list').forEach(function (el) {
    new Sortable(el, {
        group: 'tasks',
        animation: 150,
        onEnd: function (evt) {
            const taskId = evt.item.dataset.taskId;
            const columnId = evt.to.dataset.columnId;
            const order = [...evt.to.children].map(el => el.dataset.taskId);

            $.ajax({
                url: '/tasks/' + taskId + '/move',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
                data: { column_id: columnId, order: order },
            });
        }
    });
});

// Nova coluna
$('#btn-salvar-coluna').on('click', function () {
    $.ajax({
        url: '/boards/' + BOARD_ID + '/columns',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        data: { name: $('#column-name').val() },
        success: function () { location.reload(); },
        error: function (xhr) { console.log(xhr.responseText); }
    });
});

// Abrir modal task
$(document).on('click', '.btn-add-task', function () {
    $('#task-column-id').val($(this).data('column-id'));
    $('#task-title').val('');
    $('#task-description').val('');
    new bootstrap.Modal($('#modalNovaTask')[0]).show();
});

// Salvar task
$('#btn-salvar-task').on('click', function () {
    const columnId = $('#task-column-id').val();
    $.ajax({
        url: '/columns/' + columnId + '/tasks',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        data: {
            title: $('#task-title').val(),
            description: $('#task-description').val(),
        },
        success: function () { location.reload(); },
        error: function (xhr) { console.log(xhr.responseText); }
    });
});

// Deletar coluna
$(document).on('click', '.btn-delete-column', function () {
    if (!confirm('Deseja excluir esta coluna?')) return;
    const columnId = $(this).data('column-id');
    $.ajax({
        url: '/columns/' + columnId,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
        success: function () { location.reload(); },
        error: function (xhr) { console.log(xhr.responseText); }
    });
});

// Deletar task
$(document).on('click', '.btn-delete-task', function () {
    if (!confirm('Deseja excluir esta task?')) return;
    const taskId = $(this).data('task-id');
    $.ajax({
        url: '/tasks/' + taskId,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
        success: function () { $('#task-' + taskId).remove(); },
        error: function (xhr) { console.log(xhr.responseText); }
    });
});
</script>
@endsection