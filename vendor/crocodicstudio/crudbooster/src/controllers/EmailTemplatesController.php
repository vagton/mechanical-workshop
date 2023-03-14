<?php namespace crocodicstudio\crudbooster\controllers;

use CRUDBooster;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\PDF;

class EmailTemplatesController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function cbInit()
    {
        $this->table = "cms_email_templates";
        $this->primary_key = "id";
        $this->title_field = "name";
        $this->limit = 20;
        $this->orderby = ["id" => "desc"];
        $this->global_privilege = false;

        $this->button_table_action = true;
        $this->button_action_style = "button_icon";
        $this->button_add = true;
        $this->button_delete = true;
        $this->button_edit = true;
        $this->button_detail = true;
        $this->button_show = false;
        $this->button_filter = false;
        $this->button_export = false;
        $this->button_import = false;
        $this->button_bulk_action = false;

        $this->col = [];
        $this->col[] = ["label" => "Nome do template", "name" => "name"];
        $this->col[] = ["label" => "Slug", "name" => "slug"];

        $this->form = [];
        $this->form[] = [
            "label" => "Nome do template",
            "name" => "name",
            "type" => "text",
            "required" => true,
            "validation" => "required|min:3|max:80|alpha_spaces",
            "placeholder" => "",
        ];
        $this->form[] = ["label" => "Slug", "type" => "text", "name" => "slug", "required" => true, 'validation' => 'required|string|max:45|unique:cms_email_templates,slug'];
        $this->form[] = ["label" => "Assunto", "name" => "subject", "type" => "text", "required" => true, "validation" => "required|min:3|max:190"];
        $this->form[] = ["label" => "Conteúdo", "name" => "content", "type" => "wysiwyg", "required" => true, "validation" => "required|string|max:12000"];
        $this->form[] = ["label" => "Descrição", "name" => "description", "type" => "text", "required" => true, "validation" => "min:3|max:190"];

        $this->form[] = [
            "label" => "From Name",
            "name" => "from_name",
            "type" => "text",
            "required" => false,
            "width" => "col-sm-6",
            "validation" => "alpha_spaces|max:190",
            "width" => "col-sm-6",
        ];
        $this->form[] = [
            "label" => "From Email",
            "name" => "from_email",
            "type" => "email",
            "required" => false,
            "validation" => "email|max:190",
            "width" => "col-sm-6",
        ];

        $this->form[] = [
            "label" => "Cc Email",
            "name" => "cc_email",
            "type" => "email",
            "required" => false,
            "validation" => "email|max:190",
            "width" => "col-sm-6",
        ];
    }
    //By the way, you can still create your own method in here... :)

}
