<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;

/**
 * Pedidos Model
 *
 * @property \App\Model\Table\AnamneseTable&\Cake\ORM\Association\BelongsTo $Anamnese
 * @property \App\Model\Table\AmostrasTable&\Cake\ORM\Association\BelongsTo $Amostras
 * @property \App\Model\Table\ClientesTable&\Cake\ORM\Association\BelongsTo $Clientes
 *
 * @method \App\Model\Entity\Pedido get($primaryKey, $options = [])
 * @method \App\Model\Entity\Pedido newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Pedido[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pedido|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Pedido saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Pedido patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Pedido[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pedido findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PedidosTable extends Table
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

        $this->setTable('pedidos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Anamneses', [
            'foreignKey' => 'anamnese_id',
        ]);
        $this->belongsTo('Amostras', [
            'foreignKey' => 'amostra_id',
        ]);
        $this->belongsTo('Clientes', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'created_by',
        ]);
        $this->belongsTo('EntradaExames', [
            'foreignKey' => 'entrada_exame_id',
        ]);
        $this->belongsTo('Vouchers', [
            'foreignKey' => 'voucher_id',
        ]);
        $this->hasOne('PedidoCroqui', [
            'foreignKey' => 'pedido_id',
        ]);
        $this->hasOne('Exames', [
            'foreignKey' => 'pedido_id',
        ]);
        $this->hasOne('LaudoJobs', [
            'foreignKey' => 'pedido_id',
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
            ->scalar('forma_pagamento')
            ->maxLength('forma_pagamento', 255)
            ->allowEmptyString('forma_pagamento');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

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
        $rules->add($rules->existsIn(['anamnese_id'], 'Anamneses'));
        $rules->add($rules->existsIn(['amostra_id'], 'Amostras'));
        $rules->add($rules->existsIn(['cliente_id'], 'Clientes'));

        return $rules;
    }

    public function beforeFind(Event $event, $query, $options, $primary)
    {
        if (isset($_SESSION['Auth']['User']['user_type_id']) && $_SESSION['Auth']['User']['user_type_id'] !== 1) {

            $query->where(['Pedidos.cliente_id' => $_SESSION['Auth']['User']['cliente_id']]);
        }
        return $query;
    }

    public function beforeSave(Event $event)
    {
        $entityClass = $event->getData('entity');
        if ($entityClass->isNew()) {
            $entityClass->codigo_pedido = $this->geraCodPedido();
        }
    }

    public function geraCodPedido()
    {
        return mt_rand();
    }
}
