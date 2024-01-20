<?php

namespace App\Repositories;

use App\Models\LogisticCompany;
use App\Repositories\BaseRepository;

/**
 * Class LogisticCompanyRepository
 * @package App\Repositories
 * @version February 5, 2020, 6:43 am UTC
*/

class LogisticCompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'reg_no',
        'id_no',
        'contact',
        'email',
        'state_id',
        'area_id',
        'description',
        'nursery',
        'exporter_status',
        'profile',
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
        return LogisticCompany::class;
    }
}
