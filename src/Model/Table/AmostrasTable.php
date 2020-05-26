<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Amostras Model
 *
 * @property \App\Model\Table\ExamesTable&\Cake\ORM\Association\HasMany $Exames
 *
 * @method \App\Model\Entity\Amostra get($primaryKey, $options = [])
 * @method \App\Model\Entity\Amostra newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Amostra[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Amostra|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Amostra saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Amostra patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Amostra[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Amostra findOrCreate($search, callable $callback = null, $options = [])
 */
class AmostrasTable extends Table
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

        $this->setTable('amostras');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Exames', [
            'foreignKey' => 'amostra_id',
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
            ->scalar('code_amostra')
            ->maxLength('code_amostra', 255)
            ->allowEmptyString('code_amostra');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 255)
            ->allowEmptyString('cep');

        $validator
            ->integer('idade')
            ->allowEmptyString('idade');

        $validator
            ->scalar('sexo')
            ->allowEmptyString('sexo');

        return $validator;
    }
}
