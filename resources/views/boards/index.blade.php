@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2>Meus Quadros</h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoBoard">
    <i class="bi bi-plus-lg"></i> Novo Quadro
  </button>
</div>

<div class="row" id="boards-list">
  @foreach($boards as $board)
  <div class="col-md-4 mb-4" id="board-card-{{ $board->id }}">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">{{ $board->name }}</h5>
        <p class="card-text text-muted">{{ $board->description ?? 'Sem descrição' }}</p>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('boards.show', $board) }}" class="btn btn-sm btn-primary">
          <i class="bi bi-eye"></i> Abrir
        </a>
        <button class="btn btn-sm btn-danger btn-delete-board" data-id="{{ $board->id }}">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    </div>
  </div>
  @endforeach
</div>

<!-- Modal Novo Board -->
<div class="modal fade" id="modalNovoBoard" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Novo Quadro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nome</label>
          <input type="text" class="form-control" id="board-name">
        </div>
        <div class="mb-3">
          <label class="form-label">Descrição</label>
          <textarea class="form-control" id="board-description" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" id="btn-salvar-board">Salvar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const CSRF = $('meta[name="csrf-token"]').attr('content');

  // Criar board
  $('#btn-salvar-board').on('click', function() {
    $.ajax({
      url: '{{ route("boards.store") }}',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': CSRF
      },
      data: {
        name: $('#board-name').val(),
        description: $('#board-description').val(),
      },
      success: function() {
        location.reload();
      }
    });
  });

  // Deletar board
  $(document).on('click', '.btn-delete-board', function() {
    if (!confirm('Deseja excluir este quadro?')) return;
    const id = $(this).data('id');
    $.ajax({
      url: '/boards/' + id,
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': CSRF
      },
      success: function() {
        $('#board-card-' + id).remove();
      }
    });
  });
</script>
@endsection