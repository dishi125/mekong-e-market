<?php

namespace App\Repositories;

use App\Models\SettingPage;
use App\Repositories\BaseRepository;

/**
 * Class SettingPageRepository
 * @package App\Repositories
 * @version May 14, 2020, 11:34 am UTC
*/

class SettingPageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
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
        return SettingPage::class;
    }
}
