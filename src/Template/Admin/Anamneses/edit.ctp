<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Anamnese $anamnese
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $anamnese->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $anamnese->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Anamneses'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Pacientes'), ['controller' => 'Pacientes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Paciente'), ['controller' => 'Pacientes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="anamneses form large-9 medium-8 columns content">
    <?= $this->Form->create($anamnese) ?>
    <fieldset>
        <legend><?= __('Edit Anamnese') ?></legend>
        <?php
            echo $this->Form->control('paciente_id', ['options' => $pacientes, 'empty' => true]);
            echo $this->Form->control('gestante');
            echo $this->Form->control('medico_solicitante');
            echo $this->Form->control('medico_crm');
            echo $this->Form->control('data_coleta', ['empty' => true]);
            echo $this->Form->control('observacao');
            echo $this->Form->control('assinatura');
            echo $this->Form->control('data_primeiros_sintomas', ['empty' => true]);
            echo $this->Form->control('sintoma_febre');
            echo $this->Form->control('sintoma_mialgia_artralgia');
            echo $this->Form->control('sintoma_coriza');
            echo $this->Form->control('sintoma_congestao_nasal');
            echo $this->Form->control('sintoma_ganglios_linfaticos_aumentados');
            echo $this->Form->control('sintoma_sinais_de_cianose');
            echo $this->Form->control('sintoma_tosse');
            echo $this->Form->control('sintoma_diarreia');
            echo $this->Form->control('sintoma_irritabilidade_confusao');
            echo $this->Form->control('sintoma_congestao_conjuntival');
            echo $this->Form->control('sintoma_batimento_das_asas_nasais');
            echo $this->Form->control('sintoma_tiragem_intercostal');
            echo $this->Form->control('sintoma_cor_de_garganta');
            echo $this->Form->control('sintoma_nausea_vomitos');
            echo $this->Form->control('sintoma_adinamia_fraqueza');
            echo $this->Form->control('sintoma_dificildade_para_deglutir');
            echo $this->Form->control('sintoma_saturacao_de_o2_95');
            echo $this->Form->control('sintoma_dispneia');
            echo $this->Form->control('sintoma_dificuldade_de_respirar');
            echo $this->Form->control('sintoma_cefaleia_dor_de_cabeca');
            echo $this->Form->control('sintoma_producao_de_escarro');
            echo $this->Form->control('sintoma_manchas_vermelhas_pelo_corpo');
            echo $this->Form->control('sintoma_calafrios');
            echo $this->Form->control('sintoma_outros');
            echo $this->Form->control('sintoma_outros_observacao');
            echo $this->Form->control('analgesico_antitermico_antiinflamatorio');
            echo $this->Form->control('clinico_febre');
            echo $this->Form->control('clinico_febre_temp');
            echo $this->Form->control('clinico_coma');
            echo $this->Form->control('clinico_alteracao_na_radiologia_de_torax');
            echo $this->Form->control('clinico_exsudato');
            echo $this->Form->control('clinico_dispneia_taquipneia');
            echo $this->Form->control('clinico_convulsao');
            echo $this->Form->control('clinico_conjuntivite');
            echo $this->Form->control('clinico_alteracao_de_ausculta_pulmonar');
            echo $this->Form->control('clinico_outros');
            echo $this->Form->control('clinico_outros_observacao');
            echo $this->Form->control('morbidade_cardiovascular');
            echo $this->Form->control('morbidade_neurologica');
            echo $this->Form->control('morbidade_renal');
            echo $this->Form->control('morbidade_diabetes');
            echo $this->Form->control('morbidade_imunodeficiencia');
            echo $this->Form->control('morbidade_pulmonar');
            echo $this->Form->control('morbidade_hepatica');
            echo $this->Form->control('morbidade_hiv');
            echo $this->Form->control('morbidade_neoplasia');
            echo $this->Form->control('paciente_hospitalizado');
            echo $this->Form->control('paciente_hospitalizado_nome_hospital');
            echo $this->Form->control('data_internacao', ['empty' => true]);
            echo $this->Form->control('data_alta_hospitalar', ['empty' => true]);
            echo $this->Form->control('data_isolamento', ['empty' => true]);
            echo $this->Form->control('paciente_ventilacao_mecanica');
            echo $this->Form->control('paciente_situacao_notificacao');
            echo $this->Form->control('paciente_historico_viagem_14_dias');
            echo $this->Form->control('paciente_historico_viagem_14_dias_data_chegada', ['empty' => true]);
            echo $this->Form->control('paciente_coleta_de_amostra');
            echo $this->Form->control('paciente_his_deslocamento_14_dias');
            echo $this->Form->control('paciente_contato_pessoa_com_suspeita_covid');
            echo $this->Form->control('paciente_contato_pessoa_com_suspeita_covid_local');
            echo $this->Form->control('paciente_contato_pessoa_com_suspeita_covid_local_desc');
            echo $this->Form->control('paciente_contato_pessoa_com_confirmado_covid');
            echo $this->Form->control('paciente_contato_pessoa_com_confirmado_covid_caso_fonte');
            echo $this->Form->control('paciente_unidade_saude_14_dias');
            echo $this->Form->control('paciente_unidade_saude_14_dias_local');
            echo $this->Form->control('paciente_ocupacao');
            echo $this->Form->control('paciente_ocupacao_outros');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
