<?php if ($useForm) : ?>
    <?= $this->Form->create($anamnese) ?>
<?php endif; ?>


<div style="text-align: center;" class="group-title row">
    <div class="col-md-12">
        <h2>TESTE QUALITATIVO PARA CORONAVÍRUS COVID-19</h2>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-12">
        <h5>Selecione os sinais e sintomas apresentados</h5>
    </div>
</div>
<div style="font-size: 12px;" class="checkboxgerais">
    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_febre', ['label' => 'FEBRE', 'type' => 'checkbox', 'class' => ['custom-style-check',],'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_tosse', ['label' => 'TOSSE', 'type' => 'checkbox', 'class' => ['custom-style-check',],'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_corisa_congestao_nasal', ['label' => 'CORISA/CONGESTÃO NASAL', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_dor_no_corpo', ['label' => 'DOR NO CORPO', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_expectoracao', ['label' => 'EXPECTORAÇÃO', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_cefaleia_dor_de_cabeca', ['label' => 'DOR DE CABEÇA', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_adinamia_fraqueza', ['label' => 'FRAQUEZA', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_cansaco_falta_de_ar', ['label' => 'CANSAÇO / FALTA DE AR', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_diarreia', ['label' => 'DIARREIA', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_falta_de_apetite', ['label' => 'FALDA DE APETITE', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('sintoma_nausea_vomitos', ['label' => 'NÁUSEA/VÔMITOS', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>


    <div class="row mt20">
        <div class="col-md-12">
            <h5>Selecione as doenças associadas</h5>
        </div>
    </div>


    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_cardiovascular', ['label' => 'HIPERTENSÃO ARTERIAL', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('morbidade_diabetes', ['label' => 'DIABETES', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_alteracao_colesterol', ['label' => 'ALTERAÇÃO COLESTEROL/TRIGLICÉRIDES', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_obesidade', ['label' => 'OBESIDADE', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
    </div>

    <div class="row mt20">
        <div style="display: flex;" class="col-md-6">
            <?php echo $this->Form->control('clinico_doencas_reumatologicas', ['label' => 'DOENÇAS REUMATOLÓGICAS', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>

            <!-- <input style="width: 50%;margin: 0 12px;" id="clinico_doencas_reumatologicas_qual" type="text" class="form-control" name="clinico_doencas_reumatologicas_qual" <?= $disabled ? 'disabled' : '' ?>> -->

        </div>

        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_dpoc_enfisema', ['label' => 'DPOC (ENFISEMA)', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_asma', ['label' => 'ASMA', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>

    </div>

    <div class="row mt20">
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_rinite', ['label' => 'RINITE', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_apneia_do_sone', ['label' => 'APNEIA DO SONO', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>
        <div style="display: flex;" class="col-md-6">
            <?php echo $this->Form->control('clinico_neoplasia_cancer', ['label' => 'NEOPLASIA / CÂNCER', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>

            <!-- <input style="width: 50%;margin: 0 12px;" id="clinico_neoplasia_cancer_qual" type="text" class="form-control" name="clinico_neoplasia_cancer_qual" <?= $disabled ? 'disabled' : '' ?>> -->

        </div>
    </div>

    <div class="row mt20">

        <div class="col-md-3">
            <?php echo $this->Form->control('clinico_insuficiencia_renal', ['label' => 'INSUFICIÊNCIA RENAL', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
        </div>

        <div style="display: flex;" class="col-md-9">
            <?php echo $this->Form->control('clinico_doencas_hepaticas', ['label' => 'DOENÇAS HEPÁTICAS', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
            <!-- <input type="text" style="width: 50%;margin: 0 12px;" class="form-control" id="clinico_doencas_hepaticas_qual" name="clinico_doencas_hepaticas_qual" <?= $disabled ? 'disabled' : '' ?>> -->
        </div>

    </div>

    <div class="row mt20">

        <div style="display: flex;" class="col-md-6">
            <?php echo $this->Form->control('clinico_outros', ['label' => 'OUTROS:', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled]); ?>
            <input type="text" style="width: 50%;margin: 0 12px;" class="form-control" id="clinico_outros_observacao"
            value="<?= @$anamnese->clinico_outros_observacao; ?>"
            name="clinico_outros_observacao" <?= $disabled ? 'disabled' : '' ?>>
        </div>
    </div>

</div>


<div style="font-size: 12px;" class="checkboxgerais">


    <div class="row mt20">
        <div class="col-md-12">
            <div>PACIENTE TEM HISTÓRICO DE VIAGEM ATÉ 14 DIAS ANTES DO INÍCIO DOS SINTOMAS?</div>
        </div>

        <div style="display: flex;margin-top: 15px;" class="col-md-12">
            <?php echo $this->Form->control('viagem_brasil', ['label' => 'Brasil:', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled, 'style' => 'margin: 0px 15px;']); ?>

            <div style="margin: 0px 15px;">
                <?php echo $this->Form->control('viagem_brasil_estado', ['class' => 'form-control', 'options' => !empty($estados_viagem) ? $estados_viagem : $estados, 'empty' => 'Escolha', 'disabled' => $disabled, 'style' => 'margin: 0px 15px;']); ?>
            </div>
            <div style="margin: 0px 15px;">
                <?php echo $this->Form->control('viagem_brasil_cidade', ['class' => 'form-control', 'options' => $cidades_viagem, 'empty' => 'Escolha', 'disabled' => $disabled, 'style' => 'margin: 0px 15px;']); ?>
            </div>
        </div>

        <div style="display: flex;margin-top: 15px;" class="col-md-12">
            <?php echo $this->Form->control('viagem_exterior', ['label' => 'Exterior:', 'type' => 'checkbox', 'class' => ['custom-style-check',], 'disabled' => $disabled, 'style' => 'margin: 0px 15px;']); ?>
            <input type="text" style="width: 50%;margin: 0 12px;"

            value="<?= @$anamnese->viagem_exteriorobs_pais; ?>"

            class="form-control" id="viagem_exteriorobs_pais" name="viagem_exteriorobs_pais" <?= $disabled ? 'disabled' : '' ?>>
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
            ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled, 'required' => true]); ?>
            <?php echo $this->Form->radio('paciente_contato_pessoa_com_suspeita_covid_local', [
                ['value' => 'UNIDADE DE SAÚDE', 'text' => 'UNIDADE DE SAÚDE'],
                ['value' => 'DOMICÍLIO', 'text' => 'DOMICÍLIO'],
                ['value' => 'LOCAL DE TRABALHO', 'text' => 'LOCAL DE TRABALHO'],
                ['value' => 'DESCONHECIDO', 'text' => 'DESCONHECIDO'],
            ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled, 'required' => true]); ?>
        </div>
        <div class="col-md-12">
            <?php echo $this->Form->control('paciente_contato_pessoa_com_suspeita_covid_local_desc', ['label' => 'OUTRO, ESPECIFICAR:', 'class' => 'form-control', 'disabled' => $disabled, 'value' => @$anamnese->paciente_contato_pessoa_com_suspeita_covid_local_desc]); ?>
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
            ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled, 'required' => true]); ?>

            <input style="width: 30%;margin: 0 10px" type="text" class="form-control"
            value="<?= @$anamnese->paciente_contato_pessoa_com_confirmado_covid_caso_fonte; ?>"
            name="paciente_contato_pessoa_com_confirmado_covid_caso_fonte" <?= $disabled ? 'disabled' : '' ?>>
        </div>
    </div>

    <div class="row mt20">
        <div class="col-md-12">
            <span>ESTEVE EM UNIDADE DE SAÚDE NOS ÚLTIMOS 14 DIAS?(PRONTO SOCORRO; INTERNAÇÃO; UTI)</span>
        </div>
        <div style="display: flex; margin-top:15px" class="col-md-12">
            <?php echo $this->Form->radio('paciente_unidade_saude_14_dias', [
                ['value' => 'NÃO', 'text' => 'NÃO'],
                ['value' => 'NÃO SABE', 'text' => 'NÃO SABE'],
                ['value' => 'SIM', 'text' => 'SIM, INFORMAR NOME, ENDEREÇO E CONTATO:'],
            ], ['style' => 'margin: 0 10px;', 'disabled' => $disabled, 'required' => true]); ?>
        </div>

        <div style="display: flex; margin-top:15px" class="col-md-12">
            <div>
                <label>INFORMAR NOME DA UNIDADE</label>
                <input style="width: 100%;margin: 0 10px" type="text" class="form-control"
                value="<?= @$anamnese->paciente_unidade_saude_14_dias_local; ?>"
                name="paciente_unidade_saude_14_dias_local" <?= $disabled ? 'disabled' : '' ?>>
            </div>

            <div style="margin: 0px 15px;">
                <?php echo $this->Form->control('paciente_unidade_saude_14_dias_estado', ['class' => 'form-control', 'options' =>  !empty($estados_unidade) ? $estados_unidade : $estados, 'disabled' => $disabled, 'style' => 'margin: 0px 15px;']); ?>
            </div>
            <div style="margin: 0px 15px;">
                <?php echo $this->Form->control('paciente_unidade_saude_14_dias_cidade', ['class' => 'form-control', 'options' => $cidades_unidade, 'disabled' => $disabled, 'style' => 'margin: 0px 15px;']); ?>
            </div>
        </div>
    </div>
</div>

<?php if ($disabled) : ?>
    <?= $this->Form->end() ?>
<?php endif; ?>
