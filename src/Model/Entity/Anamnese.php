<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Anamnese Entity
 *
 * @property int $id
 * @property int|null $paciente_id
 * @property bool|null $gestante
 * @property string|null $medico_solicitante
 * @property string|null $medico_crm
 * @property \Cake\I18n\FrozenDate|null $data_coleta
 * @property string|null $observacao
 * @property string|null $assinatura
 * @property \Cake\I18n\FrozenDate|null $data_primeiros_sintomas
 * @property bool|null $sintoma_febre
 * @property bool|null $sintoma_mialgia_artralgia
 * @property bool|null $sintoma_coriza
 * @property bool|null $sintoma_congestao_nasal
 * @property bool|null $sintoma_ganglios_linfaticos_aumentados
 * @property bool|null $sintoma_sinais_de_cianose
 * @property bool|null $sintoma_tosse
 * @property bool|null $sintoma_diarreia
 * @property bool|null $sintoma_irritabilidade_confusao
 * @property bool|null $sintoma_congestao_conjuntival
 * @property bool|null $sintoma_batimento_das_asas_nasais
 * @property bool|null $sintoma_tiragem_intercostal
 * @property bool|null $sintoma_cor_de_garganta
 * @property bool|null $sintoma_nausea_vomitos
 * @property bool|null $sintoma_adinamia_fraqueza
 * @property bool|null $sintoma_dificildade_para_deglutir
 * @property bool|null $sintoma_saturacao_de_o2_95
 * @property bool|null $sintoma_dispneia
 * @property bool|null $sintoma_dificuldade_de_respirar
 * @property bool|null $sintoma_cefaleia_dor_de_cabeca
 * @property bool|null $sintoma_producao_de_escarro
 * @property bool|null $sintoma_manchas_vermelhas_pelo_corpo
 * @property bool|null $sintoma_calafrios
 * @property bool|null $sintoma_outros
 * @property string|null $sintoma_outros_observacao
 * @property bool|null $analgesico_antitermico_antiinflamatorio
 * @property bool|null $clinico_febre
 * @property string|null $clinico_febre_temp
 * @property bool|null $clinico_coma
 * @property bool|null $clinico_alteracao_na_radiologia_de_torax
 * @property bool|null $clinico_exsudato
 * @property bool|null $clinico_dispneia_taquipneia
 * @property bool|null $clinico_convulsao
 * @property bool|null $clinico_conjuntivite
 * @property bool|null $clinico_alteracao_de_ausculta_pulmonar
 * @property bool|null $clinico_outros
 * @property string|null $clinico_outros_observacao
 * @property bool|null $morbidade_cardiovascular
 * @property bool|null $morbidade_neurologica
 * @property bool|null $morbidade_renal
 * @property bool|null $morbidade_diabetes
 * @property bool|null $morbidade_imunodeficiencia
 * @property bool|null $morbidade_pulmonar
 * @property bool|null $morbidade_hepatica
 * @property bool|null $morbidade_hiv
 * @property bool|null $morbidade_neoplasia
 * @property string|null $paciente_hospitalizado
 * @property string|null $paciente_hospitalizado_nome_hospital
 * @property \Cake\I18n\FrozenDate|null $data_internacao
 * @property \Cake\I18n\FrozenDate|null $data_alta_hospitalar
 * @property \Cake\I18n\FrozenDate|null $data_isolamento
 * @property string|null $paciente_ventilacao_mecanica
 * @property string|null $paciente_situacao_notificacao
 * @property string|null $paciente_historico_viagem_14_dias
 * @property \Cake\I18n\FrozenDate|null $paciente_historico_viagem_14_dias_data_chegada
 * @property string|null $paciente_coleta_de_amostra
 * @property string|null $paciente_his_deslocamento_14_dias
 * @property string|null $paciente_contato_pessoa_com_suspeita_covid
 * @property string|null $paciente_contato_pessoa_com_suspeita_covid_local
 * @property string|null $paciente_contato_pessoa_com_suspeita_covid_local_desc
 * @property string|null $paciente_contato_pessoa_com_confirmado_covid
 * @property string|null $paciente_contato_pessoa_com_confirmado_covid_caso_fonte
 * @property string|null $paciente_unidade_saude_14_dias
 * @property string|null $paciente_unidade_saude_14_dias_local
 * @property string|null $paciente_ocupacao
 * @property string|null $paciente_ocupacao_outros
 *
 * @property \App\Model\Entity\Paciente $paciente
 */
