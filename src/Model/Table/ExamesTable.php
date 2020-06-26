<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Exames Model
 *
 * @property \App\Model\Table\PacientesTable&\Cake\ORM\Association\BelongsTo $Pacientes
 * @property \App\Model\Table\AmostrasTable&\Cake\ORM\Association\BelongsTo $Amostras
 *
 * @method \App\Model\Entity\Exame get($primaryKey, $options = [])
 * @method \App\Model\Entity\Exame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Exame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Exame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Exame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Exame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Exame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Exame findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExamesTable extends Table
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

        $this->setTable('exames');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Amostras', [
            'foreignKey' => 'amostra_id',
        ]);

        $this->hasMany('ExameOrigens', [
            'className' => 'ExameOrigens',
            'foreignKey' => 'exame_id',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'created_by',
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
            ->integer('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->scalar('resultado')
            ->maxLength('resultado', 255)
            ->allowEmptyString('resultado');

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

        return $rules;
    }
}
