<?php

namespace App\Models\Sys;

use App\Models\Sys\Menu;
use App\Models\Slp\Permission;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class App extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'sys_apps';
    protected $fillable = ['name', 'subdomain', 'image', 'order_number'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('App')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'app_id')->orderBy('order_number');
    }

    public function app_permission()
    {
        return $this->hasOne(Permission::class, 'app_id', 'id')->where('type', 'App');
    }
}
