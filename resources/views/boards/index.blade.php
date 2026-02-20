@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-600 mb-0" style="font-weight:600; letter-spacing:-0.3px;">Meus Quadros</h4>
            <small class="text-muted">{{ $boards->count() }} quadro(s) criado(s)</small>
        </div>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBoard">
            <i class="bi bi-plus-lg me-1"></i> Novo Quadro
        </button>
    </div>

    <div class="row" id="boards-list">
        @forelse($boards as $board)
            <div class="col-md-4 mb-4" id="board-card-{{ $board->id }}">
                <div class="card h-100 board-card">
                    <div class="card-body">
                        <div class="board-icon mb-3">
                            <i class="bi bi-kanban-fill"></i>
                        </div>
                        <h5 class="fw-semibold mb-1" style="font-size:0.95rem;">{{ $board->name }}</h5>
                        <p class="text-muted mb-0" style="font-size:0.82rem;">{{ $board->description ?? 'Sem descrição' }}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ $board->created_at->diffForHumans() }}</small>
                        <div class="d-flex gap-2">
                            <a href="{{ route('boards.show', $board) }}" class="btn btn-primary btn-sm">
                                Abrir <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <button class="btn btn-sm btn-edit-board" data-id="{{ $board->id }}"
                                data-name="{{ $board->name }}" data-description="{{ $board->description }}"
                                style="border:1px solid #e8eaed; color:#6b7280; border-radius:8px; background:transparent; padding: 0.3rem 0.6rem;">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-delete-board" data-id="{{ $board->id }}"
                                style="border:1px solid #fee2e2; color:#ef4444; border-radius:8px; background:transparent; padding: 0.3rem 0.6rem;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state text-center py-5">
                    <i class="bi bi-kanban" style="font-size:3rem; color:#d1d5db;"></i>
                    <p class="mt-3 text-muted">Nenhum quadro criado ainda.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBoard">
                        Criar primeiro quadro
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modal Board (criar e editar) -->
    <div class="modal fade" id="modalBoard" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBoardTitulo">Novo Quadro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="board-id">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" id="board-name" placeholder="Ex: Projeto Website">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" id="board-description" rows="3" placeholder="Descreva o quadro..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary btn-sm" id="btn-salvar-board">Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const STORE_URL = "{{ route('boards.store') }}";
    </script>
    <script src="{{ asset('js/boards.js') }}"></script>
@endsection
