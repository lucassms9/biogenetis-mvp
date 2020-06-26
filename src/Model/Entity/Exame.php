<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Exame Entity
 *
 * @property int $id
 * @property int|null $paciente_id
 * @property int|null $amostra_id
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $resultado
 *
 * @property \App\Model\Entity\Paciente $paciente
 * @property \App\Model\Entity\Amostra $amostra
 */
class Exame extends Entity
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
        'amostra_id' => true,
        'created_by' => true,
        'created' => true,
        'modified' => true,
        'file_extesion' => true,
        'resultado' => true,
        'equip_tipo' => true,
        'amostra_tipo' => true,
        'amostra' => true,
        'file_name' => true,
    ];
}
