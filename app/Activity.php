<?php

namespace App;

use App\Company;
use App\Contact;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public static function get_activity($module_type,$module_id)
    {
        $result=['name'=>'-'];
        if($module_type=='contact')
        {
            $contact= Contact::where('id',$module_id)->orderBy('id','desc')->first();
            if($contact)
            {
                $result =['name' => $contact->name];
            }
        }
        elseif($module_type=='company')
        {
            $company= Company::where('id',$module_id)->orderBy('id','desc')->first();
            if($company)
                {
                    $result =['name' => $company->name];
                }
        }
        elseif($module_type=='employee')
        {
            $employee= HrmEmployee::where('id',$module_id)->orderBy('id','desc')->first();
            if($employee)
                {
                    $result =['name' => $employee->first_name.' '.$employee->last_name];
                }
        }
        return $result;
    }
    public function logIcon($type = '')
    {
        $icon = '';

        if(!empty($type))
        {
            if($type == 'Invite User')
            {
                $icon = 'fa-paper-plane';
            }
            else if($type == 'User Assigned to the Task')
            {
                $icon = 'fa-user-check';
            }
            else if($type == 'User Removed from the Task')
            {
                $icon = 'fa-user-times';
            }
            else if($type == 'Upload File')
            {
                $icon = 'fa-file-upload';
            }
            else if($type == 'Create Milestone')
            {
                $icon = 'fa-gem';
            }
            else if($type == 'Create Bug')
            {
                $icon = 'fa-bug';
            }
            else if($type == 'Create Task')
            {
                $icon = 'fa-plus';
            }
            else if($type == 'Move Task')
            {
                $icon = 'fa-arrows-alt';
            }
            else if($type == 'Create Expense')
            {
                $icon = 'fa-plus';
            }
            else if($type == 'Move')
            {
                $icon = 'fa-arrows-alt';
            }
            elseif($type == 'Add Product')
            {
                $icon = 'fa-dolly';
            }
            elseif($type == 'Upload File')
            {
                $icon = 'fa-file-alt';
            }
            elseif($type == 'Update Sources')
            {
                $icon = 'fa-pen';
            }
            elseif($type == 'Create Deal Call')
            {
                $icon = 'fa-phone';
            }
            elseif($type == 'Create Deal Email')
            {
                $icon = 'fa-envelope';
            }
            elseif($type == 'Create Invoice')
            {
                $icon = 'fa-file-invoice';
            }
            elseif($type == 'Add Contact')
            {
                $icon = 'fa-address-book';
            }
            elseif($type == 'Create Task')
            {
                $icon = 'fa-tasks';
            }
        }

        return $icon;
    }
}
