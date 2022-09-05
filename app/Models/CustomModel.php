<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model
{
    use Uuids;

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
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = false;
}
