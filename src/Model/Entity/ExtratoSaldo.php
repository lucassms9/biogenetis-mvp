<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExtratoSaldo Entity
 *
 * @property int $id
 * @property int|null $voucher_id
 * @property string|null $type
 * @property float|null $valor
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $created_by
 *
 * @property \App\Model\Entity\Voucher $voucher
 */
class ExtratoSaldo extends Entity
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
        'voucher_id' => true,
        'type' => true,
        'valor' => true,
        'created' => true,
        'created_by' => true,
        'voucher' => true,
    ];
}
