<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Encadeamentos Model
 *
 * @property \App\Model\Table\OrigemParentsTable&\Cake\ORM\Association\BelongsTo $OrigemParents
 * @property \App\Model\Table\OrigemEncadeamentosTable&\Cake\ORM\Association\BelongsTo $OrigemEncadeamentos
 *
 * @method \App\Model\Entity\Encadeamento get($primaryKey, $options = [])
 * @method \App\Model\Entity\Encadeamento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Encadeamento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Encadeamento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Encadeamento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Encadeamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Encadeamento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Encadeamento findOrCreate($search, callable $callback = null, $options = [])
 */
class EncadeamentosTable extends Table
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

        $this->setTable('encadeamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Origens', [
            'foreignKey' => 'origem_encadeamento_id',
        ]);
        $this->hasOne('EncadeamentoResultados', [
            'foreignKey' => 'encadeamento_id',
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
            ->scalar('regra')
            ->maxLength('regra', 255)
            ->allowEmptyString('regra');

        return $validator;
    }

}
