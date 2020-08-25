
<div class="row">
    <div class="col-md-3">
        <?php echo $this->Form->control('croqui_tipo_id', ['class' => 'form-control', 'label' => 'Croqui Tipo', 'options' => $croqui_tipos, 'empty' => 'Escolha', 'disabled' => true, 'default' => @$croqui_tipo_id]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="table-croqui-pedido">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr id="theads">
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody id="tbodies">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

