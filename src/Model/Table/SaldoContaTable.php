<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SaldoConta Model
 *
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\BelongsTo $Clientes
 *
 * @method \App\Model\Entity\SaldoContum get($primaryKey, $options = [])
 * @method \App\Model\Entity\SaldoContum newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SaldoContum[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SaldoContum|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SaldoContum saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SaldoContum patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SaldoContum[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SaldoContum findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SaldoContaTable extends Table
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

        $this->setTable('saldo_conta');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->allowEmptyString('id', null, 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->decimal('saldo')
            ->allowEmptyString('saldo');

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
        $rules->add($rules->existsIn(['cliente_id'], 'Clientes'));

        return $rules;
    }
}
