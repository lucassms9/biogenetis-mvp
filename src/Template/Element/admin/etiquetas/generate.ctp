<div id="printer-code" class="row">
    <div  class="col-md-6" style="border: 1px solid;">
        <div id="content-barcode" class="row">
        </div>
    </div>
</div>

<input type="hidden" id="pedido-id" value="<?=$pedido->id?>">

<div style="margin-top: 10px" class="row">
    <div class="col-md-3">
        <?= $this->Form->button(__('Imprimir etiqueta'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light', 'type' => 'button', 'onclick' => 'printDiv()']) ?>
    </div>
</div>

