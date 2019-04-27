<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class home extends Model
{
    static $dataTableColumnMapping = [
        ['database'=>'id', 'label'=> '#'],
        ['database'=>'name', 'label'=> 'Name'],
        ['database'=>'address', 'label'=> 'Address'],
        ['database'=>'contactno', 'label'=> 'Contact No'],
        ['database'=>'annualincome', 'label'=> 'Income'],
        ['database'=>'age', 'label'=> 'Age'],
        ['database'=>'created_at', 'label'=> 'Created At'],
        ['database'=>'updated_at', 'label'=> 'Updated At']
    ];
}
