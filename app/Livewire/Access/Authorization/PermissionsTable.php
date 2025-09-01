<?php

namespace App\Livewire\Access\Authorization;

use App\Models\Sys\App;
use App\Helpers\TableHelper;
use App\Models\Slp\Permission;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class PermissionsTable extends DataTableComponent
{
    public $key;
    public string $tableName = 'access_authorization_permissions';

    public function builder(): Builder
    {
        return Permission::query()
            ->with(['app', 'menu'])
            ->orderBy('app.order_number', 'asc')
            ->orderBy('menu.order_number', 'asc')
            ->orderBy('type', 'asc')
            ->orderBy('number', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('uuid')->setAdditionalSelects(['slp_permissions.uuid as identifier']);
        $this->setLoadingPlaceholderEnabled();
        $this->setLoadingPlaceholderContent('Mengambil Data');
        $this->setComponentWrapperAttributes([
            'default' => true,
            'class' => 'card',
        ]);
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table border-top',
        ]);
        $this->setSearchDisabled();
        $this->setFilterLayoutSlideDown();
        $this->setActionsInToolbarEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Jenis", "type"),
            Column::make("Aplikasi", "app.name"),
            Column::make("Menu", "menu.title"),
            Column::make("Nama", "name"),
            Column::make("Urutan", "number"),
            Column::make('Diberikan Kepada', 'uuid')
                ->format(function ($value, $row) {
                    return TableHelper::roles_in_permission($row);
                })
                ->html(),
            Column::make('Aksi')
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->label(fn ($row, Column $column) => TableHelper::action_buttons(recordId: $row->uuid,
                        permissions: [
                            'edit' => 'Access - Otorisasi - Izin - Mengubah Data',
                            'delete' => 'Access - Otorisasi - Izin - Menghapus Data',
                        ],
                        cssClasses: [
                            'edit_btn' => 'btn_permission_edit',
                            'delete_btn' => 'btn_permission_delete',
                        ],
                        editModalTarget: '#modal_permission_resource'))
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            MultiSelectFilter::make('Jenis')
                ->options(
                    Permission::query()
                        ->distinct()
                        ->get()
                        ->keyBy('type')
                        ->map(fn($permission) => $permission->type)
                        ->toArray()
                ) // Menggunakan opsi dinamis dari database
                ->filter(function(Builder $builder, array $value) {
                    if (!empty($value)) {
                        $builder->whereIn('type', $value);
                    }
                }),
            MultiSelectFilter::make('Aplikasi')
                ->options(
                    App::query()
                        ->orderBy('order_number')
                        ->get()
                        ->keyBy('id')
                        ->map(fn($app) => $app->name)
                        ->toArray()
                )
                ->filter(function(Builder $builder, array $value) {
                    if (!empty($value)) {
                        $builder->whereIn('app.id', $value);
                    }
                }),
            TextFilter::make('Menu')
                ->config([
                    'placeholder' => 'Cari Menu',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('menu.title', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Nama')
                ->config([
                    'placeholder' => 'Cari Nama',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('slp_permissions.name', 'LIKE', '%'.$value.'%');
                    }
                }),
        ];
    }

    public function actions(): array
    {
        return array_filter([
            auth()->user()->hasPermissionTo('Access - Otorisasi - Izin - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_permission_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_permission_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
