<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

class ArticleView extends View {
	protected $listColumns = "title,status,category,language,accesslevel";
	
	// Form definition
	public function getDefinition($new = false) {
		return (object)[
			'tabs'=>[
				[ 'tab'=>'content-tab', 'pane'=>'content-pane', 'selected'=>true, 'title'=>'Content' ],
				[ 'tab'=>'image-tab', 'pane'=>'image-pane', 'selected'=>false, 'title'=>'Image and Metadata' ],
				[ 'tab'=>'publishing-tab', 'pane'=>'publishing-pane', 'selected'=>false, 'title'=>'Publishing' ],
				[ 'tab'=>'view-options-tab', 'pane'=>'view-options-pane', 'selected'=>false, 'title'=>'View Options' ]
			],
			'content-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-8','right'=>'col-12 col-md-4' ],
				'left'=>[
					[ 'name'=>'intro', 'class'=>'form-control text-html', 'required'=>true, 'size'=>4, 'title'=>ucwords($this->class).' Intro', 'type'=>'textarea' ],
					[ 'name'=>'body', 'class'=>'form-control text-html', 'required'=>true, 'size'=>8, 'title'=>ucwords($this->class).' Body', 'type'=>'textarea' ]
				],
				'right'=>[
					[ 'name'=>'status', 'class'=>'form-control form-control-sm', 'default'=>Application::getController()->canPublish() ? STATUS_PUBLISHED : STATUS_UNPUBLISHED, 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('status',Session::isAccessible()), 'readonly'=>Application::getController()->cannotPublish(), 'title'=>'Status', 'type'=>'select' ],
					[ 'name'=>'category', 'class'=>'form-control form-control-sm', 'default'=>CATEGORY_UNDEFINED, 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('category',Session::isAccessible()), 'title'=>'Category', 'type'=>'select' ],
					[ 'name'=>'language', 'class'=>'form-control form-control-sm', 'default'=>LANGUAGE_UNDEFINED, 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('language',Session::isAccessible()), 'title'=>'Language', 'type'=>'select' ],
					[ 'name'=>'accesslevel', 'class'=>'form-control form-control-sm', 'default'=>ACCESS_PUBLIC, 'prefix'=>($new ? ':' : ''), 'query'=>Form::query('accesslevel',Session::isAccessible()), 'title'=>'Access Level', 'type'=>'select' ]
				]
			],
			'image-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-6','right'=>'col-12 col-md-6' ],
				'left'=>[
					[ 'name'=>'description', 'class'=>'form-control', 'prefix'=>($new ? ':' : ''), 'required'=>false, 'size'=>4, 'title'=>ucwords($this->class).' Summary', 'type'=>'textarea' ]
				],
				'right'=>[
					[ 'name'=>'tags', 'class'=>'form-control', 'prefix'=>($new ? ':' : ''), 'required'=>false, 'size'=>4, 'title'=>'Tags', 'type'=>'textarea' ]
				]
			],
			'publishing-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-6','right'=>'col-12 col-md-6' ],
				'left'=>[
					[ 'name'=>'author', 'class'=>'form-control form-control-sm', 'default'=>Session::getUser(), 'query'=>Form::query('user',Session::isAccessible()), 'readonly'=>true, 'title'=>'Author', 'type'=>'select' ],
					[ 'name'=>'editor', 'class'=>'form-control form-control-sm', 'default'=>USER_NOBODY, 'query'=>Form::query('user',Session::isAccessible()), 'readonly'=>true, 'title'=>'Editor', 'type'=>'select' ],
					[ 'name'=>'publisher', 'class'=>'form-control form-control-sm', 'default'=>USER_NOBODY, 'query'=>Form::query('user',Session::isAccessible()), 'readonly'=>true, 'title'=>'Publisher', 'type'=>'select' ]
				],
				'right'=>[
					[ 'name'=>'created', 'class'=>'form-control form-control-sm', 'default'=>'', 'readonly'=>true, 'title'=>'Created', 'type'=>'datetime' ],
					[ 'name'=>'modified', 'class'=>'form-control form-control-sm', 'default'=>'', 'readonly'=>true, 'title'=>'Modified', 'type'=>'datetime' ],
					[ 'name'=>'published', 'class'=>'form-control form-control-sm', 'default'=>'', 'readonly'=>true, 'title'=>'Published', 'type'=>'datetime' ]
				]
			],
			'view-options-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-md-6','right'=>'col-12 col-md-6' ],
				'left'=>[
					[ 'name'=>'show-title', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Title', 'type'=>'select' ],
					[ 'name'=>'show-author', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_AUTHOR,'title'=>'Show Author'],['#'=>SETTING_EDITOR,'title'=>'Show Editor'],['#'=>SETTING_PUBLISHER,'title'=>'Show Publisher']], 'prefix'=>'@', 'title'=>'Show Author', 'type'=>'select' ],
					[ 'name'=>'show-category', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Category', 'type'=>'select' ],
					[ 'name'=>'show-tags', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Tags', 'type'=>'select' ],
					[ 'name'=>'show-date', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_CREATEDDATE,'title'=>'Show Created Date'],['#'=>SETTING_MODIFIEDDATE,'title'=>'Show Modified Date'],['#'=>SETTING_PUBLISHEDDATE,'title'=>'Show Published Date']], 'prefix'=>'@', 'title'=>'Show Date', 'type'=>'select' ]
				],
				'right'=>[
					[ 'name'=>'show-image', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Image', 'type'=>'select' ],
					[ 'name'=>'position-image', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_ABOVETITLE,'title'=>'Above Title'],['#'=>SETTING_BELOWTITLE,'title'=>'Below Title'],['#'=>SETTING_FLOATLEFT,'title'=>'Float Left'],['#'=>SETTING_FLOATRIGHT,'title'=>'Float Right']], 'prefix'=>'@', 'title'=>'Image Position', 'type'=>'select' ],
					[ 'name'=>'show-info', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Info', 'type'=>'select' ],
					[ 'name'=>'position-info', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_ABOVECONTENT,'title'=>'Above Content'],['#'=>SETTING_ABOVETITLE,'title'=>'Above Title'],['#'=>SETTING_BELOWTITLE,'title'=>'Below Title'],['#'=>SETTING_BELOWCONTENT,'title'=>'Below Content']], 'prefix'=>'@', 'title'=>'Info Position', 'type'=>'select' ],
					[ 'name'=>'show-readmore', 'class'=>'form-control form-control-sm', 'default'=>SETTING_GLOBAL, 'list'=>[['#'=>SETTING_GLOBAL,'title'=>'Global setting'],['#'=>SETTING_HIDE,'title'=>'Hide'],['#'=>SETTING_SHOW,'title'=>'Show']], 'prefix'=>'@', 'title'=>'Show Read More', 'type'=>'select' ]
				]
			]
		];
	}
	
	public function getFilters() {
		return [
			'text'=>[
				'name'=>'filter-text',
				'label'=>'Search',
				'prefix'=>'',
				'value'=>''
			],
			'status'=>[
				'name'=>'filter-status',
				'class'=>'form-control',
				'list'=>[['#'=>STATUS_ANY,'title'=>'Any status']],
				'query'=>Form::query('status',Session::isAccessible()),
				'title'=>'Status',
				'value'=>STATUS_PUBLISHED
			],
			'category'=>[
				'name'=>'filter-category',
				'class'=>'form-control',
				'list'=>[['#'=>CATEGORY_ANY,'title'=>'Any category']],
				'query'=>Form::query('category',Session::isAccessible()),
				'title'=>'Category',
				'value'=>CATEGORY_ANY
			],
			'language'=>[
				'name'=>'filter-language',
				'class'=>'form-control',
				'list'=>[['#'=>LANGUAGE_ANY,'title'=>'Any language']],
				'query'=>Form::query('language',Session::isAccessible()),
				'title'=>'Language',
				'value'=>LANGUAGE_ANY
			],
			'accesslevel'=>[
				'name'=>'filter-accesslevel',
				'class'=>'form-control',
				'list'=>[['#'=>ACCESS_ANY,'title'=>'Any access level']],
				'query'=>Form::query('accesslevel',Session::isAccessible()),
				'title'=>'Access Level',
				'value'=>ACCESS_ANY
			]
		];
	}
}
?>