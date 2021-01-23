<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Equipamentos Model
 *
 * @property \App\Model\Table\CroquisTable&\Cake\ORM\Association\BelongsTo $Croquis
 *
 * @method \App\Model\Entity\Equipamento get($primaryKey, $options = [])
 * @method \App\Model\Entity\Equipamento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Equipamento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Equipamento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Equipamento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Equipamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Equipamento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Equipamento findOrCreate($search, callable $callback = null, $options = [])
 */
class EquipamentosTable extends Table
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

        $this->setTable('equipamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Croquis', [
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
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->allowEmptyString('nome');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->scalar('foto_url')
            ->maxLength('foto_url', 255)
            ->allowEmptyString('foto_url');

        $validator
            ->scalar('tipo_exame')
            ->allowEmptyString('tipo_exame');

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
        $rules->add($rules->existsIn(['croqui_id'], 'Croquis'));

        return $rules;
    }
}
