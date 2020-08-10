<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Croqui Entity
 *
 * @property int $id
 * @property string|null $descricao
 * @property string|null $foto_url
 * @property int|null $qtde_posi_placa
 * @property string|null $tipo_exame_recomendado
 * @property int|null $tipo_equipament_recomendado
 *
 * @property \App\Model\Entity\Equipamento[] $equipamentos
 */
class Croqui extends Entity
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
        'descricao' => true,
        'foto_url' => true,
        'qtde_posi_placa' => true,
        'nome' => true,
        'tipo_exame_recomendado' => true,
        'tipo_equipament_recomendado' => true,
        'equipamentos' => true,
    ];
}
