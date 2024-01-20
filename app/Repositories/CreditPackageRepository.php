<?php

namespace App\Repositories;

use App\Models\CreditPackage;
use App\Repositories\BaseRepository;

/**
 * Class CreditPackageRepository
 * @package App\Repositories
 * @version February 6, 2020, 6:27 am UTC
*/

class CreditPackageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'amount',
        'credit',
        'status'
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
        return CreditPackage::class;
    }
}
