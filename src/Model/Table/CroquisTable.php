<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
            ->scalar('foto_url')
            ->maxLength('foto_url', 255)
            ->allowEmptyString('foto_url');

        $validator
            ->integer('qtde_posi_placa')
            ->allowEmptyString('qtde_posi_placa');

        $validator
            ->scalar('tipo_exame_recomendado')
            ->allowEmptyString('tipo_exame_recomendado');

        return $validator;
    }
}
