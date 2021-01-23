<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EncadeamentoResultados Model
 *
 * @property \App\Model\Table\ExameOrigemsTable&\Cake\ORM\Association\BelongsTo $ExameOrigems
 * @property \App\Model\Table\EscademantosTable&\Cake\ORM\Association\BelongsTo $Escademantos
 *
 * @method \App\Model\Entity\EncadeamentoResultado get($primaryKey, $options = [])
 * @method \App\Model\Entity\EncadeamentoResultado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EncadeamentoResultado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EncadeamentoResultado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EncadeamentoResultado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EncadeamentoResultado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EncadeamentoResultado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EncadeamentoResultado findOrCreate($search, callable $callback = null, $options = [])
 */
class EncadeamentoResultadosTable extends Table
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

        $this->setTable('encadeamento_resultados');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ExameOrigens', [
            'foreignKey' => 'exame_origem_id',
        ]);
        $this->belongsTo('Encadeamento', [
            'className' => 'Encadeamentos',
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
