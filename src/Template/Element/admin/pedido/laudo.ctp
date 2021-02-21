<div class="row mb-3">
    <div class="col-md-12">
        <button style="background-color: #31b1fb;border-color: #31b1fb;" onclick="printerPage();" type="button" class="btn btn-secondary">
            <i style="margin-right: 5px;" class="mdi mdi-printer"></i>Imprimir
        </button>
       <?php if($pedido->status === 'Finalizado'):?>
        <button id="btn-send" style="background-color: #31b1fb;border-color: #31b1fb;" onclick="sendEmail();" type="button" class="btn btn-secondary">
            <i style="margin-right: 5px;" class="mdi mdi-mail"></i>
            <span id="btn-txt">Enviar por e-mail</span>
        </button>
        <?php endif; ?>
    </div>
</div>

<div style="font-size: 17px; background-color:#fff;" id="printer-laudo">
    <!-- HEADER -->
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <?php if(!empty($header_laudo)):?>
                    <img width="100%" src="<?= $header_laudo; ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <hr class="borderHr" />

    <div style="font-family: Arial;" class="row my-3">
        <div class="col-md-6 d-flex font-weight-bold">
            Nome: <?= $pedido->anamnese->paciente->nome; ?>
        </div>
        <div style="justify-content: flex-end;" class="col-md-6 d-flex">
            Data Nascimento: <?php $data ='';
                $data = explode('-',$pedido->anamnese->paciente->data_nascimento);
                $data = $data[2] . '/' . $data[1]. '/' . $data[0];
                echo $data;
            ?>
        </div>
    </div>

    <div style="font-family: Arial;" class="row my-3">
        <div class="col-md-6 d-flex">
            Data Entrada: <?= $pedido->anamnese->created; ?>
        </div>
        <div style="justify-content: flex-end;" class="col-md-6 d-flex">
            Data Emissão: <?= $pedido->exame->created; ?>
        </div>
    </div>


    <hr class="borderHr" />
    <!-- ANÁLISE LABORATORIAL -->
    <div style="font-family: Courier New;" class="row">
        <div class="col-md-12">
            <h3 class="">TRIAGEM BIOFOTÔNICA PARA O DIAGNÓSTICO DA COVID-19</h3>
        </div>
    </div>

    <div style="font-family: Courier New;" class="row my-3">
        <div class="col-md-6 d-flex">
            Material: <?= @$pedido->exame->amostra_tipo; ?>
        </div>
    </div>

    <div style="font-family: Courier New;" class="row my-3">
        <div class="col-md-6 d-flex">
            Método: <?= @$pedido->exame->equip_tipo; ?> com Inteligência Artificial.
        </div>
    </div>

    <div style="font-family: Courier New;" class="row my-3">
        <div style="border: 1px solid;padding: 10px;" class="col-md-4 d-flex flex-column">
            <div class="d-flex">
                <h3 style="margin: 0;" >
                    <span>RESULTADO:</span>
                    <span style="margin-left: 15px;"><?= @$pedido->exame->resultado; ?></span>
                </h3>
            </div>
        </div>
    </div>

    <div style="font-family: Courier New;" class="row my-3">
        <div class="col-md-12 d-flex flex-column">
            <div class="">
                <strong>INTERPRETAÇÃO DE RESULTADO</strong>

                <ul>
                    <li><span style="border-bottom: 2px solid;" class="font-weight-bold">Negativo</span> – Afasta a infecção por COVID-19.</li>

                    <li><span style="border-bottom: 2px solid;"  class="font-weight-bold">Positivo</span> <span class="font-weight-bold">+</span> <span style="border-bottom: 2px solid;"  class="font-weight-bold">Presença de Sintomas Gripais</span> (tosse, corisa nasal, dor no corpo, febre, dor de garganta, dor de cabeça, perda de olfato e/ou paladar) – Infecção por COVID-19 confirmada.</li>

                    <li><span style="border-bottom: 2px solid;"  class="font-weight-bold">Positivo</span><span class="font-weight-bold">+</span><span style="border-bottom: 2px solid;"  class="font-weight-bold">Ausência de Sintomas Gripais</span> – Recomendado repetir o exame em 3-5 dias ou prosseguir investigação com RT-PCR.</li>
                </ul>
            </div>

        </div>
    </div>

    <div style="font-family: Courier New;" class="row my-3">
        <div class="col-md-12">
            <div><strong>NOTA TÉCNICA PARA O TESTE DE TRIAGEM DA COVID-19:</strong></div>
            <div>
            Nesse exame de triagem, o método utilizado foi o ATR-FTIR (Reflexão Total Atenuada de Infravermelho com Transformada de Fourier), desenvolvido e validado em comparação ao teste molecular RT-PCR.
            </div>
        </div>
    </div>


    <div style="font-family: Courier New;" class="row my-3">
        <div class="col-md-12">
            <div><strong>OBSERVAÇÃO</strong></div>
            <div>
            O resultado negativo não exclui a possibilidade de infecção, havendo dependência da colheita adequada do espécime. Como qualquer teste diagnóstico, os resultados do ATR-FTIR com inteligência artificial devem ser interpretados conjuntamente com os achados clínicos e laboratoriais	relevantes. Os valores	dos testes laboratoriais sofrem influência de estados fisiológicos, uso de medicamentos, entre outros, que podem levar à resultados falso-positivos e falso-negativos. Vale ressaltar que, somente seu médico tem condições de interpretar os resultados alcançados em seu exame.
            </div>
        </div>
    </div>

    <div style="font-family: Courier New;" class="row my-3">
        <div style="justify-content: center;" class="col-md-12 d-flex">
            <?php if (!empty($pedido->exame->user->foto_assinatura_digital)) : ?>
                <img width="300px" src="<?= $this->Url->build('/', true); ?><?= $pedido->exame->user->foto_assinatura_digital ?>">
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="row">
            <div class="col-md-12">
                <?php if(!empty($footer_laudo)):?>
                    <img width="100%" src="<?= $footer_laudo; ?>">
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div>
