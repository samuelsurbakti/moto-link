<?php

namespace App\Models\Sys;

use App\Models\Sys\App;
use App\Models\Slp\Permission;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Menu extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'sys_menus';
    protected $fillable = ['app_id', 'title', 'icon', 'url', 'order_number', 'parent', 'member_of'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Mengelola Menu')
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $action = match($eventName) {
                    'created' => 'Menambah',
                    'updated' => 'Mengubah',
                    'deleted' => 'Menghapus',
                    default => $eventName
                };

                return "{$action} data menu dengan judul [{$this->title}]";
            })
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function app()
    {
        return $this->belongsTo(App::class, 'app_id');
    }

    public function get_parent()
    {
        return $this->belongsTo(Menu::class, 'parent');
    }

    public function get_child()
    {
        return $this->hasMany(Menu::class, 'parent')->orderBy('order_number');
    }

    public function action_permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id')
                    ->where('type', 'Permission') // Tindakan, misalnya create/update/delete
                    ->orderBy('number', 'asc');
    }

    public function menu_permission()
    {
        return $this->hasOne(Permission::class, 'menu_id')
                    ->where('type', 'Menu');
    }
}
