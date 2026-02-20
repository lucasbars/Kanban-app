@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('boards.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 fw-semibold" style="letter-spacing:-0.3px;">{{ $board->name }}</h5>
                <small class="text-muted">{{ $columns->count() }} coluna(s)</small>
            </div>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalColuna"
            onclick="abrirModalColuna()">
            <i class="bi bi-plus-lg me-1"></i> Nova Coluna
        </button>
    </div>

    <div class="d-flex gap-3 overflow-auto pb-4" id="kanban-board" style="min-height:70vh; align-items:flex-start;">
        @foreach ($columns as $column)
            <div class="kanban-column" style="min-width:290px; max-width:290px;">
                <div class="column-header d-flex justify-content-between align-items-center mb-2 px-1">
                    <div class="d-flex align-items-center gap-2">
                        <span class="column-dot"></span>
                        <span class="fw-semibold" style="font-size:0.875rem;">{{ $column->name }}</span>
                        <span class="task-count">{{ $column->tasks->count() }}</span>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="column-action-btn btn-add-task" data-column-id="{{ $column->id }}"
                            title="Adicionar task">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <button class="column-action-btn btn-edit-column" data-column-id="{{ $column->id }}"
                            data-name="{{ $column->name }}" title="Editar coluna">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="column-action-btn btn-delete-column" data-column-id="{{ $column->id }}"
                            title="Excluir coluna">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="task-list" id="column-{{ $column->id }}" data-column-id="{{ $column->id }}">
                    @foreach ($column->tasks as $task)
                        <div class="task-card" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="task-title">{{ $task->title }}</span>
                                <div class="d-flex gap-1">
                                    <button class="task-delete-btn btn-edit-task" data-task-id="{{ $task->id }}"
                                        data-column-id="{{ $column->id }}" data-title="{{ $task->title }}"
                                        data-description="{{ $task->description }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="task-delete-btn btn-delete-task" data-task-id="{{ $task->id }}">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($task->description)
                                <p class="task-desc mb-0">{{ $task->description }}</p>
                            @endif
                        </div>
                    @endforeach

                    <button class="add-task-inline btn-add-task" data-column-id="{{ $column->id }}">
                        <i class="bi bi-plus me-1"></i> Adicionar tarefa
                    </button>
                </div>
            </div>
        @endforeach

        @if ($columns->isEmpty())
            <div class="text-center w-100 py-5">
                <i class="bi bi-columns-gap" style="font-size:3rem; color:#d1d5db;"></i>
                <p class="mt-3 text-muted">Nenhuma coluna ainda. Crie uma para começar!</p>
            </div>
        @endif
    </div>

    <!-- Modal Coluna (criar e editar) -->
    <div class="modal fade" id="modalColuna" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalColunaTitulo">Nova Coluna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="column-id">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" id="column-name" placeholder="Ex: Em andamento">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary btn-sm" id="btn-salvar-coluna">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Task (criar e editar) -->
    <div class="modal fade" id="modalTask" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTaskTitulo">Nova Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="task-id">
                    <input type="hidden" id="task-column-id">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" id="task-title"
                            placeholder="Ex: Criar tela de login">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" id="task-description" rows="3" placeholder="Detalhes da tarefa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary btn-sm" id="btn-salvar-task">Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const BOARD_ID = {{ $board->id }};
    </script>
    <script src="{{ asset('js/kanban.js') }}"></script>
@endsection