class Anamnese extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'viagem_exteriorobs_pais' => true,
        'viagem_exterior' => true,
        'viagem_brasil_estado' => true,
        'viagem_brasil_cidade' => true,
        'viagem_brasil' => true,
        'sintoma_falta_de_apetite' => true,
        'sintoma_expectoracao' => true,

        'sintoma_dor_no_corpo' => true,
        'sintoma_doecas_associadas' => true,
        'sintoma_corisa_congestao_nasal' => true,
        'sintoma_cansaco_falta_de_ar' => true,
        'paciente_unidade_saude_14_dias_estado' => true,
        'paciente_unidade_saude_14_dias_cidade' => true,
        'paciente_historico_viagem_14_dias' => true,
        'clinico_rinite' => true,
        'clinico_obesidade' => true,
        'clinico_neoplasia_cancer' => true,
        'clinico_insuficiencia_renal' => true,
        'clinico_dpoc_enfisema' => true,
        'clinico_doencas_reumatologicas' => true,
        'clinico_doencas_hepaticas' => true,
        'clinico_diabetes' => true,
        'clinico_cardiovascular' => true,
        'clinico_asma' => true,
        'clinico_apneia_do_sone' => true,
        'clinico_alteracao_colesterol' => true,


        'cliente_id' => true,
        'paciente_id' => true,
        'tipo_pagamento' => true,
        'gestante' => true,
        'medico_solicitante' => true,
        'medico_crm' => true,
        'data_coleta' => true,
        'observacao' => true,
        'assinatura' => true,
        'data_primeiros_sintomas' => true,
        'status' => true,
        'sintoma_febre' => true,
        'sintoma_mialgia_artralgia' => true,
        'sintoma_coriza' => true,
        'sintoma_congestao_nasal' => true,
        'sintoma_ganglios_linfaticos_aumentados' => true,
        'sintoma_sinais_de_cianose' => true,
        'sintoma_tosse' => true,
        'sintoma_diarreia' => true,
        'sintoma_irritabilidade_confusao' => true,
        'sintoma_congestao_conjuntival' => true,
        'sintoma_batimento_das_asas_nasais' => true,
        'sintoma_tiragem_intercostal' => true,
        'sintoma_cor_de_garganta' => true,
        'sintoma_nausea_vomitos' => true,
        'sintoma_adinamia_fraqueza' => true,
        'sintoma_dificildade_para_deglutir' => true,
        'sintoma_saturacao_de_o2_95' => true,
        'sintoma_dispneia' => true,
        'sintoma_dificuldade_de_respirar' => true,
        'sintoma_cefaleia_dor_de_cabeca' => true,
        'sintoma_producao_de_escarro' => true,
        'sintoma_manchas_vermelhas_pelo_corpo' => true,
        'sintoma_calafrios' => true,
        'sintoma_outros' => true,
        'sintoma_outros_observacao' => true,
        'analgesico_antitermico_antiinflamatorio' => true,
        'clinico_febre' => true,
        'clinico_febre_temp' => true,
        'clinico_coma' => true,
        'clinico_alteracao_na_radiologia_de_torax' => true,
        'clinico_exsudato' => true,
        'clinico_dispneia_taquipneia' => true,
        'clinico_convulsao' => true,
        'clinico_conjuntivite' => true,
        'clinico_alteracao_de_ausculta_pulmonar' => true,
        'clinico_outros' => true,
        'clinico_outros_observacao' => true,
        'morbidade_cardiovascular' => true,
        'morbidade_neurologica' => true,
        'morbidade_renal' => true,
        'morbidade_diabetes' => true,
        'morbidade_imunodeficiencia' => true,
        'morbidade_pulmonar' => true,
        'morbidade_hepatica' => true,
        'morbidade_hiv' => true,
        'morbidade_neoplasia' => true,
        'paciente_hospitalizado' => true,
        'paciente_hospitalizado_nome_hospital' => true,
        'data_internacao' => true,
        'data_alta_hospitalar' => true,
        'data_isolamento' => true,
        'paciente_ventilacao_mecanica' => true,
        'paciente_situacao_notificacao' => true,
        'paciente_historico_viagem_14_dias' => true,
        'paciente_historico_viagem_14_dias_data_chegada' => true,
        'paciente_coleta_de_amostra' => true,
        'paciente_his_deslocamento_14_dias' => true,
        'paciente_contato_pessoa_com_suspeita_covid' => true,
        'paciente_contato_pessoa_com_suspeita_covid_local' => true,
        'paciente_contato_pessoa_com_suspeita_covid_local_desc' => true,
        'paciente_contato_pessoa_com_confirmado_covid' => true,
        'paciente_contato_pessoa_com_confirmado_covid_caso_fonte' => true,
        'paciente_unidade_saude_14_dias' => true,
        'paciente_unidade_saude_14_dias_local' => true,
        'paciente_ocupacao' => true,
        'paciente_ocupacao_outros' => true,
        'paciente' => true,
    ];
}
