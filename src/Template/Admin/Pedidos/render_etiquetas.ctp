
<div class="page-content-wrapper">
    <div class="container-fluid">
    <div class="col-xl-12">
        <div class="card">
        <div class="card-body">

            <div style="margin-top: 10px" class="row">
                <div style="padding: 0;" class="col-md-3 mb-2">
                    <button style="background-color: #31b1fb;border-color: #31b1fb;"  onclick="printDiv();" type="button" class="btn btn-secondary">
                        <i style="margin-right: 5px;" class="mdi mdi-printer"></i>Imprimir etiquetas
                    </button>
                </div>
            </div>

            <div id="printer-code" class="row">
                <div  class="col-md-6" >
                    <div id="content-barcode"></div>
                </div>
            </div>
        </div>
        </div>

    </div>
    </div>
</div>

<script>
    var pedidos = <?= json_encode($barcodes); ?>;
</script>
<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/pedidos/render_etiquetas.js"></script>
