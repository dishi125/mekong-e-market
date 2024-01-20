<?php

namespace App\Repositories;

use App\Models\BannerPackage;
use App\Repositories\BaseRepository;

/**
 * Class BannerPackageRepository
 * @package App\Repositories
 * @version February 4, 2020, 5:04 am UTC
*/

class BannerPackageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'location',
        'price',
        'duration',
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
        return BannerPackage::class;
    }
}
