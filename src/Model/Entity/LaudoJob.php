<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LaudoJob Entity
 *
 * @property int $id
 * @property bool|null $completed
 * @property string|null $file
 * @property int|null $pedido_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Pedido $pedido
 */
class LaudoJob extends Entity
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
        'completed' => true,
        'file' => true,
        'pedido_id' => true,
        'created' => true,
        'modified' => true,
        'pedido' => true,
    ];
}
