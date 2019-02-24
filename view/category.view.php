<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

class CategoryView extends View {
	protected $listColumns = "title,status,parent,language,accesslevel";
	
	// Form definition
	protected function getDefinition(&$_Data = null) {
		$new = is_null($_Data);
		return (object)[
			'list'=>[
				'filters'=>[
					'text'=>[ 'name'=>'filter-text', 'prefix'=>'', 'title'=>'Search', 'type'=>'textFilter', 'value'=>'' ],
					'status'=>[ 'name'=>'filter-status', 'class'=>'form-control', 'group-class'=>'form-group col d-none d-md-block', 'list'=>[['#'=>STATUS_ANY,'title'=>'Any status']], 'query'=>Form::query('status',Session::isAccessible()), 'title'=>'Status', 'type'=>'selectFilter', 'value'=>STATUS_PUBLISHED ],
					'parent'=>[ 'name'=>'filter-category', 'class'=>'form-control', 'group-class'=>'form-group col d-none d-lg-block', 'list'=>[['#'=>CATEGORY_ANY,'title'=>'Any category'],['#'=>CATEGORY_NONE,'title'=>'Root category']], 'query'=>Form::query('category',Session::isAccessible()), 'title'=>'Parent Category', 'type'=>'selectFilter', 'value'=>CATEGORY_ANY ],
					'language'=>[ 'name'=>'filter-language', 'class'=>'form-control', 'group-class'=>'form-group col d-none d-lg-block', 'list'=>[['#'=>LANGUAGE_ANY,'title'=>'Any language']], 'query'=>Form::query('language',Session::isAccessible()), 'title'=>'Language', 'type'=>'selectFilter', 'value'=>LANGUAGE_ANY ],
					'accesslevel'=>[ 'name'=>'filter-accesslevel', 'class'=>'form-control', 'group-class'=>'form-group col d-none d-lg-block', 'list'=>[['#'=>ACCESS_ANY,'title'=>'Any access level']], 'query'=>Form::query('accesslevel',Session::isAccessible()), 'title'=>'Access Level', 'type'=>'selectFilter', 'value'=>ACCESS_ANY ]
				],
				'columns'=>[ 'title'=>'d-table-cell','status'=>'d-none d-md-table-cell','parent'=>'d-none d-lg-table-cell','language'=>'d-none d-lg-table-cell','accesslevel'=>'d-none d-lg-table-cell' ],
				'title'=>[ 'title'=>'Title', 'class'=>'', 'value'=>'showName' ],
				'status'=>[ 'title'=>'Status', 'class'=>'d-none d-md-table-cell', 'value'=>'showStatus' ],
				'parent'=>[ 'title'=>'Parent Category', 'class'=>'d-none d-lg-table-cell', 'value'=>'showCategory' ],
				'language'=>[ 'title'=>'Language', 'class'=>'d-none d-lg-table-cell', 'value'=>'showLanguage' ],
				'accesslevel'=>[ 'title'=>'Access Level', 'class'=>'d-none d-lg-table-cell', 'value'=>'showAccesslevel' ]
			],
			'top'=>[
				'columns'=>[ 'left'=>'col-8','right'=>'col-4' ],
				'left'=>[
					[ 'name'=>'id', 'prefix'=>($new ? '' : ':'), 'type'=>'hidden', 'value'=>($_Data->{'#'} ?? '') ],
					[ 'name'=>'title', 'class'=>'form-control', 'prefix'=>($new ? ':' : ''), 'required'=>true, 'title'=>'Title', 'type'=>'text', 'value'=>($_Data->title ?? '') ]
				],
				'right'=>[
					[ 'name'=>'name', 'class'=>'form-control', 'prefix'=>($new ? ':' : ''), 'required'=>true, 'title'=>'Alias', 'type'=>'text', 'value'=>($_Data->name ?? '') ]
				]
			],
			'tabs'=>[
				[ 'tab'=>'content-tab', 'pane'=>'content-pane', 'selected'=>true, 'title'=>'Content' ],
				[ 'tab'=>'image-tab', 'pane'=>'image-pane', 'selected'=>false, 'title'=>'Image and Metadata' ],
				[ 'tab'=>'publishing-tab', 'pane'=>'publishing-pane', 'selected'=>false, 'title'=>'Publishing' ],
				[ 'tab'=>'view-options-tab', 'pane'=>'view-options-pane', 'selected'=>false, 'title'=>'View Options' ]
			],
			'content-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-8','right'=>'col-12 col-md-4' ],
				'left'=>[
					[ 'name'=>'body', 'class'=>'form-control text-html', 'size'=>12, 'title'=>ucwords($this->class).' Body', 'type'=>'textarea', 'value'=>($_Data->body ?? '') ]
				],
				'right'=>[
					[ 'name'=>'status', 'class'=>'form-control form-control-sm', 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('status',Session::isAccessible()), 'readonly'=>Application::getController()->cannotPublish(), 'title'=>'Status', 'type'=>'select', 'value'=>($_Data->status ?? (Application::getController()->canPublish() ? STATUS_PUBLISHED : STATUS_UNPUBLISHED)) ],
					[ 'name'=>'parent', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>CATEGORY_NONE,'title'=>'Root category']], 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('category',Session::isAccessible()), 'title'=>'Category', 'type'=>'select', 'value'=>($_Data->category ?? CATEGORY_NONE) ],
					[ 'name'=>'language', 'class'=>'form-control form-control-sm', 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('language',Session::isAccessible()), 'title'=>'Language', 'type'=>'select', 'value'=>($_Data->language ?? LANGUAGE_UNDEFINED) ],
					[ 'name'=>'accesslevel', 'class'=>'form-control form-control-sm', 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('accesslevel',Session::isAccessible()), 'title'=>'Access Level', 'type'=>'select', 'value'=>($_Data->accesslevel ?? ACCESS_PUBLIC) ]
				]
			],
			'image-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-6','right'=>'col-12 col-md-6' ],
				'left'=>[
					[ 'name'=>'description', 'class'=>'form-control', 'prefix'=>($new ? ':' : ''), 'required'=>false, 'size'=>4, 'title'=>ucwords($this->class).' Summary', 'type'=>'textarea', 'value'=>($_Data->description ?? '') ]
				],
				'right'=>[
					[ 'name'=>'tags', 'class'=>'form-control', 'prefix'=>($new ? ':' : ''), 'required'=>false, 'size'=>4, 'title'=>'Tags', 'type'=>'textarea', 'value'=>($_Data->description ?? '') ]
				]
			],
			'publishing-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-6','right'=>'col-12 col-md-6' ],
				'left'=>[
					[ 'name'=>'author', 'class'=>'form-control form-control-sm', 'query'=>Form::query('user',Session::isAccessible()), 'readonly'=>(Application::getController()->cannotEdit($_Data->author ?? Session::getUser()) ?? true), 'title'=>'Author', 'type'=>'select', 'value'=>($_Data->author ?? Session::getUser()) ],
					[ 'name'=>'editor', 'class'=>'form-control form-control-sm', 'query'=>Form::query('user',Session::isAccessible()), 'readonly'=>true, 'title'=>'Editor', 'type'=>'select', 'value'=>(Session::getUser() ?? USER_NOBODY) ],
					[ 'name'=>'publisher', 'class'=>'form-control form-control-sm', 'query'=>Form::query('user',Session::isAccessible()), 'readonly'=>true, 'title'=>'Publisher', 'type'=>'select', 'value'=>($_Data->publisher ?? USER_NOBODY) ]
				],
				'right'=>[
					[ 'name'=>'created', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'title'=>'Created', 'type'=>'datetime', 'value'=>($_Data->created ?? '(now)') ],
					[ 'name'=>'modified', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'title'=>'Modified', 'type'=>'datetime', 'value'=> ($new ? '(never)' : '(now)') ],
					[ 'name'=>'published', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'title'=>'Published', 'type'=>'datetime', 'value'=> ($new ? '(never)' : '(now)') ]
				]
			],
			'view-options-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-6','right'=>'col-12 col-md-6' ],
				'left'=>[
					[ 'name'=>'show-title', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Title', 'type'=>'select', 'value'=>($this->_Attribute['show-title'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'show-author', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_AUTHOR,'title'=>'Show Author'],['#'=>SETTING_EDITOR,'title'=>'Show Editor'],['#'=>SETTING_PUBLISHER,'title'=>'Show Publisher']], 'prefix'=>'@', 'title'=>'Show Author', 'type'=>'select', 'value'=>($this->_Attribute['show-author'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'show-category', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Category', 'type'=>'select', 'value'=>($this->_Attribute['show-category'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'show-tags', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Tags', 'type'=>'select', 'value'=>($this->_Attribute['show-tags'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'show-date', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_CREATEDDATE,'title'=>'Show Created Date'],['#'=>SETTING_MODIFIEDDATE,'title'=>'Show Modified Date'],['#'=>SETTING_PUBLISHEDDATE,'title'=>'Show Published Date']], 'prefix'=>'@', 'title'=>'Show Date', 'type'=>'select', 'value'=>($this->_Attribute['show-date'] ?? SETTING_GLOBAL) ]
				],
				'right'=>[
					[ 'name'=>'show-image', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Image', 'type'=>'select', 'value'=>($this->_Attribute['show-image'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'position-image', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_ABOVETITLE,'title'=>'Above Title'],['#'=>SETTING_BELOWTITLE,'title'=>'Below Title'],['#'=>SETTING_FLOATLEFT,'title'=>'Float Left'],['#'=>SETTING_FLOATRIGHT,'title'=>'Float Right']], 'prefix'=>'@', 'title'=>'Image Position', 'type'=>'select', 'value'=>($this->_Attribute['position-image'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'show-info', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Info', 'type'=>'select', 'value'=>($this->_Attribute['show-info'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'position-info', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_ABOVECONTENT,'title'=>'Above Content'],['#'=>SETTING_ABOVETITLE,'title'=>'Above Title'],['#'=>SETTING_BELOWTITLE,'title'=>'Below Title'],['#'=>SETTING_BELOWCONTENT,'title'=>'Below Content']], 'prefix'=>'@', 'title'=>'Info Position', 'type'=>'select', 'value'=>($this->_Attribute['position-info'] ?? SETTING_GLOBAL) ],
					[ 'name'=>'show-readmore', 'class'=>'form-control form-control-sm', 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Read More', 'type'=>'select', 'value'=>($this->_Attribute['show-readmore'] ?? SETTING_GLOBAL) ]
				]
			]
		];
	}
}
?>