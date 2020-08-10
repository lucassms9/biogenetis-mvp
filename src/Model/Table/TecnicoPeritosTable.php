<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TecnicoPeritos Model
 *
 * @method \App\Model\Entity\TecnicoPerito get($primaryKey, $options = [])
 * @method \App\Model\Entity\TecnicoPerito newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TecnicoPerito[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TecnicoPerito|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TecnicoPerito saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TecnicoPerito patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TecnicoPerito[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TecnicoPerito findOrCreate($search, callable $callback = null, $options = [])
 */
class TecnicoPeritosTable extends Table
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

        $this->setTable('tecnico_peritos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('cpf')
            ->maxLength('cpf', 255)
            ->allowEmptyString('cpf');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 255)
            ->allowEmptyString('celular');

        $validator
            ->scalar('numero_crbio')
            ->maxLength('numero_crbio', 255)
            ->allowEmptyString('numero_crbio');

        $validator
            ->scalar('certificado_digital')
            ->maxLength('certificado_digital', 255)
            ->allowEmptyString('certificado_digital');

        $validator
            ->scalar('foto_assinatura_digital')
            ->maxLength('foto_assinatura_digital', 255)
            ->allowEmptyString('foto_assinatura_digital');

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
