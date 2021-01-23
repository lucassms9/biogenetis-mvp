<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Anamnese[]|\Cake\Collection\CollectionInterface $anamneses
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Anamnese'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Pacientes'), ['controller' => 'Pacientes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Paciente'), ['controller' => 'Pacientes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="anamneses index large-9 medium-8 columns content">
    <h3><?= __('Anamneses') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gestante') ?></th>
                <th scope="col"><?= $this->Paginator->sort('medico_solicitante') ?></th>
                <th scope="col"><?= $this->Paginator->sort('medico_crm') ?></th>
                <th scope="col"><?= $this->Paginator->sort('data_coleta') ?></th>
                <th scope="col"><?= $this->Paginator->sort('data_primeiros_sintomas') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_febre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_mialgia_artralgia') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_coriza') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_congestao_nasal') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_ganglios_linfaticos_aumentados') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_sinais_de_cianose') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_tosse') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_diarreia') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_irritabilidade_confusao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_congestao_conjuntival') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_batimento_das_asas_nasais') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_tiragem_intercostal') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_cor_de_garganta') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_nausea_vomitos') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_adinamia_fraqueza') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_dificildade_para_deglutir') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_saturacao_de_o2_95') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_dispneia') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_dificuldade_de_respirar') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_cefaleia_dor_de_cabeca') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_producao_de_escarro') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_manchas_vermelhas_pelo_corpo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_calafrios') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_outros') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sintoma_outros_observacao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('analgesico_antitermico_antiinflamatorio') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_febre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_febre_temp') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_coma') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_alteracao_na_radiologia_de_torax') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_exsudato') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_dispneia_taquipneia') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_convulsao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_conjuntivite') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_alteracao_de_ausculta_pulmonar') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_outros') ?></th>
                <th scope="col"><?= $this->Paginator->sort('clinico_outros_observacao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_cardiovascular') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_neurologica') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_renal') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_diabetes') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_imunodeficiencia') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_pulmonar') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_hepatica') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_hiv') ?></th>
                <th scope="col"><?= $this->Paginator->sort('morbidade_neoplasia') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_hospitalizado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_hospitalizado_nome_hospital') ?></th>
                <th scope="col"><?= $this->Paginator->sort('data_internacao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('data_alta_hospitalar') ?></th>
                <th scope="col"><?= $this->Paginator->sort('data_isolamento') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_ventilacao_mecanica') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_situacao_notificacao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_historico_viagem_14_dias') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_historico_viagem_14_dias_data_chegada') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_coleta_de_amostra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_contato_pessoa_com_suspeita_covid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_contato_pessoa_com_suspeita_covid_local') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_contato_pessoa_com_suspeita_covid_local_desc') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_contato_pessoa_com_confirmado_covid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_contato_pessoa_com_confirmado_covid_caso_fonte') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_unidade_saude_14_dias') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_unidade_saude_14_dias_local') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_ocupacao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('paciente_ocupacao_outros') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($anamneses as $anamnese): ?>
            <tr>
                <td><?= $this->Number->format($anamnese->id) ?></td>
                <td><?= $anamnese->has('paciente') ? $this->Html->link($anamnese->paciente->id, ['controller' => 'Pacientes', 'action' => 'view', $anamnese->paciente->id]) : '' ?></td>
                <td><?= h($anamnese->gestante) ?></td>
                <td><?= h($anamnese->medico_solicitante) ?></td>
                <td><?= h($anamnese->medico_crm) ?></td>
                <td><?= h($anamnese->data_coleta) ?></td>
                <td><?= h($anamnese->data_primeiros_sintomas) ?></td>
                <td><?= h($anamnese->sintoma_febre) ?></td>
                <td><?= h($anamnese->sintoma_mialgia_artralgia) ?></td>
                <td><?= h($anamnese->sintoma_coriza) ?></td>
                <td><?= h($anamnese->sintoma_congestao_nasal) ?></td>
                <td><?= h($anamnese->sintoma_ganglios_linfaticos_aumentados) ?></td>
                <td><?= h($anamnese->sintoma_sinais_de_cianose) ?></td>
                <td><?= h($anamnese->sintoma_tosse) ?></td>
                <td><?= h($anamnese->sintoma_diarreia) ?></td>
                <td><?= h($anamnese->sintoma_irritabilidade_confusao) ?></td>
                <td><?= h($anamnese->sintoma_congestao_conjuntival) ?></td>
                <td><?= h($anamnese->sintoma_batimento_das_asas_nasais) ?></td>
                <td><?= h($anamnese->sintoma_tiragem_intercostal) ?></td>
                <td><?= h($anamnese->sintoma_cor_de_garganta) ?></td>
                <td><?= h($anamnese->sintoma_nausea_vomitos) ?></td>
                <td><?= h($anamnese->sintoma_adinamia_fraqueza) ?></td>
                <td><?= h($anamnese->sintoma_dificildade_para_deglutir) ?></td>
                <td><?= h($anamnese->sintoma_saturacao_de_o2_95) ?></td>
                <td><?= h($anamnese->sintoma_dispneia) ?></td>
                <td><?= h($anamnese->sintoma_dificuldade_de_respirar) ?></td>
                <td><?= h($anamnese->sintoma_cefaleia_dor_de_cabeca) ?></td>
                <td><?= h($anamnese->sintoma_producao_de_escarro) ?></td>
                <td><?= h($anamnese->sintoma_manchas_vermelhas_pelo_corpo) ?></td>
                <td><?= h($anamnese->sintoma_calafrios) ?></td>
                <td><?= h($anamnese->sintoma_outros) ?></td>
                <td><?= h($anamnese->sintoma_outros_observacao) ?></td>
                <td><?= h($anamnese->analgesico_antitermico_antiinflamatorio) ?></td>
                <td><?= h($anamnese->clinico_febre) ?></td>
                <td><?= h($anamnese->clinico_febre_temp) ?></td>
                <td><?= h($anamnese->clinico_coma) ?></td>
                <td><?= h($anamnese->clinico_alteracao_na_radiologia_de_torax) ?></td>
                <td><?= h($anamnese->clinico_exsudato) ?></td>
                <td><?= h($anamnese->clinico_dispneia_taquipneia) ?></td>
                <td><?= h($anamnese->clinico_convulsao) ?></td>
                <td><?= h($anamnese->clinico_conjuntivite) ?></td>
                <td><?= h($anamnese->clinico_alteracao_de_ausculta_pulmonar) ?></td>
                <td><?= h($anamnese->clinico_outros) ?></td>
                <td><?= h($anamnese->clinico_outros_observacao) ?></td>
                <td><?= h($anamnese->morbidade_cardiovascular) ?></td>
                <td><?= h($anamnese->morbidade_neurologica) ?></td>
                <td><?= h($anamnese->morbidade_renal) ?></td>
                <td><?= h($anamnese->morbidade_diabetes) ?></td>
                <td><?= h($anamnese->morbidade_imunodeficiencia) ?></td>
                <td><?= h($anamnese->morbidade_pulmonar) ?></td>
                <td><?= h($anamnese->morbidade_hepatica) ?></td>
                <td><?= h($anamnese->morbidade_hiv) ?></td>
                <td><?= h($anamnese->morbidade_neoplasia) ?></td>
                <td><?= h($anamnese->paciente_hospitalizado) ?></td>
                <td><?= h($anamnese->paciente_hospitalizado_nome_hospital) ?></td>
                <td><?= h($anamnese->data_internacao) ?></td>
                <td><?= h($anamnese->data_alta_hospitalar) ?></td>
                <td><?= h($anamnese->data_isolamento) ?></td>
                <td><?= h($anamnese->paciente_ventilacao_mecanica) ?></td>
                <td><?= h($anamnese->paciente_situacao_notificacao) ?></td>
                <td><?= h($anamnese->paciente_historico_viagem_14_dias) ?></td>
                <td><?= h($anamnese->paciente_historico_viagem_14_dias_data_chegada) ?></td>
                <td><?= h($anamnese->paciente_coleta_de_amostra) ?></td>
                <td><?= h($anamnese->paciente_contato_pessoa_com_suspeita_covid) ?></td>
                <td><?= h($anamnese->paciente_contato_pessoa_com_suspeita_covid_local) ?></td>
                <td><?= h($anamnese->paciente_contato_pessoa_com_suspeita_covid_local_desc) ?></td>
                <td><?= h($anamnese->paciente_contato_pessoa_com_confirmado_covid) ?></td>
                <td><?= h($anamnese->paciente_contato_pessoa_com_confirmado_covid_caso_fonte) ?></td>
                <td><?= h($anamnese->paciente_unidade_saude_14_dias) ?></td>
                <td><?= h($anamnese->paciente_unidade_saude_14_dias_local) ?></td>
                <td><?= h($anamnese->paciente_ocupacao) ?></td>
                <td><?= h($anamnese->paciente_ocupacao_outros) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $anamnese->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $anamnese->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $anamnese->id], ['confirm' => __('Are you sure you want to delete # {0}?', $anamnese->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
