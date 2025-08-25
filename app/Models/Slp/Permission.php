<?php

namespace App\Models\Slp;

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    public function app()
    {
        return $this->belongsTo(App::class, 'app_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
