<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TrackingPedidos Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\TrackingPedido get($primaryKey, $options = [])
 * @method \App\Model\Entity\TrackingPedido newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TrackingPedido[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TrackingPedido|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TrackingPedido saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TrackingPedido patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TrackingPedido[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TrackingPedido findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TrackingPedidosTable extends Table
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

        $this->setTable('tracking_pedidos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->scalar('codigo_pedido')
            ->maxLength('codigo_pedido', 255)
            ->allowEmptyString('codigo_pedido');

        $validator
            ->scalar('status_anterior')
            ->maxLength('status_anterior', 255)
            ->allowEmptyString('status_anterior');

        $validator
            ->scalar('status_atual')
            ->maxLength('status_atual', 255)
            ->allowEmptyString('status_atual');

        $validator
            ->scalar('amostra_url')
            ->maxLength('amostra_url', 255)
            ->allowEmptyString('amostra_url');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function createLog($data){
        $item = $this->newEntity();
        $item = $this->patchEntity($item, $data);
        $item = $this->save($item);
    }
}
