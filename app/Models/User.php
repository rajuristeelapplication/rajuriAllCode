<?php

namespace App\Models;

use Str;
use App\Traits\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuids, SoftDeletes;

     /**
     * The name of the "created at" column.
     * The name of the "updated at" column.
     * The name of the "deleted at" column.
     *
     * @var string
     */
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['firstName','lastName','fullName','profileImage', 'email','mobileNumber','address', 'dob',
                            'cityId', 'photoIdProof','zipCode','countryCode','timezone','designation','userRandomId'
                          ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [ 'password','remember_token',];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [ 'email_verified_at' => 'datetime', ];
    /*
        Get Common User  Query
    */
    public static function getUserCommonQuery()
    {
        // $pathProfile = url('storage/images/profile');
        // $pathPhotoId = url('storage/images/photoid');

        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');
        $pathPhotoId = config('constant.baseUrlS3') . config('constant.photo_id_image');

        return self::selectRaw('users.*,photoIdProof as photoIdProofShortName,
                                        profileImage as profileImageShortName,roles.roleName,
                                 DATE_FORMAT(users.dob, "' . config('constant.dob_format') . '") as dobFormate,
                                IF(ISNULL(profileImage) or profileImage = "", CONCAT("'.$pathProfile.'","/default.png"), CONCAT("'.$pathProfile.'","/",profileImage)) as profileImage,
                                IF(ISNULL(photoIdProof) or photoIdProof = "", "", CONCAT("'.$pathPhotoId.'","/",photoIdProof)) as photoIdProof,cities.cName')
                                ->leftjoin('cities','cities.id','users.cityId')
                                ->leftjoin('roles','roles.id','users.roleId');
    }

     /**
     * Get User Condition
     *
     * @param  array $condition (Email or Phone Number)
     * @return result
     */
    public static function getUser($condition)
    {
        return self::getUserCommonQuery()->where($condition)->first();
    }

    /**
     * Get User Role Id
     *
     * @param string roleId  $request (IN => SE,ME)
     * @return roleId
     */

    public static function getRoleIdType($roleId)
    {
        return  ($roleId == "SE") ? config('constant.sales_executive_id') : config('constant.marketing_executive_id');
    }

     /**
     * Get User Input Email or Mobile Number
     *
     * @param string $userName   (Email or Phone Number)
     * @return array
     */

    public static function checkEmailOrMobileNumber($userName)
    {
        $where[] =  (Str::contains($userName, '@')) ?  ['email', $userName] :  ['mobileNumber', $userName];
        return $where;

        // if (Str::contains($userName, '@')) {
        //     $where[] = ['email', $userName];
        // } else {
        //     $where[] = ['mobileNumber', $userName];
        // }
        // return $where;
    }


    /**
     * Get the City for the User.
     */
    public function cities()
    {
        return $this->belongsTo(Cities::class,'cityId', 'id');
    }


    public static function getUserRoleName($roleId)
    {

        if($roleId == config('constant.hr_id'))
        {
            return "hr";
        }elseif($roleId == config('constant.ma_id')){
            return "ma";
        }else{
            return "all";
        }

        // switch ($roleId) {
        //     case "{{ config('constant.hr_id') }}":
        //       return "hr";
        //       break;
        //     case "blue":
        //       echo "Your favorite color is blue!";
        //       break;
        //     case "green":
        //       echo "Your favorite color is green!";
        //       break;
        //     default:
        //       echo "Your favorite color is neither red, blue, nor green!";
        //   }
    }

    public static function whichUserLogin()
    {
        if(Auth::user()->roleId == config('constant.ma_id'))
            {
                return $where = [config('constant.marketing_executive_id')];

                // return $where = [config('constant.sales_executive_id'),config('constant.marketing_executive_id')];
            }

            if(Auth::user()->roleId == config('constant.admin_id'))
            {
                return $where = [config('constant.sales_executive_id'),config('constant.marketing_executive_id')];
            }

            if(Auth::user()->roleId == config('constant.hr_id'))
            {
                return $where = [config('constant.sales_executive_id'),config('constant.marketing_executive_id')];
            }
    }

    public static function getMarketingAdminEmployee()
    {
        $userId = \Auth::user()->id;

        $result =  MarketingAdminEmployees::where('mAdminId',$userId)->pluck('employeeId')->toArray();

        return $result;
    }

    /*
        Remove Image
    */

    public static function bucketRemoveImage($imageName)
    {
        // $S3Local = config('constant.S3Local');

        // if($S3Local == "s3")
        // {
            \Storage::disk(config('constant.S3Local'))->delete($imageName);
        // }

        return true;
    }

}
