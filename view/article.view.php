<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

class ArticleView extends View {
	protected $listColumns = "title,status,category,language,accesslevel";
	
	// Form definition
	public function getDefinition() {
		return (object)[
			'tabs'=>[
				[ 'tab'=>'content-tab', 'pane'=>'content-pane', 'selected'=>true, 'title'=>'Content' ],
				[ 'tab'=>'image-tab', 'pane'=>'image-pane', 'selected'=>false, 'title'=>'Image and Metadata' ],
				[ 'tab'=>'publishing-tab', 'pane'=>'publishing-pane', 'selected'=>false, 'title'=>'Publishing' ],
				[ 'tab'=>'view-options-tab', 'pane'=>'view-options-pane', 'selected'=>false, 'title'=>'View Options' ],
				[ 'tab'=>'list-optiona-tab', 'pane'=>'list-options-pane', 'selected'=>false, 'title'=>'List Options' ]
			],
			'content-pane'=>[
				'columns'=>[ 'left'=>'col-12 col-lg-8','right'=>'col-12 col-lg-4' ],
				'left'=>[
					[ 'name'=>'intro', 'class'=>'form-control text-html', 'required'=>true, 'size'=>4, 'title'=>ucwords($this->class).' Intro', 'type'=>'textarea', 'width'=>2 ],
					[ 'name'=>'body', 'class'=>'form-control text-html', 'required'=>true, 'size'=>8, 'title'=>ucwords($this->class).' Body', 'type'=>'textarea', 'width'=>2 ]
				],
				'right'=>[
					[ 'name'=>'status', 'class'=>'form-control form-control-sm', 'default'=>Application::getController()->canPublish() ? STATUS_PUBLISHED : STATUS_UNPUBLISHED, 'query'=>Form::query('status',Session::isAccessible()), 'readonly'=>Application::getController()->cannotPublish(), 'title'=>'Status', 'type'=>'select' ],
					[ 'name'=>'category', 'class'=>'form-control form-control-sm', 'default'=>CATEGORY_UNDEFINED, 'query'=>Form::query('category',Session::isAccessible()), 'title'=>'Category', 'type'=>'select' ],
					[ 'name'=>'language', 'class'=>'form-control form-control-sm', 'default'=>LANGUAGE_UNDEFINED, 'query'=>Form::query('language',Session::isAccessible()), 'title'=>'Language', 'type'=>'select' ],
					[ 'name'=>'accesslevel', 'class'=>'form-control form-control-sm', 'default'=>ACCESS_PUBLIC, 'query'=>Form::query('accesslevel',Session::isAccessible()), 'title'=>'Access Level', 'type'=>'select' ]
				]
			],
			'image-pane'=>[
			],
			'publishing-pane'=>[
			],
			'view-options-pane'=>[
			],
			'list-options-pane'=>[
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
				'list'=>['#'=>STATUS_ANY,'title'=>'Any status'],
				'query'=>Form::query('status',Session::isAccessible()),
				'title'=>'Status',
				'value'=>STATUS_PUBLISHED
			],
			'category'=>[
				'name'=>'filter-category',
				'class'=>'form-control',
				'list'=>['#'=>CATEGORY_ANY,'title'=>'Any category'],
				'query'=>Form::query('category',Session::isAccessible()),
				'title'=>'Category',
				'value'=>CATEGORY_ANY
			],
			'language'=>[
				'name'=>'filter-language',
				'class'=>'form-control',
				'list'=>['#'=>LANGUAGE_ANY,'title'=>'Any language'],
				'query'=>Form::query('language',Session::isAccessible()),
				'title'=>'Language',
				'value'=>LANGUAGE_ANY
			],
			'accesslevel'=>[
				'name'=>'filter-accesslevel',
				'class'=>'form-control',
				'list'=>['#'=>ACCESS_ANY,'title'=>'Any access level'],
				'query'=>Form::query('accesslevel',Session::isAccessible()),
				'title'=>'Access Level',
				'value'=>ACCESS_ANY
			]
		];
	}
}
?>