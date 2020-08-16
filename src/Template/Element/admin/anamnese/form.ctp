
<?php if ($useForm):?>
<?= $this->Form->create($anamnese) ?>
<?php endif;?>

<div class="row mt20">
        <div class="col-md-2">
            <?php echo $this->Form->control('gestante',['label' => 'Está gestante?','type' => 'checkbox', 'disabled' => $disabled, 'class' => ['custom-style-check']]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-4">
            <?php echo $this->Form->control('medico_solicitante',['label' => 'Médico solicitante', 'class' => ['form-control'], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-4">
            <?php echo $this->Form->control('medico_crm',['label' => 'Médico CRM', 'class' => ['form-control'], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div style="text-align: center;" class="group-title row">
        <div class="col-md-12">
            <h4>TESTE SOLICITADO</h4>
            <h2>TESTE QUALITATIVO PARA CORONAVÍRUS COVID-19</h2>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-4">
            <?php echo $this->Form->control('data_primeiros_sintomas',['label' => 'Data dos primeiros sintomas','type' => 'text', 'class' => ['form-control'], 'disabled' => $disabled, 'required' => true]); ?>
        </div>
    </div>


    <div class="row mt20">
        <div class="col-md-12">
            <h5>Selecione os sintomas apresentados</h5>
        </div>
    </div>
    <div style="font-size: 12px;" class="checkboxgerais">
    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_febre',['label' => 'FEBRE','type' => 'checkbox', 'class' => ['custom-style-check',], ]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_tosse',['label' => 'TOSSE','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_cor_de_garganta',['label' => 'DOR DE GARGANTA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_dificuldade_de_respirar',['label' => 'DIFICULDADE DE RESPIRAR','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_mialgia_artralgia',['label' => 'MIALGIA/ARTRALGIA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_diarreia',['label' => 'DIARREIA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_nausea_vomitos',['label' => 'NÁUSEA/VÔMITOS','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_cefaleia_dor_de_cabeca',['label' => 'CEFALEIA (DOR DE CABEÇA)','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

        <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_coriza',['label' => 'CORIZA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_irritabilidade_confusao',['label' => 'IRRITABILIDADE/CONFUSÃO','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_adinamia_fraqueza',['label' => 'ADINAMIA (FRAQUEZA)','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_producao_de_escarro',['label' => 'PRODUÇÃO DE ESCARRO','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

        <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_congestao_nasal',['label' => 'CONGESTÃO NASAL','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_congestao_conjuntival',['label' => 'CONGESTÃO CONJUNTIVAL','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_dificildade_para_deglutir',['label' => 'DIFICULDADE PARA DEGLUTIR','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_manchas_vermelhas_pelo_corpo',['label' => 'MANCHAS VERMELHAS PELO CORPO','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_ganglios_linfaticos_aumentados',['label' => 'GÂNGLIOS LINFÁTICOS AUMENTADOS','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_batimento_das_asas_nasais',['label' => 'BATIMENTO DAS ASAS NASAIS','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_saturacao_de_o2_95',['label' => 'SATURAÇÃO DE O2 < 95%','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_calafrios',['label' => 'CALAFRIOS','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_sinais_de_cianose',['label' => 'SINAIS DE CIANOSE','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_tiragem_intercostal',['label' => 'TIRAGEM INTERCOSTAL','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_dispneia',['label' => 'DISPNEIA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_outros',['label' => 'OUTROS, ESPECIFICAR:','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" id="sintoma_outros_observacao" name="sintoma_outros_observacao" <?= $disabled ? 'disabled' : '' ?> >
        </div>
    </div>
</div>


<div class="row mt20">
    <div style="display: flex;" class="col-md-12">
        <span style="margin-right: 15px;">O PACIENTE UTILIZOU ANALGÉSICO, ANTITÉRMICO OU ANTI-INFLAMATÓRIO?</span>

        <?php echo $this->Form->radio('analgesico_antitermico_antiinflamatorio', ['SIM', 'NÃO'], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
    </div>
</div>



<div class="row mt20">
    <div class="col-md-12">
        <span>SELECIONE OS SINAIS CLÍNICOS OBSERVADOS:</span>
    </div>
</div>
<div style="font-size: 12px;" class="checkboxgerais">
    <div class="row mt20">
        <div style="display: flex;" class="col-md-3">
            <?php echo $this->Form->control('clinico_febre',['label' => 'FEBRE - TEMPERATURA DE:','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
                    <input style="width: 20%;margin: 0 12px;" id="clinico_febre_temp" type="text" class="form-control" name="clinico_febre_temp" <?= $disabled ? 'disabled' : '' ?>>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_exsudato',['label' => 'EXSUDATO FARÍNGEO','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_convulsao',['label' => 'CONVULSÃO','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_conjuntivite',['label' => 'CONJUNTIVITE','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>
    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_coma',['label' => 'COMA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_dispneia_taquipneia',['label' => 'DISPNEIA/TAQUIPNEIA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->Form->control('clinico_alteracao_de_ausculta_pulmonar',['label' => 'ALTERAÇÃO DE AUSCULTA PULMONAR','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>
        <div class="row mt20">
        <div class="col-md-4">
            <?php echo $this->Form->control('clinico_alteracao_na_radiologia_de_torax',['label' => 'ALTERAÇÃO NA RADIOLOGIA DE TÓRAX','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div style="display: flex;" class="col-md-8">
            <?php echo $this->Form->control('clinico_outros',['label' => 'OUTROS, ESPECIFICAR:','type' => 'checkbox', 'class' => ['custom-style-check'], 'disabled' => $disabled]); ?>
                <input style="width: 60%;margin: 0 10px" type="text" id="clinico_outros_observacao" class="form-control" name="clinico_outros_observacao" <?= $disabled ? 'disabled' : '' ?>>
        </div>
    </div>
</div>


<div class="row mt20">
    <div class="col-md-12">
        <span>MORBIDADES PRÉVIAS (SELECIONAR TODAS MORBIDADES PERTINENTES):</span>
    </div>
</div>
<div style="font-size: 12px;" class="checkboxgerais">
    <div class="row mt20">
        <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_cardiovascular',['label' => 'DOENÇA CARDIOVASCULAR, INCLUINDO HIPERTENSÃO','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_diabetes',['label' => 'DIABETES','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
            <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_hepatica',['label' => 'DOENÇA HEPÁTICA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>
        <div class="row mt20">
        <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_neurologica',['label' => 'DOENÇA NEUROLÓGICA CRÔNICA OU NEUROMUSCULAR','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_imunodeficiencia',['label' => 'IMUNODEFICIÊNCIA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
            <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_hiv',['label' => 'INFECÇÃO PELO HIV','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>
        <div class="row mt20">
        <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_renal',['label' => 'DOENÇA RENAL','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_pulmonar',['label' => 'DOENÇA PULMONAR CRÔNICA','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
            <div class="col-md-4">
            <?php echo $this->Form->control('morbidade_neoplasia',['label' => 'NEOPLASIA (TUMOR SÓLIDO OU HEMATOLÓGICO)','type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>


<div class="row mt20">
    <div style="display: flex;" class="col-md-12">
        <span style="margin-right: 15px;">PACIENTE FOI HOSPITALIZADO?</span>
        <?php echo $this->Form->radio('paciente_hospitalizado', [
            ['value' => 'NÃO', 'text' => 'NÃO'],
            ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
            ['value' => 'SIM', 'text' => 'SIM, NOME DO HOSPITAL DE INTENÇÃO'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
        <input style="width: 38%;margin: 0 10px" type="text" type="paciente_hospitalizado_nome_hospital" class="form-control" name="paciente_hospitalizado_nome_hospital" <?= $disabled ? 'disabled' : '' ?>>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-4">
        <?php echo $this->Form->control('data_internacao', ['label' => 'DATA DA INTERNAÇÃO','type' => 'text',  'class' => 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('data_alta_hospitalar', ['label' => 'DATA DA ALTA HOSPITALAR','type' => 'text', 'class' => 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('data_isolamento', ['label' => 'DATA DO ISOLAMENTO','type' => 'text', 'class' => 'form-control', 'disabled' => $disabled]); ?>
    </div>
</div>

<div class="row mt20">
    <div style="display: flex;" class="col-md-12">
        <span style="margin-right: 15px;">PACIENTE FOI SUBMETIDO A VENTILAÇÃO MECÂNICA?</span>
        <?php echo $this->Form->radio('paciente_ventilacao_mecanica', [
            ['value' => 'NÃO', 'text' => 'NÃO'],
            ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
            ['value' => 'SIM', 'text' => 'SIM'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
    </div>
</div>

<div class="row mt20">
    <div style="display: flex;" class="col-md-12">
        <div style="margin-right: 15px;">SITUAÇÃO DE SAÚDE DO PACIENTE NO MOMENTO DA NOTIFICAÇÃO:</div>
        <?php echo $this->Form->radio('paciente_situacao_notificacao', [
            ['value' => 'ÓBITO', 'text' => 'ÓBITO'],
            ['value' => 'CURA', 'text' => 'CURA'],
            ['value' => 'SINTOMÁTICO', 'text' => 'SINTOMÁTICO'],
            ['value' => 'IGNORADO', 'text' => 'IGNORADO'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
    </div>
</div>
<div class="row mt20">
    <div class="col-md-4">
        <div>PACIENTE TEM HISTÓRICO DE VIAGEM PARA FORA DO BRASIL ATÉ 14 DIAS ANTES DO INÍCIO DOS SINTOMAS?</div>
    </div>
    <div style="display: flex;" class="col-md-8">
        <?php echo $this->Form->radio('paciente_historico_viagem_14_dias', [
            ['value' => 'NÃO', 'text' => 'NÃO'],
            ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
            ['value' => 'SIM', 'text' => 'SIM, DATA DE CHEGADA AO BRASIL:'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
        <input style="width: 30%;margin: 0 10px" type="text" class="form-control" name="paciente_historico_viagem_14_dias_data_chegada" <?= $disabled ? 'disabled' : '' ?>>
    </div>
</div>

<div class="row mt20">
    <div style="display: flex;" class="col-md-12">
        <div style="margin-right: 15px;">FOI REALIZADA COLETA DE AMOSTRA DO PACIENTE?</div>
        <?php echo $this->Form->radio('paciente_coleta_de_amostra', [
            ['value' => 'NÃO', 'text' => 'NÃO'],
            ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
            ['value' => 'SIM', 'text' => 'SIM'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-12">
        <div>DESCRITIVO DO HISTÓRICO DE DESLOCAMENTO NOS 14 DIAS ANTERIORES AO INÍCIO DOS SINTOMAS:</div>
        <?php echo $this->Form->control('paciente_his_deslocamento_14_dias', ['class' => 'form-control','label' => '', 'disabled' => $disabled]); ?>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-12">
        <div>O PACIENTE TEVE CONTATO PRÓXIMO COM UMA PESSOA QUE SEJA CASO SUSPEITO DE NOVO CORONAVÍRUS (COVID-19)?</div>
    </div>
    <div class="col-md-12">
        <?php echo $this->Form->radio('paciente_contato_pessoa_com_suspeita_covid', [
            ['value' => 'NÃO', 'text' => 'NÃO'],
            ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
            ['value' => 'SIM', 'text' => 'SIM, ONDE:'],
        ], ['style' => 'margin: 0 10px;','disabled' => $disabled,'required' => true]); ?>
        <?php echo $this->Form->radio('paciente_contato_pessoa_com_suspeita_covid_local', [
            ['value' => 'UNIDADE DE SAÚDE', 'text' => 'UNIDADE DE SAÚDE'],
            ['value' => 'DOMICÍLIO', 'text' => 'DOMICÍLIO'],
            ['value' => 'LOCAL DE TRABALHO', 'text' => 'LOCAL DE TRABALHO'],
            ['value' => 'DESCONHECIDO', 'text' => 'DESCONHECIDO'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
    </div>
    <div class="col-md-12">
        <?php echo $this->Form->control('paciente_contato_pessoa_com_suspeita_covid_local_desc', ['label' => 'OUTRO, ESPECIFICAR:', 'class' => 'form-control', 'disabled' => $disabled,'required' => true]); ?>
    </div>
</div>

    <div class="row mt20">
    <div class="col-md-12">
        <div>O PACIENTE TEVE CONTATO PRÓXIMO COM UMA PESSOA QUE SEJA CASO CONFIRMADO DE NOVO CORONAVÍRUS (COVID-19)?</div>
    </div>
    <div style="display: flex;" class="col-md-12">
        <?php echo $this->Form->radio('paciente_contato_pessoa_com_confirmado_covid', [
            ['value' => 'NÃO', 'text' => 'NÃO'],
            ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
            ['value' => 'SIM', 'text' => 'SIM, NOME DO CASO FONTE:'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>

        <input style="width: 30%;margin: 0 10px" type="text" class="form-control" name="paciente_contato_pessoa_com_confirmado_covid_caso_fonte" <?= $disabled ? 'disabled' : '' ?>>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-4">
            <span>ESTEVE EM ALGUMA UNIDADE DE SAÚDE NOS 14 DIAS ANTES DO INÍCIO DOS SINTOMAS?</span>
    </div>
    <div style="display: flex;" class="col-md-8">
        <?php echo $this->Form->radio('paciente_unidade_saude_14_dias', [
            ['value' => 'NÃO', 'text' => 'NÃO'],
            ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
            ['value' => 'SIM', 'text' => 'SIM, INFORMAR NOME, ENDEREÇO E CONTATO:'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>

        <input style="width: 30%;margin: 0 10px" type="text" class="form-control" name="paciente_unidade_saude_14_dias_local" <?= $disabled ? 'disabled' : '' ?>>
    </div>
</div>

<div class="row mt20">
    <div style="display: flex;" class="col-md-12">
        <span>SUA OCUPAÇÃO:</span>
        <?php echo $this->Form->radio('paciente_ocupacao', [
            ['value' => 'PROFISSIONAL DE SAÚDE', 'text' => 'PROFISSIONAL DE SAÚDE'],
            ['value' => 'ESTUDANTE DA ÁREA DE SAÚDE', 'text' => 'ESTUDANTE DA ÁREA DE SAÚDE'],
            ['value' => 'PROFISSIONAL DE LABORATÓRIO', 'text' => 'PROFISSIONAL DE LABORATÓRIO'],
            ['value' => 'TRABALHA EM CONTATO COM ANIMAIS', 'text' => 'TRABALHA EM CONTATO COM ANIMAIS'],
            ['value' => 'OUTROS', 'text' => 'OUTROS, ESPECIFICAR'],
        ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled,'required' => true]); ?>
    </div>

    <div style="display: flex;margin-left: 96px;" class="col-md-12">

        <input style="width: 50%;margin: 0 10px" type="text" class="form-control" name="paciente_ocupacao_outros" <?= $disabled ? 'disabled' : '' ?>>
    </div>
</div>

    <div class="row mt20">
    <div class="col-md-12">
        <?php echo $this->Form->control('observacao', ['class' => 'form-control', 'label' => 'OBSERVAÇÃO GERAL', 'disabled' => $disabled]); ?>
    </div>
</div>
</div>

<?php if ($disabled):?>
    <?= $this->Form->end() ?>
<?php endif;?>
