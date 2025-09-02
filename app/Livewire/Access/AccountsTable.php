<?php

namespace App\Livewire\Access;

use App\Models\User;
use App\Helpers\TableHelper;
use App\Models\Slp\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class AccountsTable extends DataTableComponent
{
    public $key;
    public string $tableName = 'access_accounts';

    public function builder(): Builder
    {
        return User::query()
            ->with(['roles'])
            ->orderBy('name', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setAdditionalSelects(['users.id as identifier']);
        if (auth()->user()->hasPermissionTo('Access - Akun - Melihat Data')) {
            $this->setTableRowUrl(function($row) {
                return route('Access | Account | Show', $row->id);
            });
        }
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
        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('avatar')) {
                return [
                    'class' => 'w-px-18 pe-0',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make("Avatar", "avatar")
                ->setColumnLabelStatusDisabled()
                ->excludeFromColumnSelect()
                ->format(
                    fn($value, $row, Column $column) => '<img src="/src/img/user/'.$value.'" alt="User Image" class="rounded-circle h-px-50" />'
                )
                ->html(),
            Column::make("Nama", "name"),
            Column::make("Username", "username"),
            Column::make("Email", "email"),
            Column::make('Peran', 'id')
                ->format(function ($value, $row) {
                    return TableHelper::role_in_account($row);
                })
                ->html(),
            Column::make("Status", "account_status")
                ->format(
                    fn($value, $row, Column $column) => '<span class="badge rounded-pill '.($value == 1 ? 'bg-label-success' : 'bg-label-danger').'">'.($value == 1 ? 'Aktif' : 'Tidak Aktif').'</span>'
                )
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            MultiSelectFilter::make('Peran')
                ->options(
                    Role::query()
                        ->orderBy('name')
                        ->get()
                        ->keyBy('name')
                        ->map(fn($role) => $role->name)
                        ->toArray()
                )
                ->filter(function(Builder $builder, array $value) {
                    if (!empty($value)) {
                        $builder->whereHas('roles', function ($query) use ($value) {
                            $query->whereIn('name', $value);
                        });
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
                        $builder->where('name', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Username')
                ->config([
                    'placeholder' => 'Cari Username',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('username', 'LIKE', '%'.$value.'%');
                    }
                }),
            TextFilter::make('Email')
                ->config([
                    'placeholder' => 'Cari Email',
                    'maxlength' => '25',
                ])
                ->setWireLive()
                ->filter(function(Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->where('email', 'LIKE', '%'.$value.'%');
                    }
                }),
        ];
    }

    public function actions(): array
    {
        return array_filter([
            auth()->user()->hasPermissionTo('Access - Akun - Menambah Data') ?
                Action::make('Tambah Data')
                    ->setActionAttributes([
                        'id' => 'btn_account_add',
                        'class' => 'btn w-100 btn-label-primary',
                        'data-bs-toggle '=> 'modal',
                        'data-bs-target' => '#modal_account_resource',
                        'default-colors' => false,
                        'default-styling' => false
                    ]) : null,
        ]);
    }
}
