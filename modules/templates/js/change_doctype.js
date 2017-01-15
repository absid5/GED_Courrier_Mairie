
function doctype_template(args)
{
	//alert(print_r(args));
	var choose_file_div = $('choose_file_div');
	var file_iframe = $('file_iframe');

	var template = '';
	var is_generated = false;
	var doc_frame = '';
	var model_frame = '';
	for(var i=0; i< args.length; i++)
	{
		if(args[i]['id'] == 'template_id')
		{
			template = args[i]['value'];
		}
		if(args[i]['id'] == 'is_generated' && args[i]['value'] == 'Y')
		{
			is_generated = true;
		}
		if(args[i]['id'] == 'doc_frame')
		{
			doc_frame = args[i]['value'];
		}
		if(args[i]['id'] == 'model_frame')
		{
			model_frame = args[i]['value'];
		}
	}

	if(is_generated == true)
	{
		if(choose_file_div != null)
		{
			choose_file_div.style.display = 'none';
		}
		if(file_iframe != null && model_frame!= '' && model_frame != null)
		{
			file_iframe.src = model_frame;
		}
	}
	else
	{
		if(choose_file_div != null)
		{
			choose_file_div.style.display = 'block';
		}
		if(file_iframe != null && doc_frame!= '' && doc_frame != null)
		{
			file_iframe.src = doc_frame;
		}
	}
}