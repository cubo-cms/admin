<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

class View {
	protected $class;
	protected $_Attribute;
	
	public function __construct() {
		$this->class = basename(str_replace('\\','/',get_called_class()),'View');
	}
	
	// Get attribute
	public function getAttribute($attribute) {
		return (isset($this->_Attribute[$attribute]) && $this->_Attribute[$attribute] != SETTING_GLOBAL ? $this->_Attribute[$attribute] : Configuration::getAttribute($attribute) ?? null);
	}
	
	// Get model class
	public function getClass() {
		return $this->class;
	}
	
	public function default(&$_Data) {
		return $this->html($_Data);
	}
	
	public function html(&$_Data) {
		// Store the article attributes
		if(empty($this->_Attribute))
			!empty($_Data->{'@attribute'}) && $this->_Attribute = json_decode($_Data->{'@attribute'},true);
		// Retrieve the template; we need the template attributes as they are global settings
		$_Template = Template::get($_Data->template ?? Configuration::getDefault('template','default'),"`name`,`@attribute`");
		if($_Template) {
			// Save template name as parameter
			Configuration::setParameter('template',$_Template->name);
			// Save template attributes as global settings
			!empty($_Template->{'@attribute'}) && Configuration::set('_Attribute',json_decode($_Template->{'@attribute'},true));
		}
		// Get the body of the article
		if(is_array($_Data)) {
			$html = $this->showList($_Data);
		} else {
			$html = $this->showItem($_Data);
		}
		// Render plugins and return output
		return $this->renderPlugins($html);
	}
	
	public function list(&$_Data) {
		// Store the article attributes
		if(empty($this->_Attribute))
			!empty($_Data->{'@attribute'}) && $this->_Attribute = json_decode($_Data->{'@attribute'},true);
		// Retrieve the template; we need the template attributes as they are global settings
		$_Template = Template::get($_Data->template ?? Configuration::getDefault('template','default'),"`name`,`@attribute`");
		if($_Template) {
			// Save template name as parameter
			Configuration::setParameter('template',$_Template->name);
			// Save template attributes as global settings
			!empty($_Template->{'@attribute'}) && Configuration::set('_Attribute',json_decode($_Template->{'@attribute'},true));
		}
		// Show heading
		$html = '<h1>'.ucwords($this->class).' List</h1>';
		// Show filters
		$html .= $this->showFilters();
		// Show filter info
		$html .= '<p id="filter-info"></p>';
		// Begin table
		$html .= '<div class="grid-rows">';
		// Show table headers
		$html .= $this->showTableHeaders();
		// Show table rows
		foreach($_Data as $row) {
			$html .= $this->showTableRow($row);
		}
		// End table
		$html .= '</div>';
		// Add script
		$routePath = Application::getController()->getRouter()->getRoutePath();
		Configuration::addScript($routePath.'js/filtering.js');
		// Render plugins and return output
		return $this->renderPlugins($html);
	}
	
	public function renderPlugins($html) {
		// Render plugins
		$_Plugins = Plugin::getAll();
		foreach($_Plugins as &$_Plugin) {
			$plugin = __CUBO__.'\\'.$_Plugin->name.'plugin';
			if(class_exists($plugin))
				$html = $plugin::render($html);
		}
		return $html;
	}
	
	// Show access level
	public function showAccessLevel(&$item) {
		$_AccessLevel = Category::get($item->accesslevel,"`#`,`title`");
		return $_AccessLevel->title ?? false;
	}
	
	// Show category
	public function showCategory(&$item) {
		$_Category = Category::get($item->category,"`#`,`title`");
		return $_Category->title ?? false;
	}
	
	public function showFilters() {
		$html = '<form id="filter-form" class="form">';
		$html .= '<div class="grid-columns">';
		foreach($this->listFilters() as $filter=>$data) {
			switch($filter) {
				case 'text':
					$html .= Form::textFilter(array('id'=>'filter-text','label'=>'Search','prefix'=>'','value'=>''));
					break;
				default:
					$html .= Form::selectFilter($data);
			}
		}
		$html .= '</div>';
		$html .= '</form>';
		return $html;
	}
	
