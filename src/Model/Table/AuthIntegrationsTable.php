<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AuthIntegrations Model
 *
 * @method \App\Model\Entity\AuthIntegration get($primaryKey, $options = [])
 * @method \App\Model\Entity\AuthIntegration newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AuthIntegration[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuthIntegration|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuthIntegration saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuthIntegration patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AuthIntegration[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuthIntegration findOrCreate($search, callable $callback = null, $options = [])
 */
class AuthIntegrationsTable extends Table
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

        $this->setTable('auth_integrations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Clientes', [
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('user')
            ->maxLength('user', 255)
            ->requirePresence('user', 'create')
            ->notEmptyString('user');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        return $validator;
    }
}
