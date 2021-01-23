<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Origens Model
 *
 * @method \App\Model\Entity\Origen get($primaryKey, $options = [])
 * @method \App\Model\Entity\Origen newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Origen[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Origen|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Origen saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Origen patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Origen[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Origen findOrCreate($search, callable $callback = null, $options = [])
 */
class OrigensTable extends Table
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

        $this->setTable('origens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Encadeamentos', [
            'className' => 'Encadeamentos',
            'foreignKey' => 'origem_parent_id',
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
            ->scalar('nome_origem')
            ->maxLength('nome_origem', 255)
            ->allowEmptyString('nome_origem');

        $validator
            ->scalar('url_request')
            ->maxLength('url_request', 255)
            ->allowEmptyString('url_request');

        return $validator;
    }
}
