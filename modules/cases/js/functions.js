function unlink_case(path_manage_script,case_id,res_id){
	new Ajax.Request(path_manage_script,
	{
		method:'post',
		parameters: { 
			case_id : case_id,
			res_id : res_id,
		},
		onSuccess: function(answer){
			eval('response='+answer.responseText);
			if(response){
				window.location.href='index.php?page=details&dir=indexing_searching&id='+res_id;
			}else{
				alert('Something went wrong...');
			}
		},
		onFailure: function(){
			alert('Something went wrong...');
		}
	});
}