<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

class Form {
	// Return data time input
	public static function datetime(&$params) {
		$html = '<div class="form-group">';
		$html .= '<label for="'.$params->name.'">'.$params->title.'</label>';
		$html .= '<input id="'.$params->name.'" name="'.($params->prefix ?? '').str_replace('-','_',$params->name).'" type="'.($params->type ?? 'text').'" class="'.$params->class.'" placeholder="'.$params->title.'" value="'.($params->value ?? $params->default ?? '').'"'.(isset($params->readonly) && $params->readonly ? ' readonly' : '').' />';
		$html .= '</div>';
		return $html;
	}
	
	// Return hidden input
	public static function hidden(&$params) {
		$html = '<input id="'.$params->name.'" name="'.($params->prefix ?? '').str_replace('-','_',$params->name).'" type="'.($params->type ?? 'hidden').'" value="'.($params->value ?? $params->default ?? '').'" />';
		return $html;
	}
	
	// Preview image
	public static function previewImage(&$params) {
		$_Image = Image::get($params->value,"`#`,`accesslevel`,`name`,`status`,`title`",1);
		$html = '<div class="form-group">';
		$html .= '<label for="'.$params->name.'">'.$params->title.'</label>';
		if($_Image) {
			$html .= '<figure id="image-preview" style="width:100%;font-size:10rem;background-color:lightgray;color:silver;text-align:center;padding:2rem;"><img class="img-fluid img-thumbnail w-100" src="/image/'.$_Image->name.'" /></figure>';
		} else {
			$html .= '<figure id="image-preview" style="width:100%;font-size:10rem;background-color:lightgray;color:silver;text-align:center;padding:2rem;"><i class="fa fa-image"></i></figure>';
		}
		$html .= '</div>';
		return $html;
	}
	
	// Return query
	public static function query($class,$filter = "1",$order = "`title`") {
		return "SELECT `#`,`title` FROM `{$class}` WHERE {$filter} ORDER BY {$order}";
	}
	
	// Return selection
	public static function select(&$params) {
		!is_array($params) || $params = (object)$params;
		$html = '<div class="'.($params->{'group-class'} ?? 'form-group').'">';
		$html .= '<label for="'.$params->name.'">'.$params->title.'</label>';
		$html .= '<select id="'.$params->name.'" name="'.($params->prefix ?? '').(isset($params->prefix) && $params->prefix != '@' ? str_replace('-','_',$params->name) : $params->name).'" class="'.$params->class.'"'.(isset($params->readonly) && $params->readonly ? ' readonly' : '').'>';
		$items = [];
		if(isset($params->query)) {
			$_Model = new Model;
			$items = $_Model->getDB()->loadItems($params->query);
		}
		if(!empty($params->list)) {
			$items = array_merge($params->list,$items);
		}
		foreach($items as $item) {
			$item = (object)$item;
			$html .= '<option value="'.$item->{'#'}.'"'.($item->{'#'} == ($params->value ?? $params->default) ? ' selected' : '').'>'.$item->title.'</option>';
		}
		$html .= '</select>';
		$html .= '</div>';
		return $html;
	}
	
	// Return filter selection
	public static function selectFilter(&$params) {
		!is_array($params) || $params = (object)$params;
		return self::select($params);
	}
	
