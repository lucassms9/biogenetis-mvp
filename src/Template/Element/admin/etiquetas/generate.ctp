<div class="row">
    <div class="col-md-6">
       <div class="area_barcode">
            <div id="barcodeTarget" class="barcodeTarget"></div>
            <canvas id="canvasTarget" width="150" height="150"></canvas>
       </div>
    </div>
</div>

<div style="margin-top: 10px" class="row">
    <div class="col-md-3">
        <?= $this->Form->button(__('Imprimir etiqueta'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light', 'type' => 'button', 'onclick' => 'alert(1)']) ?>
    </div>
</div>

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/atendimento/pedido.js"></script>
