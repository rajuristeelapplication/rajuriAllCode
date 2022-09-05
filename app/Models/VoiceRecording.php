<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoiceRecording extends CustomModel
{
    use HasFactory,SoftDeletes;

    protected $table = 'voice_recordings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','vrName','vrRename'
    ];

    public static function getSelectQuery()
    {
        // $pathPhoto = url('storage/images/voice_recording');

        $pathPhoto = config('constant.baseUrlS3') . config('constant.voice_recording_image');

        return self::selectRaw('voice_recordings.id, userId,vrRename,
                            IF(ISNULL(vrName) or vrName = "", "", CONCAT("'.$pathPhoto.'","/",vrName)) as vrName, vrName as sortNameVRName');
    }

    public static function scopeIsActive($query) {
        return $query->where('isActive', 1);
    }
}
