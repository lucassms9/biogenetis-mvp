<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PedidoCroqui Entity
 *
 * @property int $id
 * @property int|null $croqui_tipo_id
 * @property int|null $pedido_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\CroquiTipo $croqui_tipo
 * @property \App\Model\Entity\Pedido $pedido
 * @property \App\Model\Entity\PedidoCroquiValore[] $pedido_croqui_valores
 */
class PedidoCroqui extends Entity
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
        'croqui_tipo_id' => true,
        'pedido_id' => true,
        'created' => true,
        'modified' => true,
        'croqui_tipo' => true,
        'pedido' => true,
        'codigo_croqui' => true,
        'pedido_croqui_valores' => true,
    ];
}