	// Open image selection
	public static function selectImage(&$params) {
		$_Image = Image::get($params->value,"`#`,`name`,`accesslevel`,`status`,`title`",1);
		$html = '<div class="form-group">';
		$html .= '<label for="'.$params->name.'">'.$params->title.'</label>';
		$html .= '<div class="form-inline">';
		$html .= '<input id="'.$params->name.'" name="'.($params->prefix ?? '').str_replace('-','_',$params->name).'" type="hidden" value="'.($params->value ?? $params->default ?? '').'" />';
		$html .= '<input name="'.$params->name.'-placeholder" type="text" class="'.$params->class.' flex-fill" placeholder="'.$params->title.'" value="'.($_Image->name ?? '').'" readonly />';
		$html .= '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#image-modal"><i class="fa fa-search"></i></button>';
		$html .= '</div>';
		$html .= '</div>';
		// Start modal
		$html .= '<div class="modal fade" id="image-modal" tab-index="-1" role="dialog">';
		$html .= '<div class="modal-dialog modal-lg" role="document">';
		$html .= '<div class="modal-content">';
		// Modal header
		$html .= '<div class="modal-header">';
		$html .= '<h2 class="modal-title">'.$params->title.'</h2>';
		$html .= '<button type="button" class="close text-danger" data-dismiss="modal" aria-label="close"><i class="fa fa-times"></i></button>';
		$html .= '</div>';
		// Modal body
		$html .= '<div class="modal-body">';
		$html .= '<form class="form">';
		$html .= '<div class="form-row d-flex justify-content-between flex-nowrap">';
		$params->{'image-filter'}['text-filter'] = ['name'=>'filter-text','prefix'=>'','title'=>'Search','value'=>''];
		$html .= self::textFilter($params->{'image-filter'}['text-filter']);
		$params->{'image-filter'}['status-filter'] = ['name'=>'filter-status','class'=>'form-control','group-class'=>'form-group col','list'=>[['#'=>STATUS_ANY,'title'=>'Any status']],'prefix'=>'','query'=>Form::query('status',Session::isAccessible()),'title'=>'Status','value'=>STATUS_PUBLISHED];
		$html .= self::selectFilter($params->{'image-filter'}['status-filter']);
		$params->{'image-filter'}['category-filter'] = ['name'=>'filter-category','class'=>'form-control','group-class'=>'form-group col','list'=>[['#'=>CATEGORY_ANY,'title'=>'Any category']],'prefix'=>'','query'=>Form::query('category',Session::isAccessible()),'title'=>'Category','value'=>CATEGORY_ANY];
		$html .= self::selectFilter($params->{'image-filter'}['category-filter']);
		$params->{'image-filter'}['language-filter'] = ['name'=>'filter-language','class'=>'form-control','group-class'=>'form-group col','list'=>[['#'=>LANGUAGE_ANY,'title'=>'Any language']],'prefix'=>'','query'=>Form::query('language',Session::isAccessible()),'title'=>'Language','value'=>LANGUAGE_ANY];
		$html .= self::selectFilter($params->{'image-filter'}['language-filter']);
		$html .= '</div>';
		$html .= '</form>';
		$html .= '<p id="filter-info"></p>';
		$html .= '<div class="d-flex justify-space-evenly flex-wrap">';
		// Render thumbnails
		$_Images = Image::getAll("`#`,`name`,`accesslevel`,`category`,`status`,`title`");
		foreach($_Images as $image) {
			$image = (object)$image;
			$html .= '<figure class="table-item img-thumbnail img-selectable d-none" data-item="'.htmlentities(json_encode($image)).'" data-target="#image" data-preview="#image-preview" data-dismiss="modal" data-filter="none">';
			$html .= '<img class="img-thumbnail" src="/image/'.$image->{'#'}.'?thumbnail&cache=no" />';
			$html .= '<figcaption>'.$image->title.'</figcaption>';
			$html .= '</figure>';
		}
		$html .= '</div>';
		$html .= '</div>';
		// Modal footer
		$html .= '<div class="modal-footer">';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="close">Cancel</button>';
		$html .= '</div>';
		// End modal
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		// Add script
		$route = Application::getController()->getRouter()->getRoute();
		Configuration::addScript($route.'js/filtering.js');
		return $html;
	}
	
	// Return text input
	public static function text(&$params) {
		$html = '<div class="form-group">';
		$html .= '<label for="'.$params->name.'">'.$params->title.'</label>';
		$html .= '<input id="'.$params->name.'" name="'.($params->prefix ?? '').str_replace('-','_',$params->name).'" type="'.($params->type ?? 'text').'" class="'.$params->class.'" placeholder="'.$params->title.'" value="'.($params->value ?? $params->default ?? '').'"'.(isset($params->readonly) && $params->readonly ? ' readonly' : '').' />';
		$html .= '</div>';
		return $html;
	}
	
	// Return textarea
	public static function textarea(&$params) {
		!is_array($params) || $params = (object)$params;
		$html = '<div class="form-group'.(isset($params->width) ? ' grid-column-'.$params->width : '').'">';
		$html .= '<label for="'.$params->name.'">'.$params->title.'</label>';
		$html .= '<textarea id="'.$params->name.'" name="'.($params->prefix ?? '').str_replace('-','_',$params->name).'" class="'.$params->class.'" placeholder="'.$params->title.'" rows="'.$params->size.'"'.(isset($params->readonly) && $params->readonly ? ' readonly' : '').(isset($params->required) && $params->required ? ' required' : '').'>'.($params->value ?? $params->default ?? '').'</textarea>';
		$html .= '</div>';
		return $html;
	}
	
	// Filter for text search
	public static function textFilter(&$params) {
		!is_array($params) || $params = (object)$params;
		return '<div class="form-group col"><label for="'.$params->name.'">'.$params->title.'</label><div class="form-inline flex-nowrap"><input id="'.$params->name.'" name="'.$params->prefix.str_replace('-','_',$params->name).'" class="form-control flex-fill" type="text" placeholder="'.$params->title.'" value="'.$params->value.'" /><button type="button" class="btn btn-primary"><i class="fa fa-search"></i></button></div></div>';
	}
}
?>