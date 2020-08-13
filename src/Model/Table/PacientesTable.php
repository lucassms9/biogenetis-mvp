<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pacientes Model
 *
 * @property &\Cake\ORM\Association\HasMany $Anamneses
 *
 * @method \App\Model\Entity\Paciente get($primaryKey, $options = [])
 * @method \App\Model\Entity\Paciente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Paciente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Paciente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Paciente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Paciente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Paciente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Paciente findOrCreate($search, callable $callback = null, $options = [])
 */
class PacientesTable extends Table
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

        $this->setTable('pacientes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Anamneses', [
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
            ->allowEmptyString('id', null, 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->notEmpty('nome','Campo Obrigatório');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 255)
            ->allowEmptyString('cpf');

        $validator
            ->scalar('rg')
            ->maxLength('rg', 255)
            ->allowEmptyString('rg');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 255)
            ->allowEmptyString('celular');

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 255)
            ->allowEmptyString('telefone');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 255)
            ->allowEmptyString('sexo');

        $validator
            ->date('data_nascimento')
            ->allowEmptyDate('data_nascimento');

        $validator
            ->scalar('endereco')
            ->maxLength('endereco', 255)
            ->allowEmptyString('endereco');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 255)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 255)
            ->allowEmptyString('cep');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 255)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('uf')
            ->maxLength('uf', 255)
            ->allowEmptyString('uf');

        $validator
            ->scalar('foto_perfil_url')
            ->maxLength('foto_perfil_url', 255)
            ->allowEmptyString('foto_perfil_url');

        $validator
            ->scalar('foto_doc_url')
            ->maxLength('foto_doc_url', 255)
            ->allowEmptyString('foto_doc_url');

        $validator
            ->scalar('nome_da_mae')
            ->maxLength('nome_da_mae', 255)
            ->allowEmptyString('nome_da_mae');

        $validator
            ->scalar('nacionalidade')
            ->maxLength('nacionalidade', 255)
            ->allowEmptyString('nacionalidade');

        $validator
            ->scalar('pais_residencia')
            ->maxLength('pais_residencia', 255)
            ->allowEmptyString('pais_residencia');

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
        $rules->add(
            $rules->isUnique(['email']),
            ['errorField' => 'email', 'message' => 'E-mail ja existente']
        );
        $rules->add($rules->isUnique(['id']));

        return $rules;
    }
}
