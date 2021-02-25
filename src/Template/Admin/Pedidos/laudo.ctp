<div style="padding: 20px;background-color: #fff;margin: 50px;" class="row">
    <div class="col-md-12">
        <?php echo $this->element('admin/pedido/laudo');?>
    </div>
</div>

<script>
 var pedido_id = "<?= $pedido->id; ?>"
</script>
<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/atendimento/pedido.js"></script>
