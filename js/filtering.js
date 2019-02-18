$(document).ready(function() {
	$('#filter-text').on('change paste keyup',function() {
		var pattern = new RegExp($(this).val().normalize('NFD'),'i');
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(pattern.test($(this).data('item').title.normalize('NFD'))) {
				filter = filter & ~1;
			} else if(pattern.test($(this).data('item').description.normalize('NFD'))) {
				filter = filter & ~1;
			} else if(pattern.test($(this).data('item').name.normalize('NFD'))) {
				filter = filter & ~1;
			} else if($(this).data('item').tags && pattern.test($(this).data('item').tags.normalize('NFD'))) {
				filter = filter & ~1;
			} else {
				filter = filter | 1;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-status').on('change',function() {
		var status = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(status == -1 || status == $(this).data('item').status) {
				filter = filter & ~2;
			} else {
				filter = filter | 2;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-category').on('change',function() {
		var category = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(category == -1 || category == $(this).data('item').category) {
				filter = filter & ~4;
			} else {
				filter = filter | 4;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-collection').on('change',function() {
		var collection = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(collection == -1 || collection == $(this).data('item').collection) {
				filter = filter & ~4;
			} else {
				filter = filter | 4;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-group').on('change',function() {
		var group = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(group == -1 || group == $(this).data('item').group) {
				filter = filter & ~4;
			} else {
				filter = filter | 4;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-option').on('change',function() {
		var option = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(option == -1 || option == $(this).data('item').option) {
				filter = filter & ~4;
			} else {
				filter = filter | 4;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-role').on('change',function() {
		var role = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(role == -1 || role == $(this).data('item').role) {
				filter = filter & ~4;
			} else {
				filter = filter | 4;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-language').on('change',function() {
		var language = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(language == -1 || language == $(this).data('item').language) {
				filter = filter & ~8;
			} else {
				filter = filter | 8;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('#filter-access').on('change',function() {
		var access = parseInt($(this).val());
		var count = 0;
		var total = 0;
		$('.table-item').each(function() {
			var filter = parseInt($(this).attr('data-filter'));
			if(access == -1 || access == $(this).data('item').access) {
				filter = filter & ~16;
			} else {
				filter = filter | 16;
			}
			$(this).attr('data-filter',filter);
			if(filter) {
				$(this).addClass('d-none');
			} else {
				$(this).removeClass('d-none');
				count++;
			}
			total++;
		});
		$('#filter-info').html('Shown '+count+' out of '+total);
	});
	$('.table-item').each(function() {
		$('#filter-text').trigger('change');
		$('#filter-status').trigger('change');
		//$('#filter-category').trigger('change');
		//$('#filter-group').trigger('change');
		//$('#filter-collection').trigger('change');
		//$('#filter-option').trigger('change');
		//$('#filter-language').trigger('change');
		//$('#filter-access').trigger('change');
	});
	$('.img-selectable').click(function() {
		$($(this).data('target')).val($(this).data('item').id).attr('name',$($(this).data('target')).attr('name').replace(/^-/,''));
		$($(this).data('preview')).attr('src','/image?id='+$(this).data('item').id+'&cache=no');
		$($(this).data('dismiss')).hide();
	});
	$('.img-selectable').each(function() {
		//$('#filter-text').trigger('change');
		$('#filter-status').trigger('change');
		//$('#filter-collection').trigger('change');
		//$('#filter-language').trigger('change');
	});
});