<!-- Modal de sucesso de agendamento/aluguel -->
<div class="modal fade" id="modalSucessoAluguel" tabindex="-1" aria-labelledby="modalSucessoAluguelLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalSucessoAluguelLabel">Agendamento Concluído</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Seu agendamento foi realizado com sucesso!</p>
        <p>Por favor, passe na imobiliária para acertar os documentos e finalizar o processo.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script>
// Exibe o modal de sucesso se houver mensagem de sucesso
window.addEventListener('DOMContentLoaded', function() {
  var flashType = <?php echo json_encode($flash['type'] ?? ''); ?>;
  if (flashType === 'success') {
    var modal = new bootstrap.Modal(document.getElementById('modalSucessoAluguel'));
    modal.show();
  }
});
</script>
