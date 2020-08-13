<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Anamneses Model
 *
 * @property \App\Model\Table\PacientesTable&\Cake\ORM\Association\BelongsTo $Pacientes
 *
 * @method \App\Model\Entity\Anamnese get($primaryKey, $options = [])
 * @method \App\Model\Entity\Anamnese newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Anamnese[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Anamnese|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Anamnese saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Anamnese patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Anamnese[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Anamnese findOrCreate($search, callable $callback = null, $options = [])
 */
class AnamnesesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('anamneses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Pacientes', [
            'foreignKey' => 'paciente_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->notEmpty('id', null, 'create');

        $validator
            ->boolean('gestante')
            ->notEmpty('gestante','Campo Obrigatório');

        $validator
            ->scalar('medico_solicitante')
            ->maxLength('medico_solicitante', 255)
            ->allowEmptyString('medico_solicitante');

        $validator
            ->scalar('medico_crm')
            ->maxLength('medico_crm', 255)
            ->allowEmptyString('medico_crm');

        $validator
            ->date('data_coleta')
            ->allowEmptyDate('data_coleta');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        $validator
            ->scalar('assinatura')
            ->allowEmptyString('assinatura');

        $validator
            ->date('data_primeiros_sintomas')
            ->notEmpty('data_primeiros_sintomas','Campo Obrigatório');

        $validator
            ->boolean('sintoma_febre')
            ->allowEmptyString('sintoma_febre');

        $validator
            ->boolean('sintoma_mialgia_artralgia')
            ->allowEmptyString('sintoma_mialgia_artralgia');

        $validator
            ->boolean('sintoma_coriza')
            ->allowEmptyString('sintoma_coriza');

        $validator
            ->boolean('sintoma_congestao_nasal')
            ->allowEmptyString('sintoma_congestao_nasal');

        $validator
            ->boolean('sintoma_ganglios_linfaticos_aumentados')
            ->allowEmptyString('sintoma_ganglios_linfaticos_aumentados');

        $validator
            ->boolean('sintoma_sinais_de_cianose')
            ->allowEmptyString('sintoma_sinais_de_cianose');

        $validator
            ->boolean('sintoma_tosse')
            ->allowEmptyString('sintoma_tosse');

        $validator
            ->boolean('sintoma_diarreia')
            ->allowEmptyString('sintoma_diarreia');

        $validator
            ->boolean('sintoma_irritabilidade_confusao')
            ->allowEmptyString('sintoma_irritabilidade_confusao');

        $validator
            ->boolean('sintoma_congestao_conjuntival')
            ->allowEmptyString('sintoma_congestao_conjuntival');

        $validator
            ->boolean('sintoma_batimento_das_asas_nasais')
            ->allowEmptyString('sintoma_batimento_das_asas_nasais');

        $validator
            ->boolean('sintoma_tiragem_intercostal')
            ->allowEmptyString('sintoma_tiragem_intercostal');

        $validator
            ->boolean('sintoma_cor_de_garganta')
            ->allowEmptyString('sintoma_cor_de_garganta');

        $validator
            ->boolean('sintoma_nausea_vomitos')
            ->allowEmptyString('sintoma_nausea_vomitos');

        $validator
            ->boolean('sintoma_adinamia_fraqueza')
            ->allowEmptyString('sintoma_adinamia_fraqueza');

        $validator
            ->boolean('sintoma_dificildade_para_deglutir')
            ->allowEmptyString('sintoma_dificildade_para_deglutir');

        $validator
            ->boolean('sintoma_saturacao_de_o2_95')
            ->allowEmptyString('sintoma_saturacao_de_o2_95');

        $validator
            ->boolean('sintoma_dispneia')
            ->allowEmptyString('sintoma_dispneia');

        $validator
            ->boolean('sintoma_dificuldade_de_respirar')
            ->allowEmptyString('sintoma_dificuldade_de_respirar');

        $validator
            ->boolean('sintoma_cefaleia_dor_de_cabeca')
            ->allowEmptyString('sintoma_cefaleia_dor_de_cabeca');

        $validator
            ->boolean('sintoma_producao_de_escarro')
            ->allowEmptyString('sintoma_producao_de_escarro');

        $validator
            ->boolean('sintoma_manchas_vermelhas_pelo_corpo')
            ->allowEmptyString('sintoma_manchas_vermelhas_pelo_corpo');

        $validator
            ->boolean('sintoma_calafrios')
            ->allowEmptyString('sintoma_calafrios');

        $validator
            ->boolean('sintoma_outros')
            ->allowEmptyString('sintoma_outros');

        $validator
            ->scalar('sintoma_outros_observacao')
            ->maxLength('sintoma_outros_observacao', 255)
            ->allowEmptyString('sintoma_outros_observacao');

        $validator
            ->boolean('analgesico_antitermico_antiinflamatorio')
            ->notEmpty('analgesico_antitermico_antiinflamatorio','Campo Obrigatório');

        $validator
            ->boolean('clinico_febre')
            ->allowEmptyString('clinico_febre');

        $validator
            ->scalar('clinico_febre_temp')
            ->maxLength('clinico_febre_temp', 100)
            ->allowEmptyString('clinico_febre_temp');

        $validator
            ->boolean('clinico_coma')
            ->allowEmptyString('clinico_coma');

        $validator
            ->boolean('clinico_alteracao_na_radiologia_de_torax')
            ->allowEmptyString('clinico_alteracao_na_radiologia_de_torax');

        $validator
            ->boolean('clinico_exsudato')
            ->allowEmptyString('clinico_exsudato');

        $validator
            ->boolean('clinico_dispneia_taquipneia')
            ->allowEmptyString('clinico_dispneia_taquipneia');

        $validator
            ->boolean('clinico_convulsao')
            ->allowEmptyString('clinico_convulsao');

        $validator
            ->boolean('clinico_conjuntivite')
            ->allowEmptyString('clinico_conjuntivite');

        $validator
            ->boolean('clinico_alteracao_de_ausculta_pulmonar')
            ->allowEmptyString('clinico_alteracao_de_ausculta_pulmonar');

        $validator
            ->boolean('clinico_outros')
            ->allowEmptyString('clinico_outros');

        $validator
            ->scalar('clinico_outros_observacao')
            ->maxLength('clinico_outros_observacao', 255)
            ->allowEmptyString('clinico_outros_observacao');

        $validator
            ->boolean('morbidade_cardiovascular')
            ->allowEmptyString('morbidade_cardiovascular');

        $validator
            ->boolean('morbidade_neurologica')
            ->allowEmptyString('morbidade_neurologica');

        $validator
            ->boolean('morbidade_renal')
            ->allowEmptyString('morbidade_renal');

        $validator
            ->boolean('morbidade_diabetes')
            ->allowEmptyString('morbidade_diabetes');

        $validator
            ->boolean('morbidade_imunodeficiencia')
            ->allowEmptyString('morbidade_imunodeficiencia');

        $validator
            ->boolean('morbidade_pulmonar')
            ->allowEmptyString('morbidade_pulmonar');

        $validator
            ->boolean('morbidade_hepatica')
            ->allowEmptyString('morbidade_hepatica');

        $validator
            ->boolean('morbidade_hiv')
            ->allowEmptyString('morbidade_hiv');

        $validator
            ->boolean('morbidade_neoplasia')
            ->allowEmptyString('morbidade_neoplasia');

        $validator
            ->scalar('paciente_hospitalizado')
            ->notEmpty('paciente_hospitalizado', 'Campo Obrigatório');

        $validator
            ->scalar('paciente_hospitalizado_nome_hospital')
            ->maxLength('paciente_hospitalizado_nome_hospital', 255)
            ->allowEmptyString('paciente_hospitalizado_nome_hospital');

        $validator
            ->date('data_internacao')
            ->allowEmptyDate('data_internacao');

        $validator
            ->date('data_alta_hospitalar')
            ->allowEmptyDate('data_alta_hospitalar');

        $validator
            ->date('data_isolamento')
            ->allowEmptyDate('data_isolamento');

        $validator
            ->scalar('paciente_ventilacao_mecanica')
            ->notEmpty('paciente_ventilacao_mecanica','Campo Obrigatório');

        $validator
            ->scalar('paciente_situacao_notificacao')
            ->notEmpty('paciente_situacao_notificacao','Campo Obrigatório');

        $validator
            ->scalar('paciente_historico_viagem_14_dias')
            ->notEmpty('paciente_historico_viagem_14_dias','Campo Obrigatório');

        $validator
            ->date('paciente_historico_viagem_14_dias_data_chegada')
            ->allowEmptyDate('paciente_historico_viagem_14_dias_data_chegada');

        $validator
            ->scalar('paciente_coleta_de_amostra')
            ->notEmpty('paciente_coleta_de_amostra','Campo Obrigatório');

        $validator
            ->scalar('paciente_his_deslocamento_14_dias')
            ->allowEmptyString('paciente_his_deslocamento_14_dias');

        $validator
            ->scalar('paciente_contato_pessoa_com_suspeita_covid')
            ->allowEmptyString('paciente_contato_pessoa_com_suspeita_covid');

        $validator
            ->scalar('paciente_contato_pessoa_com_suspeita_covid_local')
            ->allowEmptyString('paciente_contato_pessoa_com_suspeita_covid_local');

        $validator
            ->scalar('paciente_contato_pessoa_com_suspeita_covid_local_desc')
            ->maxLength('paciente_contato_pessoa_com_suspeita_covid_local_desc', 255)
            ->allowEmptyString('paciente_contato_pessoa_com_suspeita_covid_local_desc');

        $validator
            ->scalar('paciente_contato_pessoa_com_confirmado_covid')
            ->allowEmptyString('paciente_contato_pessoa_com_confirmado_covid');

        $validator
            ->scalar('paciente_contato_pessoa_com_confirmado_covid_caso_fonte')
            ->maxLength('paciente_contato_pessoa_com_confirmado_covid_caso_fonte', 255)
            ->allowEmptyString('paciente_contato_pessoa_com_confirmado_covid_caso_fonte');

        $validator
            ->scalar('paciente_unidade_saude_14_dias')
            ->allowEmptyString('paciente_unidade_saude_14_dias');

        $validator
            ->scalar('paciente_unidade_saude_14_dias_local')
            ->maxLength('paciente_unidade_saude_14_dias_local', 255)
            ->allowEmptyString('paciente_unidade_saude_14_dias_local');

        $validator
            ->scalar('paciente_ocupacao')
            ->allowEmptyString('paciente_ocupacao');

        $validator
            ->scalar('paciente_ocupacao_outros')
            ->maxLength('paciente_ocupacao_outros', 255)
            ->allowEmptyString('paciente_ocupacao_outros');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['paciente_id'], 'Pacientes'));

        return $rules;
    }
}