	// Show language
	public function showLanguage(&$item) {
		$_Language = Language::get($item->language,"`#`,`title`");
		return $_Language->title ?? false;
	}
	
	// Show status
	public function showStatus(&$item) {
		$_Status = Status::get($item->status,"`#`,`title`");
		return $_Status->title ?? false;
	}
	
	public function showTableHeaders() {
		$routePath = Application::getController()->getRouter()->getRoutePath();
		$html = '<div class="grid-columns row-header">';
		foreach(explode(',',$this->listColumns) as $column) {
			$html .= '<div class="align-middle"><strong>'.ucwords($column).'</strong></div>';
		}
		$html .= '<div class="text-right align-middle"><a href="'.$routePath.$this->class.'/create" class="btn btn-sm btn-success'.(Application::getController()->canCreate() ? '' : ' disabled').'" tabindex="-1"><i class="fa fa-plus fa-fw"></i></a></div>';
		$html .= '</div>';
		return $html;
	}
	
	public function showTableRow(&$item) {
		$routePath = Application::getController()->getRouter()->getRoutePath();
		$html = '<div class="table-item d-none grid-columns row-body" data-item="'.htmlentities(json_encode($item)).'" data-filter="none">';
		$html .= '<div class="align-middle">'.$item->title.'</div>';
		$html .= '<div class="align-middle">'.$this->showStatus($item).'</div>';
		$html .= '<div class="align-middle">'.$this->showCategory($item).'</div>';
		$html .= '<div class="align-middle">'.$this->showLanguage($item).'</div>';
		$html .= '<div class="align-middle">'.$this->showAccessLevel($item).'</div>';
		$html .= '<div class="text-right align-middle">';
		$html .= '<a href="'.$routePath.$this->class.'/edit/'.$item->{'#'}.'" class="btn btn-sm btn-warning'.(Application::getController()->canEdit($item->author) ? '' : ' disabled').'" tabindex="-1"><i class="fa fa-pen fa-fw"></i></a>';
		$html .= '<a href="'.$routePath.$this->class.'/trash/'.$item->{'#'}.'" class="btn btn-sm btn-danger'.(Application::getController()->canPublish() ? '' : ' disabled').'" tabindex="-1"><i class="fa fa-trash fa-fw"></i></a>';
		$html .= '</div></div>';
		return $html;
	}
	
	// Shared function to show item text in uniform way
	public function showBody(&$_Data) {
		return $_Data->body ?? '';
	}
	
	// Shared function to show item image in uniform way
	public function showImage(&$_Data) {
		$html = '';
		if($_Image = Image::get($_Data->image,'name,title')) {
			$html = '<img class="article-image" src="'.__BASE__.'/image/'.urlencode($_Image->name).'" alt="'.htmlspecialchars($_Image->title,ENT_QUOTES|ENT_HTML5).'" />';
		}
		return $html;
	}
	
	// Shared function to show an item
	public function showItem(&$_Data) {
		$html = '<div class="article">';
		$html = '<h1 class="article-title">'.$this->showTitle($_Data).'</h1>';
		$html .= '<div class="article-body">'.$this->showBody($_Data).'</div>';
		return $html;
		$html .= '<div class="article-body">'.$this->showBody($_Data).'</div>';
		return $html;
	}
	
	// Shared function to show a list of items
	public function showList(&$_Data) {
		$html = '<ul class="item-list">';
		foreach($this->_Data as $item)
			$html .= '<li class="list-item">'.$this->showItem($item).'</li>';
		$html .= '</ul>';
		return $html;
	}
	
	// Shared function to show item heading in uniform way
	public function showTitle(&$_Data) {
		return '<h1 class="article-title">'.htmlspecialchars($_Data->title,ENT_QUOTES|ENT_HTML5).'</h1>';
	}
}
?>