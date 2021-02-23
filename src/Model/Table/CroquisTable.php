<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
/**
 * Croquis Model
 *
 * @property \App\Model\Table\EquipamentosTable&\Cake\ORM\Association\HasMany $Equipamentos
 *
 * @method \App\Model\Entity\Croqui get($primaryKey, $options = [])
 * @method \App\Model\Entity\Croqui newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Croqui[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Croqui|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Croqui saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Croqui patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Croqui[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Croqui findOrCreate($search, callable $callback = null, $options = [])
 */
class CroquisTable extends Table
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

        $this->setTable('croquis');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->hasMany('Equipamentos', [
            'foreignKey' => 'croqui_id',
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
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->scalar('nome')
            ->notEmptyString('nome');

        $validator
            ->scalar('foto_url')
            ->maxLength('foto_url', 255)
            ->allowEmptyString('foto_url');

        $validator
            ->integer('qtde_posi_placa')
            ->notEmptyString('qtde_posi_placa');

        $validator
            ->scalar('tipo_exame_recomendado')
            ->notEmptyString('tipo_exame_recomendado');
        $validator
            ->scalar('tipo_equipament_recomendado')
            ->notEmptyString('tipo_equipament_recomendado');

        return $validator;
    }

    public function beforeFind(Event $event, Query $query, $options, $primary)
    {
        if (isset($_SESSION['Auth']['User']['user_type_id']) && $_SESSION['Auth']['User']['user_type_id'] !== 1) {

            $query->where(['Croquis.created_cliente_by' => $_SESSION['Auth']['User']['cliente_id']]);
        }
        return $query;
    }

}
