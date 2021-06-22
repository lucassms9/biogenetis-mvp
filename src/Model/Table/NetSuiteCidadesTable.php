<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NetSuiteCidades Model
 *
 * @property \App\Model\Table\InternalsTable&\Cake\ORM\Association\BelongsTo $Internals
 *
 * @method \App\Model\Entity\NetSuiteCidade get($primaryKey, $options = [])
 * @method \App\Model\Entity\NetSuiteCidade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NetSuiteCidade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NetSuiteCidade|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NetSuiteCidade saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NetSuiteCidade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NetSuiteCidade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NetSuiteCidade findOrCreate($search, callable $callback = null, $options = [])
 */
class NetSuiteCidadesTable extends Table
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

        $this->setTable('net_suite_cidades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->allowEmptyString('nome');

        $validator
            ->scalar('code_municipio')
            ->maxLength('code_municipio', 255)
            ->allowEmptyString('code_municipio');

        $validator
            ->scalar('municipio_nome')
            ->maxLength('municipio_nome', 255)
            ->requirePresence('municipio_nome', 'create')
            ->notEmptyString('municipio_nome');

        $validator
            ->scalar('uf')
            ->maxLength('uf', 255)
            ->requirePresence('uf', 'create')
            ->notEmptyString('uf');

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

        return $rules;
    }
}
