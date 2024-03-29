<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AnamnesesFixture
 */
class AnamnesesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'paciente_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'gestante' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'medico_solicitante' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'medico_crm' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'data_coleta' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'observacao' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'assinatura' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'data_primeiros_sintomas' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_febre' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_mialgia_artralgia' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_coriza' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_congestao_nasal' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_ganglios_linfaticos_aumentados' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_sinais_de_cianose' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_tosse' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_diarreia' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_irritabilidade_confusao' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_congestao_conjuntival' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_batimento_das_asas_nasais' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_tiragem_intercostal' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_cor_de_garganta' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_nausea_vomitos' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_adinamia_fraqueza' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_dificildade_para_deglutir' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_saturacao_de_o2_95' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_dispneia' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_dificuldade_de_respirar' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_cefaleia_dor_de_cabeca' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_producao_de_escarro' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_manchas_vermelhas_pelo_corpo' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_calafrios' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_outros' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'sintoma_outros_observacao' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'analgesico_antitermico_antiinflamatorio' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_febre' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_febre_temp' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'clinico_coma' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_alteracao_na_radiologia_de_torax' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_exsudato' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_dispneia_taquipneia' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_convulsao' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_conjuntivite' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_alteracao_de_ausculta_pulmonar' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_outros' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'clinico_outros_observacao' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'morbidade_cardiovascular' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_neurologica' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_renal' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_diabetes' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_imunodeficiencia' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_pulmonar' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_hepatica' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_hiv' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'morbidade_neoplasia' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'paciente_hospitalizado' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_hospitalizado_nome_hospital' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'data_internacao' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'data_alta_hospitalar' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'data_isolamento' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'paciente_ventilacao_mecanica' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_situacao_notificacao' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_historico_viagem_14_dias' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_historico_viagem_14_dias_data_chegada' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'paciente_coleta_de_amostra' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_his_deslocamento_14_dias' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'paciente_contato_pessoa_com_suspeita_covid' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_contato_pessoa_com_suspeita_covid_local' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_contato_pessoa_com_suspeita_covid_local_desc' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_contato_pessoa_com_confirmado_covid' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_contato_pessoa_com_confirmado_covid_caso_fonte' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_unidade_saude_14_dias' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_unidade_saude_14_dias_local' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_ocupacao' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'paciente_ocupacao_outros' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'paciente_id' => 1,
                'gestante' => 1,
                'medico_solicitante' => 'Lorem ipsum dolor sit amet',
                'medico_crm' => 'Lorem ipsum dolor sit amet',
                'data_coleta' => '2020-08-09',
                'observacao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'assinatura' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'data_primeiros_sintomas' => '2020-08-09',
                'sintoma_febre' => 1,
                'sintoma_mialgia_artralgia' => 1,
                'sintoma_coriza' => 1,
                'sintoma_congestao_nasal' => 1,
                'sintoma_ganglios_linfaticos_aumentados' => 1,
                'sintoma_sinais_de_cianose' => 1,
                'sintoma_tosse' => 1,
                'sintoma_diarreia' => 1,
                'sintoma_irritabilidade_confusao' => 1,
                'sintoma_congestao_conjuntival' => 1,
                'sintoma_batimento_das_asas_nasais' => 1,
                'sintoma_tiragem_intercostal' => 1,
                'sintoma_cor_de_garganta' => 1,
                'sintoma_nausea_vomitos' => 1,
                'sintoma_adinamia_fraqueza' => 1,
                'sintoma_dificildade_para_deglutir' => 1,
                'sintoma_saturacao_de_o2_95' => 1,
                'sintoma_dispneia' => 1,
                'sintoma_dificuldade_de_respirar' => 1,
                'sintoma_cefaleia_dor_de_cabeca' => 1,
                'sintoma_producao_de_escarro' => 1,
                'sintoma_manchas_vermelhas_pelo_corpo' => 1,
                'sintoma_calafrios' => 1,
                'sintoma_outros' => 1,
                'sintoma_outros_observacao' => 'Lorem ipsum dolor sit amet',
                'analgesico_antitermico_antiinflamatorio' => 1,
                'clinico_febre' => 1,
                'clinico_febre_temp' => 'Lorem ipsum dolor sit amet',
                'clinico_coma' => 1,
                'clinico_alteracao_na_radiologia_de_torax' => 1,
                'clinico_exsudato' => 1,
                'clinico_dispneia_taquipneia' => 1,
                'clinico_convulsao' => 1,
                'clinico_conjuntivite' => 1,
                'clinico_alteracao_de_ausculta_pulmonar' => 1,
                'clinico_outros' => 1,
                'clinico_outros_observacao' => 'Lorem ipsum dolor sit amet',
                'morbidade_cardiovascular' => 1,
                'morbidade_neurologica' => 1,
                'morbidade_renal' => 1,
                'morbidade_diabetes' => 1,
                'morbidade_imunodeficiencia' => 1,
                'morbidade_pulmonar' => 1,
                'morbidade_hepatica' => 1,
                'morbidade_hiv' => 1,
                'morbidade_neoplasia' => 1,
                'paciente_hospitalizado' => 'Lorem ipsum dolor sit amet',
                'paciente_hospitalizado_nome_hospital' => 'Lorem ipsum dolor sit amet',
                'data_internacao' => '2020-08-09',
                'data_alta_hospitalar' => '2020-08-09',
                'data_isolamento' => '2020-08-09',
                'paciente_ventilacao_mecanica' => 'Lorem ipsum dolor sit amet',
                'paciente_situacao_notificacao' => 'Lorem ipsum dolor sit amet',
                'paciente_historico_viagem_14_dias' => 'Lorem ipsum dolor sit amet',
                'paciente_historico_viagem_14_dias_data_chegada' => '2020-08-09',
                'paciente_coleta_de_amostra' => 'Lorem ipsum dolor sit amet',
                'paciente_his_deslocamento_14_dias' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'paciente_contato_pessoa_com_suspeita_covid' => 'Lorem ipsum dolor sit amet',
                'paciente_contato_pessoa_com_suspeita_covid_local' => 'Lorem ipsum dolor sit amet',
                'paciente_contato_pessoa_com_suspeita_covid_local_desc' => 'Lorem ipsum dolor sit amet',
                'paciente_contato_pessoa_com_confirmado_covid' => 'Lorem ipsum dolor sit amet',
                'paciente_contato_pessoa_com_confirmado_covid_caso_fonte' => 'Lorem ipsum dolor sit amet',
                'paciente_unidade_saude_14_dias' => 'Lorem ipsum dolor sit amet',
                'paciente_unidade_saude_14_dias_local' => 'Lorem ipsum dolor sit amet',
                'paciente_ocupacao' => 'Lorem ipsum dolor sit amet',
                'paciente_ocupacao_outros' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
