<?php namespace crocodicstudio\crudbooster\controllers;

use CRUDBooster;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\PDF;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use crocodicstudio\crudbooster\fonts\Fontawesome;

class MenusController extends CBController
{
    public function cbInit()
    {
        $this->table = "cms_menus";
        $this->primary_key = "id";
        $this->title_field = "name";
        $this->limit = 20;
        $this->orderby = ["id" => "desc"];

        $this->button_table_action = true;
        $this->button_action_style = "FALSE";
        $this->button_add = false;
        $this->button_delete = false;
        $this->button_edit = true;
        $this->button_detail = false;
        $this->button_show = false;
        $this->button_filter = false;
        $this->button_export = false;
        $this->button_import = false;

        $id = CRUDBooster::getCurrentId();
        if (Request::segment(3) == 'edit') {
            $id = Request::segment(4);
            Session::put('current_row_id', $id);
        }
        $row = CRUDBooster::first($this->table, $id);
        $row = (Request::segment(3) == 'edit') ? $row : null;

        $id_module = $id_statistic = 0;

        if ($row->type == 'Module') {
            $m = CRUDBooster::first('cms_moduls', ['path' => $row->path]);
            $id_module = $m->id;
        } elseif ($row->type == 'Statistic') {
            $row->path = str_replace('statistic_builder/show/', '', $row->path);
            $m = CRUDBooster::first('cms_statistics', ['slug' => $row->path]);
            $id_statistic = $m->id;
        }

        $this->script_js = "
			$(function() {
				var current_id = '$id';
				var current_type = '$row->type';
				var type_menu = $('input[name=type]').val();
				type_menu = (current_type)?current_type:type_menu;
				if(type_menu == 'Module') {
					$('#form-group-module_slug').show();
					$('#form-group-statistic_slug,#form-group-path').hide();
					$('#module_slug').prop('required',true);
					$('#form-group-module_slug label').append('<span class=\"text-danger\" title=\"".trans('crudbooster.this_field_is_required')."\">*</span>');
				}else if(type_menu == 'Statistic') {
					$('#form-group-statistic_slug').show();
					$('#module_slug').prop('required',false);
					$('#form-group-module_slug,#form-group-path').hide();
					$('#statistic_slug').prop('required',true);
					$('#form-group-statistic_slug label').append('<span class=\"text-danger\" title=\"".trans('crudbooster.this_field_is_required')."\">*</span>');
				}else{
					$('#module_slug').prop('required',false);
					$('#form-group-module_slug,#form-group-statistic_slug').hide();
					$('#form-group-path').show();
				}


				function format(icon) {          
	                  var originalOption = icon.element;
	                  var label = $(originalOption).text();
	                  var val = $(originalOption).val();
	                  if(!val) return label;
	                  var \$resp = $('<span><i style=\"margin-top:5px\" class=\"pull-right ' + $(originalOption).val() + '\"></i> ' + $(originalOption).data('label') + '</span>');
	                  return \$resp;
	              }
	              $('#list-icon').select2({
	                  width: \"100%\",
	                  templateResult: format,
	                  templateSelection: format
	              });

				$('input[name=type]').click(function() {
					var default_placeholder_path = 'NameController@methodName';
					var n = $(this).val();
					var isCheck = $(this).prop('checked');
					console.log('Click the module type '+n);
					$('#module_slug').prop('required',false);
					$('input[name=path]').attr('placeholder',default_placeholder_path);
					if(n == 'Module') {
						$('#form-group-path').hide();
						$('#form-group-statistic_slug').hide();
						$('#statistic_slug,#path').prop('required',false);

						$('#form-group-module_slug').show();
						$('#module_slug').prop('required',true);
						$('#form-group-module_slug label .text-danger').remove();
					}else if (n == 'Statistic') {
						$('#form-group-path').hide();
						$('#form-group-module_slug').hide();
						$('#module_slug,#path').prop('required',false);

						$('#form-group-statistic_slug').show();
						$('#statistic_slug').prop('required',true);
						$('#form-group-statistic_slug label .text-danger').remove();
						$('#form-group-statistic_slug label').append('<span class=\"text-danger\" title=\"".trans('crudbooster.this_field_is_required')."\">*</span>');
					}else if (n == 'URL') {
						$('input[name=path]').attr('placeholder','Please enter your URL');

						$('#path').prop('required',true);
						$('#form-group-path label .text-danger').remove();
						$('#form-group-path label').append('<span class=\"text-danger\" title=\"".trans('crudbooster.this_field_is_required')."\">*</span>');

						$('#form-group-path').show();
						$('#form-group-module_slug,#form-group-statistic_slug').hide();
					}else if (n == 'Route') {
						$('input[name=path]').attr('placeholder','Please enter the Route');

						$('#path').prop('required',true);
						$('#form-group-path label .text-danger').remove();
						$('#form-group-path label').append('<span class=\"text-danger\" title=\"".trans('crudbooster.this_field_is_required')."\">*</span>');

						$('#form-group-path').show();
						$('#form-group-module_slug,#form-group-statistic_slug').hide();					
					}else {
						$('#module_slug,#statistic_slug').prop('required',false);
						
						$('#path').prop('required',true);
						$('#form-group-path label .text-danger').remove();
						$('#form-group-path label').append('<span class=\"text-danger\" title=\"".trans('crudbooster.this_field_is_required')."\">*</span>');

						$('#form-group-path').show();
						$('#form-group-module_slug,#form-group-statistic_slug').hide();
					}
				})
			})
			";

        $this->col = [];
        $this->col[] = ["label" => "Nome", "name" => "name"];
        $this->col[] = ["label" => "Ativo?", "name" => "is_active"];
        $this->col[] = ["label" => "Privilégios", "name" => "id_cms_privileges", "join" => "cms_privileges,name"];

        $this->form = [];
        $this->form[] = [
            "label" => "Privilégios",
            "name" => "cms_menus_privileges",
            "type" => "select2",
            "select2_multiple" => true,
            "datatable" => "cms_privileges,name",
            "relationship_table" => "cms_menus_privileges",
            "required" => true,
        ];
        $this->form[] = [
            "label" => "Nome",
            "name" => "name",
            "type" => "text",
            "required" => true,
            "validation" => "required|min:3|unique:cms_menus,name|max:45"
        ];

        $fontawesome = Fontawesome::getIcons();

        $custom = view('crudbooster::components.list_icon', compact('fontawesome', 'row'))->render();
        $this->form[] = ['label' => 'Ícone', 'name' => 'icon', 'type' => 'custom', 'html' => $custom, 'required' => true,"validation" => "required|min:3|string|max:45"];
        $this->form[] = [
            'label' => 'Cor',
            'name' => 'color',
            'type' => 'select2',
            'dataenum' => ['normal', 'red', 'green', 'aqua', 'light-blue', 'red', 'yellow', 'muted'],
            'required' => true,
            "validation" => "required|min:3|string|max:45",
            'value' => 'normal',
        ];
        $this->form[] = [
            "label" => "Active",
            "name" => "is_active",
            "type" => "radio",
            "required" => true,
            "validation" => "required|boolean",
            "dataenum" => ['1|Ativo', '0|Inativo'],
            'value' => '1',
        ];

        $id_cms_privileges = Request::get('id_cms_privileges');
        $this->form[] = ["label" => "id_cms_privileges", "name" => "id_cms_privileges", "type" => "hidden", "value" => $id_cms_privileges];
    }

