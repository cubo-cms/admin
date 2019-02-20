<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

class View {
	protected $class;
	protected $_Attribute;
	
	public function __construct() {
		$this->class = basename(str_replace('\\','/',get_called_class()),'View');
	}
	
	// Call create method
	public function create(&$_Data) {
		// No need to store attributes; creating new ones
		$this->_Attribute = [];
		// Retrieve the template; we need the template attributes as they are global settings
		$_Template = Template::get($_Data->template ?? Configuration::getDefault('template','default'),"`name`,`@attribute`");
		if($_Template) {
			// Save template name as parameter
			Configuration::setParameter('template',$_Template->name);
			// Save template attributes as global settings
			!empty($_Template->{'@attribute'}) && Configuration::set('_Attribute',json_decode($_Template->{'@attribute'},true));
		}
		// Show heading
		$html = '<h1>Create '.ucwords($this->class).'</h1>';
		// Initiate form
		$_Definition = $this->getDefinition();
		$html .= '<form class="form-create" action="" method="post">';
		// Save and cancel buttons
		$routePath = Application::getController()->getRouter()->getRoutePath();
		$html .= '<div class="form-group">';
		$html .= '<button class="btn btn-success" id="submit" type="submit" disabled><i class="fa fa-check"></i> Save</button>';
		$html .= '<a href="'.$routePath.strtolower($this->class).'" class="btn btn-danger" id="cancel"><i class="fa fa-times"></i> Cancel</a>';
		$html .= '</div>';
		// Show title and name
		$html .= '<div class="grid-columns">';
		$html .= '<div class="form-group grid-column-2"><label for="title">Title</label><input type="text" name="title" id="title" class="form-control" placeholder="Title" required autofocus /></div>';
		$html .= '<div class="form-group"><label for="name">Alias</label><input type="text" name="name" id="name" class="form-control" placeholder="Alias" required /></div>';
		$html .= '</div>';
		// Show tabs
		$html .= '<ul class="nav nav-tabs" id="tabs" role="tablist">';
		foreach($_Definition->tabs as $tab) {
			$tab = (object)$tab;
			$html .= '<li class="nav-item"><a class="nav-link'.($tab->selected ? ' active' : '').'" id="'.$tab->tab.'" data-toggle="tab" href="#'.$tab->pane.'" role="tab" aria-controls="'.$tab->pane.'" aria-selected="'.($tab->selected ? 'true' : 'false').'">'.$tab->title.'</a></li>';
		}
		$html .= '</ul>';
		// Show panes
		$html .= '<div class="tab-content">';
		foreach($_Definition->tabs as $tab) {
			$tab = (object)$tab;
			$html .= '<div class="tab-pane fade'.($tab->selected ? ' show active' : '').'" id="'.$tab->pane.'" role="tabpanel" aria-labelledby="'.$tab->tab.'">';
			$html .= '<div class="row">';
			if(isset($_Definition->{$tab->pane}['columns'])) {			// ************ REMOVE
			$columns = $_Definition->{$tab->pane}['columns'];
			foreach($columns as $column=>$class) {
				$column = $_Definition->{$tab->pane}[$column];
				$html .= '<div class="'.$class.'">';
				foreach($column as $field) {
					$field = (object)$field;
					$html .= $this->showField($field);
				}
				$html .= '</div>';
			}
			}															// ************ REMOVE
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
		// Render plugins and return output
		return $this->renderPlugins($html);
	}
	
	public function showField($field) {
		$type = $field->type;
		return Form::$type($field);
	}
	
	// Call default method: list
	public function default(&$_Data) {
		return $this->list($_Data);
	}
	
	// Get attribute
	public function getAttribute($attribute) {
		return (isset($this->_Attribute[$attribute]) && $this->_Attribute[$attribute] != SETTING_GLOBAL ? $this->_Attribute[$attribute] : Configuration::getAttribute($attribute) ?? null);
	}
	
	// Get model class
	public function getClass() {
		return $this->class;
	}
	
	// Call list method
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
		$_AccessLevel = AccessLevel::get($item->accesslevel,"`#`,`title`");
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
		foreach($this->getFilters() as $filter=>$data) {
			switch($filter) {
				case 'text':
					$html .= Form::textFilter($data);
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
		$html .= '<div class="text-right align-middle"><a href="'.$routePath.strtolower($this->class).'/create" class="btn btn-sm btn-success'.(Application::getController()->canCreate() ? '' : ' disabled').'" tabindex="-1"><i class="fa fa-plus fa-fw"></i></a></div>';
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
		$html .= '<a href="'.$routePath.strtolower($this->class).'/edit/'.$item->{'#'}.'" class="btn btn-sm btn-warning'.(Application::getController()->canEdit($item->author) ? '' : ' disabled').'" tabindex="-1"><i class="fa fa-pen fa-fw"></i></a>';
		$html .= '<a href="'.$routePath.strtolower($this->class).'/trash/'.$item->{'#'}.'" class="btn btn-sm btn-danger'.(Application::getController()->canPublish() ? '' : ' disabled').'" tabindex="-1"><i class="fa fa-trash fa-fw"></i></a>';
		$html .= '</div></div>';
		return $html;
	}
	
	public function view(&$_Data) {
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
		$html = $this->showItem($_Data);
		// Render plugins and return output
		return $this->renderPlugins($html);
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