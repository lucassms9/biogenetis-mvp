<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EntradaExames Model
 *
 * @property \App\Model\Table\ExamesTable&\Cake\ORM\Association\HasMany $Exames
 *
 * @method \App\Model\Entity\EntradaExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\EntradaExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EntradaExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EntradaExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EntradaExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EntradaExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EntradaExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EntradaExame findOrCreate($search, callable $callback = null, $options = [])
 */
class EntradaExamesTable extends Table
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

        $this->setTable('entrada_exames');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Exames', [
            'foreignKey' => 'entrada_exame_id',
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
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->decimal('valor_particular')
            ->allowEmptyString('valor_particular');

        $validator
            ->decimal('valor_laboratorio_conveniado')
            ->allowEmptyString('valor_laboratorio_conveniado');

        $validator
            ->decimal('valor_laboratorio_nao_conveniado')
            ->allowEmptyString('valor_laboratorio_nao_conveniado');

        $validator
            ->scalar('tipo_exame')
            ->allowEmptyString('tipo_exame');

        return $validator;
    }
}
