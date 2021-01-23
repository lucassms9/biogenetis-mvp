<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Equipamento Entity
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $descricao
 * @property string|null $foto_url
 * @property string|null $tipo_exame
 * @property int|null $croqui_id
 *
 * @property \App\Model\Entity\Croqui $croqui
 */
class Equipamento extends Entity
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
        'foto_url' => true,
        'tipo_exame' => true,
        'croqui_id' => true,
        'croqui' => true,
    ];
}
