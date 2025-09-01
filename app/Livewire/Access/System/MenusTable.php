<?php

namespace App\Livewire\Access\System;

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use App\Helpers\TableHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class MenusTable extends DataTableComponent
{
    public $key;
    public string $tableName = 'access_system_menus';

    public function builder(): Builder
    {
        return Menu::query()
            ->with(['app'])
            ->orderBy('app.order_number', 'asc')
            ->orderBy('order_number', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setAdditionalSelects(['sys_menus.id as identifier']);
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
            Column::make("Aplikasi", "app.name"),
            Column::make("Judul", "title"),
            Column::make("Icon", "icon")
                ->format(
                    fn($value, $row, Column $column) => '<i class="icon-base '.$value.'"></i>'
                )
                ->html(),
            Column::make("Url", "url"),
            Column::make("Urutan", "order_number"),
            Column::make("Turunan Dari", "parent"),
            Column::make("Kelompok", "member_of"),
            Column::make('Aksi')
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->label(fn ($row, Column $column) => TableHelper::action_buttons(recordId: $row->identifier,
                        permissions: [
                            'edit' => 'Access - Sistem - Menu - Mengubah Data',
                            'delete' => 'Access - Sistem - Menu - Menghapus Data',
                        ],
                        cssClasses: [
                            'edit_btn' => 'btn_menu_edit',
                            'delete_btn' => 'btn_menu_delete',
                        ],
                        editModalTarget: '#modal_menu_resource'))
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
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
            TextFilter::make('Judul')
                ->config([
                    'placeholder' => 'Cari Menu',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('title', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Url')
                ->config([
                    'placeholder' => 'Cari Url',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('url', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Kelompok')
                ->config([
                    'placeholder' => 'Cari Kelompok',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('member_of', 'LIKE', '%'.$value.'%');
                    }
                }),
        ];
    }

    public function actions(): array
    {
        return array_filter([
            auth()->user()->hasPermissionTo('Access - Sistem - Menu - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_menu_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_menu_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
