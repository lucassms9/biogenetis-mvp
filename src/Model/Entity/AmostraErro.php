<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AmostraErro Entity
 *
 * @property int $id
 * @property int|null $amostra_id
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Amostra $amostra
 */
class AmostraErro extends Entity
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
        'amostra' => true,
    ];
}
