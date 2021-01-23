<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vouchers Model
 *
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\BelongsTo $Clientes
 * @property \App\Model\Table\ExtratoSaldoTable&\Cake\ORM\Association\HasMany $ExtratoSaldo
 * @property \App\Model\Table\PedidosTable&\Cake\ORM\Association\HasMany $Pedidos
 *
 * @method \App\Model\Entity\Voucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\Voucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Voucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Voucher|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Voucher saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Voucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VouchersTable extends Table
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

        $this->setTable('vouchers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Clientes', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->hasMany('ExtratoSaldo', [
            'foreignKey' => 'voucher_id',
        ]);
        $this->hasMany('Pedidos', [
            'foreignKey' => 'voucher_id',
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
            ->allowEmptyString('id', null, 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('codigo')
            ->maxLength('codigo', 255)
            ->allowEmptyString('codigo');

        $validator
            ->decimal('valor')
            ->allowEmptyString('valor');

        $validator
            ->boolean('used')
            ->allowEmptyString('used');

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
