<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Configuracoes Model
 *
 * @method \App\Model\Entity\Configuraco get($primaryKey, $options = [])
 * @method \App\Model\Entity\Configuraco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Configuraco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Configuraco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Configuraco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Configuraco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Configuraco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Configuraco findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfiguracoesTable extends Table
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

        $this->setTable('configuracoes');
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
            ->scalar('cns_profissional_rnds')
            ->maxLength('cns_profissional_rnds', 255)
            ->allowEmptyString('cns_profissional_rnds');

        $validator
            ->scalar('cnes_rnds')
            ->maxLength('cnes_rnds', 255)
            ->allowEmptyString('cnes_rnds');

        return $validator;
    }
}
