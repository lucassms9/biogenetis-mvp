<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $nome_completo
 * @property string $email
 * @property string $senha
 * @property int $user_type_id
 * @property int $cliente_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\UserType $user_type
 * @property \App\Model\Entity\Cliente $cliente
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'nome_completo' => true,
        'email' => true,
        'senha' => true,
        'cpf' => true,
        'telefone' => true,
        'user_type_id' => true,
        'cliente_id' => true,
        'created' => true,
        'modified' => true,
        'user_type' => true,
        'cliente' => true,
        'numero_crbio' => true,
        'certificado_digital' => true,
        'foto_assinatura_digital' => true,
    ];

    protected function _setSenha($value)
    {

        if (!empty($value)) {
            return md5($value);
        }
    }

}
