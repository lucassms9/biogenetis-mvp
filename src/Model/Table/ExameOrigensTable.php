<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExameOrigens Model
 *
 * @property \App\Model\Table\ExamesTable&\Cake\ORM\Association\BelongsTo $Exames
 * @property \App\Model\Table\OrigemsTable&\Cake\ORM\Association\BelongsTo $Origems
 *
 * @method \App\Model\Entity\ExameOrigen get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExameOrigen newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExameOrigen[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExameOrigen|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExameOrigen saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExameOrigen patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExameOrigen[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExameOrigen findOrCreate($search, callable $callback = null, $options = [])
 */
class ExameOrigensTable extends Table
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

        $this->setTable('exame_origens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Exames', [
            'foreignKey' => 'exame_id',
        ]);
        $this->belongsTo('Origens', [
            'foreignKey' => 'origem_id',
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
        $rules->add($rules->existsIn(['exame_id'], 'Exames'));

        return $rules;
    }
}
