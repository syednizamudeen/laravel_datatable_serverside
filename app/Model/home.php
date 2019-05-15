<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class home extends Model
{
    static $dataTableColumnMapping = [
        ['database'=>'id', 'datatable'=>'id', 'label'=> '#' , 'type'=>'checkbox'],
        ['database'=>'name', 'datatable'=>'name', 'label'=> 'Name' , 'type'=>'text'],
        ['database'=>'address', 'datatable'=>'address', 'label'=> 'Address' , 'type'=>'text'],
        ['database'=>'contactno', 'datatable'=>'contactno', 'label'=> 'Contact No' , 'type'=>'text'],
        ['database'=>'annualincome', 'datatable'=>'annualincome', 'label'=> 'Income' , 'type'=>'text'],
        ['database'=>'age', 'datatable'=>'age', 'label'=> 'Age' , 'type'=>'text'],
        ['database'=>'created_at', 'datatable'=>'created_at', 'label'=> 'Created At' , 'type'=>'datetimerange'],
        ['database'=>'updated_at', 'datatable'=>'updated_at', 'label'=> 'Updated At' , 'type'=>'datetimerange']
    ];

    static $dataTableRelationalColumnMapping = [
        ['database'=>'homes.id', 'datatable'=>'id', 'label'=> '#' , 'type'=>'checkbox'],
        ['database'=>'name', 'datatable'=>'name', 'label'=> 'Name' , 'type'=>'text'],
        ['database'=>'address', 'datatable'=>'address', 'label'=> 'Address' , 'type'=>'text'],
        ['database'=>'contactno', 'datatable'=>'contactno', 'label'=> 'Contact No' , 'type'=>'text'],
        ['database'=>'details.email', 'datatable'=>'email', 'label'=> 'Email', 'type'=>'text'],
        ['database'=>'annualincome', 'datatable'=>'annualincome', 'label'=> 'Income' , 'type'=>'text'],
        ['database'=>'age', 'datatable'=>'age', 'label'=> 'Age' , 'type'=>'text'],
        ['database'=>'homes.created_at', 'datatable'=>'created_at', 'label'=> 'Created At' , 'type'=>'datetimerange'],
        ['database'=>'homes.updated_at', 'datatable'=>'updated_at', 'label'=> 'Updated At' , 'type'=>'datetimerange']
    ];

    public function detail()
    {
        return $this->hasOne('App\Model\Detail', 'home_id', 'id');
    }
}
