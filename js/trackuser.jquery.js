$(document).ready(function () {

	var TrackUserActivity = {
		
		CaptureRecord : function($event_name,$href,$iatrack,$mycat_id,$mytheme_id,$mycard_id,$appid){
			
			
			
			var uniqueid;
			uniqueid=readCookie("uniqueID");
			if(uniqueid=="" || uniqueid==null){
			uniqueid=uniqueidfun();
			createCookie("uniqueID", uniqueid, 30);
			}
			console.log(uniqueid);
			var formdata = {
				event_name 	: $event_name,
				href      	:  $href,
				iatrack     :$iatrack,
				mycat_id    :$mycat_id,
				mytheme_id  :$mytheme_id,
				mycard_id   :$mycard_id,
				ia_appid    :$appid,
				uniqueid   	:uniqueid
				 
			};
			$.ajax({
				type: "POST",
				url: BASEURL+'modules/trackuser/trackuser.php',
				data : formdata,
				success: function(msg){
					console.log(msg);
				},
				complete: function(msg){
					if($event_name == 'click')
					{
						if(window.location.href != $href)
						{
							window.location = $href;
						}
					}
				}
			});
		}
	}
		//For Anchor tags By Pravat
		
	$(document).on("click","a",function(e) {
		
		
		if($(this).attr("ia-track"))
		{ 
		e.preventDefault();
			if($(this).attr('data-aapid'))
			{
				var appid=  $(this).attr('data-aapid');
				
		
			}
			else if($(this).attr('data-appid'))
			{
				var appid=  $(this).attr('data-appid');
				
		
			}
			else
			{
				var appid='';
			}
			
			if($(this).attr('mycat-id'))
			{
				var mycat_id=  $(this).attr('mycat-id');
			}
			else
			{
				var mycat_id='';
			}
			
			if($(this).attr('mytheme-id'))
			{
				var mytheme_id=  $(this).attr('mytheme-id');
			}
			else
			{
				var mytheme_id='';
			}
				
			var href    = $(this).attr('href');
			var iatrack = $(this).attr('ia-track');
			
			if(href == 'javascript:void(0);' || href == 'javascript:void(0)' || href == '#' || href == '')
			{
				console.log('if');
				href = window.location.href;
				href = href.replace(".php", "");
				TrackUserActivity.CaptureRecord('click',href,iatrack,mycat_id,mytheme_id,'',appid);
			}
			else
			{
				
				console.log('else'+href);
				var url_validate = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
				if(!url_validate.test(href))
				{
				   href = BASEURL+href;
				}
				else{
				   href = href;
				}
				href = href.replace(".php", "");
				TrackUserActivity.CaptureRecord('click',href,iatrack,mycat_id,mytheme_id,'',appid);
			}
		}
		else{
			return true;
		}
		
	});
		//For Button By Pravat
		
	$('input[type="submit"]').click(function() {
		if($(this).attr("ia-track"))
		{
			var sbmtvalu = $(this).val();
			var iatrack = $(this).attr("ia-track");
			TrackUserActivity.CaptureRecord('form-submit',sbmtvalu,iatrack,'','','','');
			
		}
	});
	
	$('input[type="button"]').click(function() {
		if($(this).attr("ia-track"))
		{
			var appid = $("#current").attr("data-aapid");
			if(typeof appid == 'undefined')
			{
				appid = '';
			}
			
			var sbmtvalu = $(this).val();
			var iatrack = $(this).attr("ia-track");
			TrackUserActivity.CaptureRecord('form-submit',sbmtvalu,iatrack,'','','',appid);
			
		}
	});
	
	//For Cards By Pravat	
		
		$(document).on("dragstop",".ui-draggable",function(e) {
			
			
		if($(this).attr("data-component"))
			{
				
			
				    var theme_id=$("#iapthemeid").val();
				    var cat_id=$("#iapcatid").val();
					var mycard_id = $(this).attr("data-component");
					var iatrack = 'IAP101000'+$(this).attr("data-component");
					var href = window.location.href;
					href = href.replace(".php", "");
				
				TrackUserActivity.CaptureRecord('Draged-card',href,iatrack,cat_id ,theme_id,mycard_id,'');	
			
			}
		});
	
		
	function getCookie(name) {
		var regexp = new RegExp("(?:^" + name + "|;\s*"+ name + ")=(.*?)(?:;|$)", "g");
		var result = regexp.exec(document.cookie);
		return (result === null) ? null : result[1];
	}

	function readCookie(name) {
	  var nameEQ = name + "=";
	  var ca = document.cookie.split(';');
	  for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	  }
	  return null;
	}

	function createCookie(name,value,days) {
	  if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	  }
	  else var expires = "";
	  document.cookie = name+"="+value+expires+"; path=/";
	}	

	function uniqueidfun(){
		// always start with a letter (for DOM friendlyness)
		var idstr=String.fromCharCode(Math.floor((Math.random()*25)+65));
		do {                
			// between numbers and characters (48 is 0 and 90 is Z (42-48 = 90)
			var ascicode=Math.floor((Math.random()*42)+48);
			if (ascicode<58 || ascicode>64){
				// exclude all chars between : (58) and @ (64)
				idstr+=String.fromCharCode(ascicode);    
			}                
		} while (idstr.length<32);
		return (idstr);
	}	
});