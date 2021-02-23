<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TrackingPedido Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $codigo_pedido
 * @property string|null $status_anterior
 * @property string|null $status_atual
 * @property string|null $amostra_url
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class TrackingPedido extends Entity
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
        'user_id' => true,
        'codigo_pedido' => true,
        'status_anterior' => true,
        'status_atual' => true,
        'amostra_url' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