    public function getIndex()
    {
        $this->cbLoader();

        $module = CRUDBooster::getCurrentModule();
        if (! CRUDBooster::isView() && $this->global_privilege == false) {
            CRUDBooster::insertLog(trans('crudbooster.log_try_view', ['module' => $module->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }

        $privileges = DB::table('cms_privileges')->get();

        $id_cms_privileges = Request::get('id_cms_privileges');
        $id_cms_privileges = ($id_cms_privileges) ?: CRUDBooster::myPrivilegeId();

        $menu_active = DB::table('cms_menus')->where('parent_id', 0)->where('is_active', 1)->orderby('sorting', 'asc')->get();

        foreach ($menu_active as &$menu) {
            $child = DB::table('cms_menus')->where('is_active', 1)->where('parent_id', $menu->id)->orderby('sorting', 'asc')->get();
            if (count($child)) {
                $menu->children = $child;
            }
        }

        $menu_inactive = DB::table('cms_menus')->where('parent_id', 0)->where('is_active', 0)->orderby('sorting', 'asc')->get();

        foreach ($menu_inactive as &$menu) {
            $child = DB::table('cms_menus')->where('is_active', 1)->where('parent_id', $menu->id)->orderby('sorting', 'asc')->get();
            if (count($child)) {
                $menu->children = $child;
            }
        }

        $return_url = Request::fullUrl();

        $page_title = 'Menu Management';

        return view('crudbooster::menus_management', compact('menu_active', 'menu_inactive', 'privileges', 'id_cms_privileges', 'return_url', 'page_title'));
    }

    public function hook_before_add(&$postdata)
    {
        if (! $postdata['id_cms_privileges']) {
            $postdata['id_cms_privileges'] = CRUDBooster::myPrivilegeId();
        }
        $postdata['parent_id'] = 0;

        if ($postdata['type'] == 'Statistic') {
            $stat = CRUDBooster::first('cms_statistics', ['id' => $postdata['statistic_slug']]);
            $postdata['path'] = 'statistic_builder/show/'.$stat->slug;
        } elseif ($postdata['type'] == 'Module') {
            $stat = CRUDBooster::first('cms_moduls', ['id' => $postdata['module_slug']]);
            $postdata['path'] = $stat->path;
        }

        unset($postdata['module_slug']);
        unset($postdata['statistic_slug']);

        if ($postdata['is_dashboard'] == 1) {
            //If set dashboard, so unset for first all dashboard
            //DB::table('cms_menus')->where('id_cms_privileges', $postdata['id_cms_privileges'])->where('is_dashboard', 1)->update(['is_dashboard' => 0]);
            Cache::forget('sidebarDashboard'.CRUDBooster::myPrivilegeId());
        }
    }

    public function hook_before_edit(&$postdata, $id)
    {

        if ($postdata['is_dashboard'] == 1) {
            //If set dashboard, so unset for first all dashboard
            //DB::table('cms_menus')->where('id_cms_privileges', $postdata['id_cms_privileges'])->where('is_dashboard', 1)->update(['is_dashboard' => 0]);
            Cache::forget('sidebarDashboard'.CRUDBooster::myPrivilegeId());
        }

        if ($postdata['type'] == 'Statistic') {
            $stat = CRUDBooster::first('cms_statistics', ['id' => $postdata['statistic_slug']]);
            $postdata['path'] = 'statistic_builder/show/'.$stat->slug;
        } elseif ($postdata['type'] == 'Module') {
            $stat = CRUDBooster::first('cms_moduls', ['id' => $postdata['module_slug']]);
            $postdata['path'] = $stat->path;
        }

        unset($postdata['module_slug']);
        unset($postdata['statistic_slug']);
    }

    public function hook_after_delete($id)
    {
        DB::table('cms_menus')->where('parent_id', $id)->delete();
    }

    public function postSaveMenu()
    {
        $post = Request::input('menus');
        $isActive = Request::input('isActive');
        $post = json_decode($post, true);

        $i = 1;
        foreach ($post[0] as $ro) {
            $pid = $ro['id'];
            if ($ro['children'][0]) {
                $ci = 1;
                foreach ($ro['children'][0] as $c) {
                    $id = $c['id'];
                    DB::table('cms_menus')->where('id', $id)->update(['sorting' => $ci, 'parent_id' => $pid, 'is_active' => $isActive]);
                    $ci++;
                }
            }
            DB::table('cms_menus')->where('id', $pid)->update(['sorting' => $i, 'parent_id' => 0, 'is_active' => $isActive]);
            $i++;
        }

        return response()->json(['success' => true]);
    }
}
