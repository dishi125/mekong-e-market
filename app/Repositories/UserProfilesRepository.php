<?php

namespace App\Repositories;

use App\Models\UserProfile;
use App\Models\UserProfiles;
use App\Repositories\BaseRepository;

/**
 * Class UserProfilesRepository
 * @package App\Repositories
 * @version February 5, 2020, 8:30 am UTC
*/

class UserProfilesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'password',
        'profile_pic',
        'phone_no',
        'user_type',
        'main_category_id',
        'company_name',
        'company_reg_no',
        'company_tel_no',
        'state_id',
        'area_id',
        'address',
        'company_email',
        'document',
        'job_description',
        'preferred_status',
        'is_approved_status',
        'parent_id',
        'package_id',
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
        return UserProfile::class;
    }
}
