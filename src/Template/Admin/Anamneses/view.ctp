<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Anamnese $anamnese
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Anamnese'), ['action' => 'edit', $anamnese->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Anamnese'), ['action' => 'delete', $anamnese->id], ['confirm' => __('Are you sure you want to delete # {0}?', $anamnese->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Anamneses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Anamnese'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Pacientes'), ['controller' => 'Pacientes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Paciente'), ['controller' => 'Pacientes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="anamneses view large-9 medium-8 columns content">
    <h3><?= h($anamnese->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Paciente') ?></th>
            <td><?= $anamnese->has('paciente') ? $this->Html->link($anamnese->paciente->id, ['controller' => 'Pacientes', 'action' => 'view', $anamnese->paciente->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Medico Solicitante') ?></th>
            <td><?= h($anamnese->medico_solicitante) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Medico Crm') ?></th>
            <td><?= h($anamnese->medico_crm) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Outros Observacao') ?></th>
            <td><?= h($anamnese->sintoma_outros_observacao) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Febre Temp') ?></th>
            <td><?= h($anamnese->clinico_febre_temp) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Outros Observacao') ?></th>
            <td><?= h($anamnese->clinico_outros_observacao) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Hospitalizado') ?></th>
            <td><?= h($anamnese->paciente_hospitalizado) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Hospitalizado Nome Hospital') ?></th>
            <td><?= h($anamnese->paciente_hospitalizado_nome_hospital) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Ventilacao Mecanica') ?></th>
            <td><?= h($anamnese->paciente_ventilacao_mecanica) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Situacao Notificacao') ?></th>
            <td><?= h($anamnese->paciente_situacao_notificacao) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Historico Viagem 14 Dias') ?></th>
            <td><?= h($anamnese->paciente_historico_viagem_14_dias) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Coleta De Amostra') ?></th>
            <td><?= h($anamnese->paciente_coleta_de_amostra) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Contato Pessoa Com Suspeita Covid') ?></th>
            <td><?= h($anamnese->paciente_contato_pessoa_com_suspeita_covid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Contato Pessoa Com Suspeita Covid Local') ?></th>
            <td><?= h($anamnese->paciente_contato_pessoa_com_suspeita_covid_local) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Contato Pessoa Com Suspeita Covid Local Desc') ?></th>
            <td><?= h($anamnese->paciente_contato_pessoa_com_suspeita_covid_local_desc) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Contato Pessoa Com Confirmado Covid') ?></th>
            <td><?= h($anamnese->paciente_contato_pessoa_com_confirmado_covid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Contato Pessoa Com Confirmado Covid Caso Fonte') ?></th>
            <td><?= h($anamnese->paciente_contato_pessoa_com_confirmado_covid_caso_fonte) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Unidade Saude 14 Dias') ?></th>
            <td><?= h($anamnese->paciente_unidade_saude_14_dias) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Unidade Saude 14 Dias Local') ?></th>
            <td><?= h($anamnese->paciente_unidade_saude_14_dias_local) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Ocupacao') ?></th>
            <td><?= h($anamnese->paciente_ocupacao) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Ocupacao Outros') ?></th>
            <td><?= h($anamnese->paciente_ocupacao_outros) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($anamnese->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Coleta') ?></th>
            <td><?= h($anamnese->data_coleta) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Primeiros Sintomas') ?></th>
            <td><?= h($anamnese->data_primeiros_sintomas) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Internacao') ?></th>
            <td><?= h($anamnese->data_internacao) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Alta Hospitalar') ?></th>
            <td><?= h($anamnese->data_alta_hospitalar) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Isolamento') ?></th>
            <td><?= h($anamnese->data_isolamento) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paciente Historico Viagem 14 Dias Data Chegada') ?></th>
            <td><?= h($anamnese->paciente_historico_viagem_14_dias_data_chegada) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gestante') ?></th>
            <td><?= $anamnese->gestante ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Febre') ?></th>
            <td><?= $anamnese->sintoma_febre ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Mialgia Artralgia') ?></th>
            <td><?= $anamnese->sintoma_mialgia_artralgia ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Coriza') ?></th>
            <td><?= $anamnese->sintoma_coriza ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Congestao Nasal') ?></th>
            <td><?= $anamnese->sintoma_congestao_nasal ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Ganglios Linfaticos Aumentados') ?></th>
            <td><?= $anamnese->sintoma_ganglios_linfaticos_aumentados ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Sinais De Cianose') ?></th>
            <td><?= $anamnese->sintoma_sinais_de_cianose ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Tosse') ?></th>
            <td><?= $anamnese->sintoma_tosse ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Diarreia') ?></th>
            <td><?= $anamnese->sintoma_diarreia ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Irritabilidade Confusao') ?></th>
            <td><?= $anamnese->sintoma_irritabilidade_confusao ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Congestao Conjuntival') ?></th>
            <td><?= $anamnese->sintoma_congestao_conjuntival ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Batimento Das Asas Nasais') ?></th>
            <td><?= $anamnese->sintoma_batimento_das_asas_nasais ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Tiragem Intercostal') ?></th>
            <td><?= $anamnese->sintoma_tiragem_intercostal ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Cor De Garganta') ?></th>
            <td><?= $anamnese->sintoma_cor_de_garganta ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Nausea Vomitos') ?></th>
            <td><?= $anamnese->sintoma_nausea_vomitos ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Adinamia Fraqueza') ?></th>
            <td><?= $anamnese->sintoma_adinamia_fraqueza ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Dificildade Para Deglutir') ?></th>
            <td><?= $anamnese->sintoma_dificildade_para_deglutir ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Saturacao De O2 95') ?></th>
            <td><?= $anamnese->sintoma_saturacao_de_o2_95 ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Dispneia') ?></th>
            <td><?= $anamnese->sintoma_dispneia ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Dificuldade De Respirar') ?></th>
            <td><?= $anamnese->sintoma_dificuldade_de_respirar ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Cefaleia Dor De Cabeca') ?></th>
            <td><?= $anamnese->sintoma_cefaleia_dor_de_cabeca ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Producao De Escarro') ?></th>
            <td><?= $anamnese->sintoma_producao_de_escarro ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Manchas Vermelhas Pelo Corpo') ?></th>
            <td><?= $anamnese->sintoma_manchas_vermelhas_pelo_corpo ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Calafrios') ?></th>
            <td><?= $anamnese->sintoma_calafrios ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sintoma Outros') ?></th>
            <td><?= $anamnese->sintoma_outros ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Analgesico Antitermico Antiinflamatorio') ?></th>
            <td><?= $anamnese->analgesico_antitermico_antiinflamatorio ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Febre') ?></th>
            <td><?= $anamnese->clinico_febre ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Coma') ?></th>
            <td><?= $anamnese->clinico_coma ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Alteracao Na Radiologia De Torax') ?></th>
            <td><?= $anamnese->clinico_alteracao_na_radiologia_de_torax ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Exsudato') ?></th>
            <td><?= $anamnese->clinico_exsudato ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Dispneia Taquipneia') ?></th>
            <td><?= $anamnese->clinico_dispneia_taquipneia ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Convulsao') ?></th>
            <td><?= $anamnese->clinico_convulsao ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Conjuntivite') ?></th>
            <td><?= $anamnese->clinico_conjuntivite ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Alteracao De Ausculta Pulmonar') ?></th>
            <td><?= $anamnese->clinico_alteracao_de_ausculta_pulmonar ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Clinico Outros') ?></th>
            <td><?= $anamnese->clinico_outros ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Cardiovascular') ?></th>
            <td><?= $anamnese->morbidade_cardiovascular ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Neurologica') ?></th>
            <td><?= $anamnese->morbidade_neurologica ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Renal') ?></th>
            <td><?= $anamnese->morbidade_renal ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Diabetes') ?></th>
            <td><?= $anamnese->morbidade_diabetes ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Imunodeficiencia') ?></th>
            <td><?= $anamnese->morbidade_imunodeficiencia ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Pulmonar') ?></th>
            <td><?= $anamnese->morbidade_pulmonar ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Hepatica') ?></th>
            <td><?= $anamnese->morbidade_hepatica ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Hiv') ?></th>
            <td><?= $anamnese->morbidade_hiv ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Morbidade Neoplasia') ?></th>
            <td><?= $anamnese->morbidade_neoplasia ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Observacao') ?></h4>
        <?= $this->Text->autoParagraph(h($anamnese->observacao)); ?>
    </div>
    <div class="row">
        <h4><?= __('Assinatura') ?></h4>
        <?= $this->Text->autoParagraph(h($anamnese->assinatura)); ?>
    </div>
    <div class="row">
        <h4><?= __('Paciente His Deslocamento 14 Dias') ?></h4>
        <?= $this->Text->autoParagraph(h($anamnese->paciente_his_deslocamento_14_dias)); ?>
    </div>
</div>
