<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Clientes Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Cliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cliente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cliente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cliente findOrCreate($search, callable $callback = null, $options = [])
 */
class ClientesTable extends Table
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

        $this->setTable('clientes');
        $this->setDisplayField('nome_fantasia');
        $this->setPrimaryKey('id');

        $this->hasMany('Users', [
            'foreignKey' => 'cliente_id',
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
            ->scalar('nome_fantasia')
            ->maxLength('nome_fantasia', 255)
            ->allowEmptyString('nome_fantasia');

        $validator
            ->scalar('razao_social')
            ->maxLength('razao_social', 255)
            ->allowEmptyString('razao_social');

        $validator
            ->scalar('cnpj_cpf')
            ->maxLength('cnpj_cpf', 255)
            ->allowEmptyString('cnpj_cpf');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 255)
            ->allowEmptyString('cep');

        $validator
            ->scalar('endereco')
            ->maxLength('endereco', 255)
            ->allowEmptyString('endereco');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 255)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 255)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('uf')
            ->maxLength('uf', 255)
            ->allowEmptyString('uf');

        $validator
            ->scalar('responsavel_nome')
            ->maxLength('responsavel_nome', 255)
            ->allowEmptyString('responsavel_nome');

        $validator
            ->scalar('responsavel_email')
            ->maxLength('responsavel_email', 255)
            ->allowEmptyString('responsavel_email');

        $validator
            ->scalar('responsavel_telefone')
            ->maxLength('responsavel_telefone', 255)
            ->allowEmptyString('responsavel_telefone');

        $validator
            ->scalar('responsavel_financeiro_nome')
            ->maxLength('responsavel_financeiro_nome', 255)
            ->allowEmptyString('responsavel_financeiro_nome');

        $validator
            ->scalar('responsavel_financeiro_email')
            ->maxLength('responsavel_financeiro_email', 255)
            ->allowEmptyString('responsavel_financeiro_email');

        $validator
            ->scalar('responsavel_financeiro_telefone')
            ->maxLength('responsavel_financeiro_telefone', 255)
            ->allowEmptyString('responsavel_financeiro_telefone');

        $validator
            ->scalar('tipo_cobranca')
            ->allowEmptyString('tipo_cobranca');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

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
        $rules->add($rules->isUnique(['id']));

        return $rules;
    }
}
