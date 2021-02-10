<div class="row mb-3">
    <div class="col-md-12">
        <button style="background-color: #31b1fb;border-color: #31b1fb;" onclick="printerPage();" type="button" class="btn btn-secondary">
            <i style="margin-right: 5px;" class="mdi mdi-printer"></i>Imprimir
        </button>
    </div>
</div>

<div style="font-size: 17px; background-color:#fff;" id="printer-laudo">
    <!-- HEADER -->
    <div class="row">
        <div class="col-md-12">
            <div class="">
                <?php if(!empty($header_laudo)):?>
                <img width="100%" src="<?= $this->Url->build('/', true); ?><?= @$header_laudo; ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <hr class=" borderHr" />
    <!-- INFORMACOES GERAIS -->
    <div class="row">
        <div class="col-md-12 d-flex container-title-printer">
            <h3 class="title-printer">INFORMAÇÕES GERAIS</h3>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-md-6 d-flex font-weight-bold">
            Nome: <?= $pedido->anamnese->paciente->nome; ?>
        </div>
        <div style="justify-content: flex-end;" class="col-md-6 d-flex">
            Data da Colheita: <?= $pedido->anamnese->created; ?>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-6 d-flex">
            Médico: <?= $pedido->anamnese->medico_solicitante; ?>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-6 d-flex">
            Láb./Convênio: BIOCHEMIE BIOTECNOLOGIA SA
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-6 d-flex">
            N˚ Controle: <?= $pedido->codigo_pedido; ?>
        </div>
        <div style="justify-content: flex-end;" class="col-md-6 d-flex">
            Data Emissão: 20/10/1995
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-6 d-flex font-weight-bold">
            Exame: <?= @$pedido->exame->amostra->lote; ?>
        </div>
    </div>
    <hr class="borderHr" />
    <!-- ANÁLISE LABORATORIAL -->
    <div class="row">
        <div class="col-md-12 d-flex container-title-printer">
            <h3 class="title-printer">ANÁLISE LABORATORIAL</h3>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-6 d-flex">
            Material: <?= @$pedido->exame->amostra_tipo; ?>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-6 d-flex">
            Método: <?= @$pedido->exame->equip_tipo; ?>
        </div>
    </div>

    <div style="background-color: #e5f1fd;margin-bottom: 90px !important;" class="row my-3">
        <div class="col-md-6 d-flex flex-column">
            <div class="d-flex">
                <h3>RESULTADO: <?= @$pedido->exame->resultado; ?></h3>
            </div>
            <div class="d-flex">
                <h5><?= $pedido->entrada_exame->nome; ?></h5>
            </div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-12 d-flex flex-column">
            <div class="d-flex">
                <strong> INTERPRETAÇÃO DE RESULTADO</strong>
            </div>
            <div class="d-flex">
                <span style="display: contents;" class="font-weight-bold">Não Detectado: </span> ausência de curva de aplicação para os alvos pesquisado (N1 e N2) e presença de curva de amplificação do controle endógeno (gene RP humano).
            </div>
            <div class="d-flex">
                <strong style="margin-right: 5px">Detectado: </strong> presença de curva de amplificação específica para os alvos pesquisados (N1 e N2).
            </div>
            <div class="d-flex">
                <strong style="margin-right: 5px">Inconclusivo: </strong> presença de curva de amplificação somente em um dos alvos pesquisados (N1 ou N2).
            </div>
            <div class="d-flex">
                <strong style="margin-right: 5px">Abaixo do limite de detecção: </strong> ausência de curva de amplificação do controle endógeno ( gene RP humano).
            </div>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            Valor de Referência: Não Detectado
        </div>
    </div>
    <div class="row my-3">
        <div class="col-md-12 d-flex flex-column">
            <div><strong>NOTA TÉCNICA PARA LAUDOS CORONA VÍRUS</strong></div>
            <div><strong>- Nota 1: </strong>O método utilizado nesse exame foi realizado segundo protocolo desenvolvido e validado pelo Centro de Controle e Prevenção de Doenças dos EUA (CDC).</div>
            <div><strong>- Nota 2: </strong>O processo de eliminação do SARS-Cov-2 não está elucidado, apesar de estudos recentes sugerirem que a sensibilidade da PCR é maior a partir do terceiro dia de surgimento dos sintomas. Portanto, um resultado negativo na PCR
                não exclui completamente o diagnóstico, especialmente em pacientes assintomáticos ou em fase inicial da infecção. Em casos graves ou com gravidade progressiva, recomenda-se a coleta de nova amostra, preferencialmente do trato respiratório inferior.</div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-12">
            <div><strong>OBSERVAÇÃO</strong></div>
            <div>
                O resultado negativo não exclui a possibilidade de infecção, havendo dependência da colheita adequada ao espécime e da ausência de inibidores do teste. Como para qualquer teste diagnóstico, os resultados da PCR devem ser interpretados conjuntamente com os achados clínicos e laboratoriais relevantes. Os valores dos testes de laboratório sofrem influência de estados fisiológicos, uso de medicamentos, etc. Exames realizados pela PCR (Reação em Cadeia da Polimerase) podem apresentar resultados falso-positivos e falso-negativos. Vale ressaltar que, somente seu médico tem condições de interpretar os resultados alcançados em seu exame.
            </div>
        </div>
    </div>

    <div class="row my-3">
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
                    <img width="100%" src="<?= $this->Url->build('/', true); ?><?= @$footer_laudo; ?>">
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div>
