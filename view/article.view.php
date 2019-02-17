<?php
namespace Cubo;

defined('__CUBO__') || new \Exception("No use starting a class without an include");

class ArticleView extends View {
	protected $listColumns = "title,status,category,language,accesslevel";
	
	public function listFilters() {
		return [
			'text'=>[],
			'status'=>[
				'name'=>'filter-status',
				'title'=>'Status',
				'value'=>STATUS_PUBLISHED,
				'list'=>['#'=>STATUS_ANY,'title'=>'Any status'],
				'query'=>Form::query('status',Session::isAccessible())
			],
			'category'=>[
				'name'=>'filter-category',
				'title'=>'Category',
				'value'=>CATEGORY_ANY,
				'list'=>['#'=>CATEGORY_ANY,'title'=>'Any category'],
				'query'=>Form::query('category',Session::isAccessible())
			],
			'language'=>[
				'name'=>'filter-language',
				'title'=>'Language',
				'value'=>LANGUAGE_ANY,
				'list'=>['#'=>LANGUAGE_ANY,'title'=>'Any language'],
				'query'=>Form::query('language',Session::isAccessible())
			],
			'accesslevel'=>[
				'name'=>'filter-accesslevel',
				'title'=>'Access Level',
				'value'=>ACCESS_ANY,
				'list'=>['#'=>ACCESS_ANY,'title'=>'Any access level'],
				'query'=>Form::query('accesslevel',Session::isAccessible())
			]
		];
	}
}
?>