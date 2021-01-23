<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Voucher Entity
 *
 * @property int $id
 * @property string|null $codigo
 * @property float|null $valor
 * @property int|null $cliente_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property bool|null $used
 *
 * @property \App\Model\Entity\Cliente $cliente
 * @property \App\Model\Entity\ExtratoSaldo[] $extrato_saldo
 * @property \App\Model\Entity\Pedido[] $pedidos
 */
class Voucher extends Entity
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
        'codigo' => true,
        'valor' => true,
        'cliente_id' => true,
        'created' => true,
        'used' => true,
        'cliente' => true,
        'extrato_saldo' => true,
        'pedidos' => true,
    ];
}
