<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PedidoCroqui Model
 *
 * @property \App\Model\Table\CroquiTiposTable&\Cake\ORM\Association\BelongsTo $CroquiTipos
 * @property \App\Model\Table\PedidosTable&\Cake\ORM\Association\BelongsTo $Pedidos
 * @property \App\Model\Table\PedidoCroquiValoresTable&\Cake\ORM\Association\HasMany $PedidoCroquiValores
 *
 * @method \App\Model\Entity\PedidoCroqui get($primaryKey, $options = [])
 * @method \App\Model\Entity\PedidoCroqui newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PedidoCroqui[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PedidoCroqui|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PedidoCroqui saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PedidoCroqui patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PedidoCroqui[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PedidoCroqui findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PedidoCroquiTable extends Table
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

        $this->setTable('pedido_croqui');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Croquis', [
            'className' => 'Croquis',
            'foreignKey' => 'croqui_tipo_id',
        ]);
        $this->belongsTo('Pedidos', [
            'foreignKey' => 'pedido_id',
        ]);
        $this->hasMany('PedidoCroquiValores', [
            'foreignKey' => 'pedido_croqui_id',
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
        $rules->add($rules->existsIn(['croqui_tipo_id'], 'Croquis'));
        $rules->add($rules->existsIn(['pedido_id'], 'Pedidos'));

        return $rules;
    }
}
