<?php

namespace App\Repositories;

use App\Models\CreditManagement;
use App\Repositories\BaseRepository;

/**
 * Class CreditManagementRepository
 * @package App\Repositories
 * @version February 15, 2020, 5:56 am UTC
*/

class CreditManagementRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'buyer_id',
        'post_id',
        'bid_price',
        'buyer_fees',
        'credit_transaction_id',
        'total_amount',
        'transaction_status'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CreditManagement::class;
    }
}
