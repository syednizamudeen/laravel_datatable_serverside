<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class home extends Model
{
    static $dataTableColumnMapping = [
        ['database'=>'id', 'label'=> '#' , 'type'=>'text'],
        ['database'=>'name', 'label'=> 'Name' , 'type'=>'text'],
        ['database'=>'address', 'label'=> 'Address' , 'type'=>'text'],
        ['database'=>'contactno', 'label'=> 'Contact No' , 'type'=>'text'],
        ['database'=>'annualincome', 'label'=> 'Income' , 'type'=>'text'],
        ['database'=>'age', 'label'=> 'Age' , 'type'=>'text'],
        ['database'=>'created_at', 'label'=> 'Created At' , 'type'=>'text'],
        ['database'=>'updated_at', 'label'=> 'Updated At' , 'type'=>'text']
    ];
}
