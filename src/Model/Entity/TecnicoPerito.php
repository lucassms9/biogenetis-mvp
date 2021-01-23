<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TecnicoPerito Entity
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $cpf
 * @property string|null $email
 * @property string|null $celular
 * @property string|null $numero_crbio
 * @property string|null $certificado_digital
 * @property string|null $foto_assinatura_digital
 */
class TecnicoPerito extends Entity
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
        'nome' => true,
        'cpf' => true,
        'email' => true,
        'celular' => true,
        'numero_crbio' => true,
        'certificado_digital' => true,
        'foto_assinatura_digital' => true,
    ];
}
