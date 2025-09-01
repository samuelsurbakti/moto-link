<?php

namespace App\Helpers;

use App\Models\SLP\Permission;

class TableHelper
{
    public static function roles_in_permission($permission)
    {
        $return = '<span class="d-flex flex-wrap">';
        foreach ($permission->roles as $role) {
            $return .= '<span class="m-1 badge rounded-pill" style="background-color: color-mix(in sRGB, #fff 84%, #'.$role->badge_color.') !important; color: #'.$role->badge_color.' !important;">'.$role->name.'</span>';
        }
        $return .= '</span>';

        return $return;
    }

    public static function action_buttons(
        string $recordId,
        array $permissions,
        array $cssClasses,
        ?string $editModalTarget = null
    ): string {
        $editPermission = $permissions['edit'] ?? null;
        $deletePermission = $permissions['delete'] ?? null;

        // Cek apakah user memiliki setidaknya satu permission untuk menampilkan dropdown wrapper
        $canShowWrapper = (isset($editPermission) && auth()->user()->can($editPermission)) ||
                          (isset($deletePermission) && auth()->user()->can($deletePermission));

        if (!$canShowWrapper) {
            return ''; // Jika tidak ada permission, tidak ada tombol aksi yang ditampilkan
        }

        $actionButtonHtml = '
            <div class="d-flex justify-content-end">

        ';

        // Tombol Edit
        if (isset($editPermission) && auth()->user()->can($editPermission)) {
            $editButtonClass = $cssClasses['edit_btn'] ?? 'btn_edit'; // Default class
            $editModalAttr = $editModalTarget ? 'data-bs-toggle="modal" data-bs-target="'.$editModalTarget.'"' : '';
            $actionButtonHtml .= '
                <a href="javascript:;" class="btn btn-icon '.$editButtonClass.'" '.$editModalAttr.' value="'.$recordId.'"><i class="icon-base bx bx-edit icon-sm"></i></a>
            ';
        }

        // Tombol Hapus
        if (isset($deletePermission) && auth()->user()->can($deletePermission)) {
            $deleteButtonClass = $cssClasses['delete_btn'] ?? 'btn_delete'; // Default class
            $actionButtonHtml .= '
                <a href="javascript:;" class="btn btn-icon '.$deleteButtonClass.'" value="'.$recordId.'"><i class="icon-base bx bx-trash icon-sm"></i></a>
            ';
        }

        $actionButtonHtml .= '

            </div>
        ';

        return $actionButtonHtml;
    }
}
