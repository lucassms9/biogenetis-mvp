<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Produto Entity
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $descricao
 * @property float|null $valor_lab_conveniado
 * @property float|null $valor_lab_nao_conveniado
 * @property string|null $tipo_exame
 */
class Produto extends Entity
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
        'descricao' => true,
        'valor_lab_conveniado' => true,
        'valor_lab_nao_conveniado' => true,
        'tipo_exame' => true,
    ];
}
