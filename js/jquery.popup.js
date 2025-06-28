// expand popup click event
$(".lx-open-popup").click(function(){
	$(".lx-popup").css({"display":"none"});
	var title = $(this).attr("data-title");
	$("."+title).css({"display":"block"});
	window.setTimeout(function(){
		$("."+title+" .lx-popup-content").css({"transform":"scale(1.0)","opacity":"1.0"});
		$("."+title).trigger("focus");
	},100);	
});
$("body").delegate(".lx-open-popup","click",function(){
	$(".lx-popup").css({"display":"none"});
	var title = $(this).attr("data-title");
	$("."+title).css({"display":"block"});
	window.setTimeout(function(){
		$("."+title+" .lx-popup-content").css({"transform":"scale(1.0)","opacity":"1.0"});
		$("."+title).trigger("focus");
	},100);	
});

$(".lx-popup-content").delegate(".lx-open-popup","click",function(){
	$(".lx-popup").css({"display":"none"});
	var title = $(this).attr("data-title");
	$("."+title).css({"display":"block"});
	window.setTimeout(function(){
		$("."+title+" .lx-popup-content").css({"transform":"scale(1.0)","opacity":"1.0"});
		$("."+title).trigger("focus");
	},100);	
});

// popup remove click event
$(".lx-popup-content > a > .material-icons").click(function(){
	// hide popup
	$(".lx-popup .lx-popup-content").css({"transform":"scale(0.9)","opacity":"0.0"});
	window.setTimeout(function(){
		$(".lx-popup").css({"display":"none"});
	},500);
	$(".lx-list-products ul li").each(function(){
		if($(".lx-pagination ul").attr("data-table") === "commands"){
			$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-qtyoui",$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-oqo"));
			$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-qtynon",$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-oqn"));		
		}
	});
	if($(this).parent().next("div").find("h3").text() === "Modifier commande fournisseur"){
		window.setTimeout(function(){
			$(".lx-reload-details").trigger("click");
		},500);		
	}
});

// popup remove click event
$("body").delegate(".lx-popup-content > a > .material-icons","click",function(){
	// hide popup
	$(".lx-popup .lx-popup-content").css({"transform":"scale(0.9)","opacity":"0.0"});
	window.setTimeout(function(){
		$(".lx-popup").css({"display":"none"});
	},500);
	$(".lx-list-products ul li").each(function(){
		if($(".lx-pagination ul").attr("data-table") === "commands"){
			$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-qtyoui",$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-oqo"));
			$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-qtynon",$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-oqn"));		
		}
	});
	if($(this).parent().next("div").find("h3").text() === "Modifier commande fournisseur"){
		window.setTimeout(function(){
			$(".lx-reload-details").trigger("click");
		},500);		
	}
});

// Hide the popup when esc key is clicked
$(document).on("keyup",function(e) {
	
    if(e.keyCode == 27) {	
		// hide popup
		$(".lx-popup .lx-popup-content").css({"transform":"scale(0.9)","opacity":"0.0"});
		window.setTimeout(function(){	
			$(".lx-popup").css({"display":"none"});
		},500);
		$(".lx-list-products ul li").each(function(){
			if($(".lx-pagination ul").attr("data-table") === "commands"){
				$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-qtyoui",$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-oqo"));
				$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-qtynon",$("#commandsform select[name='product'] option[value='"+$(this).find("span").attr("data-id")+"']").attr("data-oqn"));		
			}		
		});
    }
	
});

// expand click event
$(".lx-open-popup").click(function(event) {
	// stop hide popup event
	event.stopPropagation();	
});

// expand click event
$(".lx-popup-content").click(function(event) {
	// stop hide popup event
	event.stopPropagation();	
});