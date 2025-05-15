<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogs extends Model
{

    protected $primaryKey = 'activity_logs_id';

    protected $fillable = [
        'title',
        'ref_table_id',
        'ref_table_rec_id',
        'activity_type_id',
        'remarks',
        'created_by',
    ];

    public static function addActivity($params = [])
    {
        $userId = Auth::id();
        $data = [
            'ref_table_id' => isset($params['ref_table_id']) ? $params['ref_table_id'] : '',
            'title' => isset($params['title']) ? $params['title'] : '',
            'ref_table_rec_id' => isset($params['ref_table_rec_id']) ? $params['ref_table_rec_id'] : '',
            'activity_type_id' => isset($params['activity_type_id']) ? $params['activity_type_id'] : '',
            'remarks' => isset($params['remarks']) ? $params['remarks'] : '',
            'created_by' => $userId,
        ];
        ActivityLogs::create($data);
    }
}
