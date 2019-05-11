<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class home extends Model
{
    static $dataTableColumnMapping = [
        ['database'=>'id', 'label'=> '#' , 'type'=>'checkbox'],
        ['database'=>'name', 'label'=> 'Name' , 'type'=>'text'],
        ['database'=>'address', 'label'=> 'Address' , 'type'=>'text'],
        ['database'=>'contactno', 'label'=> 'Contact No' , 'type'=>'text'],
        ['database'=>'annualincome', 'label'=> 'Income' , 'type'=>'text'],
        ['database'=>'age', 'label'=> 'Age' , 'type'=>'text'],
        ['database'=>'created_at', 'label'=> 'Created At' , 'type'=>'datetimerange'],
        ['database'=>'updated_at', 'label'=> 'Updated At' , 'type'=>'datetimerange']
    ];

    static $dataTableRelationalColumnMapping = [
        ['database'=>'id', 'label'=> '#' , 'type'=>'checkbox'],
        ['database'=>'name', 'label'=> 'Name' , 'type'=>'text'],
        ['database'=>'address', 'label'=> 'Address' , 'type'=>'text'],
        ['database'=>'contactno', 'label'=> 'Contact No' , 'type'=>'text'],
        ['database'=>'detail.email', 'label'=> 'Email', 'type'=>'text'],
        ['database'=>'annualincome', 'label'=> 'Income' , 'type'=>'text'],
        ['database'=>'age', 'label'=> 'Age' , 'type'=>'text'],
        ['database'=>'created_at', 'label'=> 'Created At' , 'type'=>'datetimerange'],
        ['database'=>'updated_at', 'label'=> 'Updated At' , 'type'=>'datetimerange']
    ];

    public function detail()
    {
        return $this->hasOne('App\Model\Detail', 'home_id', 'id');
    }
}
