/* =========================================================================
   EASY‑DOC – main site script
   (2025‑05‑10) – LOGO / SIGNATURE UPLOAD BUG FIXED
   ======================================================================== */

/* ---------------  GLOBAL HELPERS --------------- */

function uploadPhotos(file, companyId, table, column) {
    let formData = new FormData();
    formData.append("photo",  file);
    formData.append("id",     companyId);
    formData.append("table",  table);
    formData.append("column", column);

    $.ajax({
        url:  "/ajax/file_upload_parser.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (resp) {
            // Expect JSON:  { ok:1, filename:"…", message:"…" }
            try { resp = JSON.parse(resp); } catch (e) { resp = {}; }

            if (resp.ok) {
                $(`#preview_${column}_${companyId}`)
                    .attr("src", "/uploads/" + resp.filename)
                    .removeClass("d-none");
            } else {
                alert("Erreur d’upload : " + (resp.message || resp));
            }
        },
        error: function (xhr) {
            alert("Upload échoué : " + xhr.status + " " + xhr.statusText);
        }
    });
}

/* ---------------  FIXED CHANGE‑HANDLER --------------- */

/* Any <input type="file"> whose id *starts with* “uploadphotos”
   will now trigger this handler – so dynamic IDs like
   uploadphotos5, uploadphotos17… all work. */

$("body").on("change", "input[id^='uploadphotos']", function () {

    // Mini UX feedback – spinning icon on the adjacent <a>
    const $btn = $(this).next("a");
    if ($btn.find(".fa-circle-notch").length === 0) {
        $btn.prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
    }

    uploadPhotos(
        this.files[0],           // FILE the user picked
        $(this).data("id"),      // company ID
        $(this).data("table"),   // "companies"
        $(this).data("column")   // "logo1" or "signature"
    );
});

// Strict Mode
"use strict";

var timer;
var filterClicked = 'no';
var chartdates;
var chartnbdocuments;
var chartvalue;

// Window Load Event
$(window).on("load", function() {
	var html = $(".contactus").clone();
	$(".lx-header .contactus").remove();
	$(".lx-wrapper").append(html);
    return false;
});

// Document Ready event
$(document).on("ready", function() {
	if($("#commandsform").length){
		$("#commandsform select[name='depot']").trigger("change");
		$("#commandsform select[name='company']").trigger("change");		
	}	
	var html = $(".contactus").clone();
	$(".lx-header .contactus").remove();
	$(".lx-wrapper").append(html);
	html = $(".editnotifications").clone();
	$(".lx-header .editnotifications").remove();
	$(".lx-wrapper").append(html);
	html = $(".signature").clone();
	$(".lx-header .signature").remove();
	$(".lx-wrapper").append(html);
	loadNotification();
	window.setInterval(function(){
		loadNotification();
	},60000);
	
	var isMouseDown = false;
    var lastMouseX = 0;

    $(".lx-table").on("mousedown", function(event) {
        isMouseDown = true;
        lastMouseX = event.clientX;
    });

    $(document).on("mouseup", function() {
        isMouseDown = false;
    });

    $(document).on("mousemove", function(event) {
        if (isMouseDown) {
            var deltaX = event.clientX - lastMouseX;
            lastMouseX = event.clientX;
            $(".lx-table")[0].scrollLeft -= deltaX;
        }
    });
	
	document.addEventListener('DOMContentLoaded', function() {
		const infobuls = document.querySelectorAll('.infobul');
			infobuls.forEach(function(infobul) {
			infobul.removeAttribute('data-title');
		});
	});
	return false;
});

function loadNotification(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'loadnotification'
		},
		success : function(response){
			$(".lx-notifications-list div").html(response);
			$(".lx-show-notifications ins").text($(".lx-notifications-list div .lx-notifications-nb").text());
			if($(".lx-show-notifications ins").text() === "" || $(".lx-show-notifications ins").text() === "0"){
				$(".lx-show-notifications ins").css("display","none");
			}
			else{
				$(".lx-show-notifications ins").css("display","block");
			}
		}
	});	
}

$(".lx-show-notifications").on("click",function(){
	$(".lx-header-admin > ul > li > img + .lx-notifications-list").hide();
	$(this).parent().prev(".lx-notifications-list").fadeToggle();
});

$(".lx-accordion > li > a").on("click",function(){
	var a = $(this).next("div");
	a.slideToggle();
	if($(this).find("i").attr("class") === "fa fa-plus"){
		$(this).find("i").attr("class","fa fa-minus");
	}
	else{
		$(this).find("i").attr("class","fa fa-plus");
	}
	window.setTimeout(function(){
		a.find("iframe").height(a.find("iframe").width() * 0.56);
	},100);
});

$(".lx-header-admin > ul > li > img").on("click",function(){
	$(".lx-show-notifications").parent().next(".lx-account-settings").hide();
	$(this).next(".lx-account-settings").fadeToggle();
});

$(".lx-mobile-menu").on("click",function(){
	$(".lx-main-leftside").css("left","0px");
});

$(".lx-mobile-menu-hide").on("click",function(){
	$(".lx-main-leftside").css("left","-268px");
});

$(".lx-stats-bloc ul li a").on("click",function(){
	$(this).parents("ul").find("a").removeClass("active");
	$(this).addClass("active");
	$(this).parents("div.lx-stats-bloc").find("table[data-tab],div[data-tab]").hide();
	$(this).parents("div.lx-stats-bloc").find("table[data-tab='"+$(this).attr("data-tab")+"'],div[data-tab='"+$(this).attr("data-tab")+"']").show();
});

$(".lx-login .lx-submit a").on("click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	$(".lx-login form").submit();
});

$(document).on("keyup",function(e) {
    if(e.keyCode == 13) {	
		if($(".lx-login .lx-submit a").length){
			$(".lx-login .lx-submit a").trigger("click");
		}
		if($(".lx-search-keyword").length){
			$(".lx-search-keyword").trigger("click");
		}
		if($(".lx-search-keyword2").length){
			$(".lx-search-keyword2").trigger("click");
		}
    }
});

$(".lx-login .lx-textfield label i").on("click",function(){
	if($(this).attr("class") === "fa fa-eye-slash"){
		$(this).attr("class","fa fa-eye").css("color","#39add1");
		$(this).prev("input").attr("type","text");
	}
	else{
		$(this).attr("class","fa fa-eye-slash").css("color","#CCCCCC");
		$(this).prev("input").attr("type","password");
	}
});

function _(el){
	return document.getElementById(el);
}

$("#medias").on("change",function(){
	if($(this).parent().find("a").find(".fa-circle-notch").length === 0){
		$(this).parent().find("a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	uploadsImages(_("medias").files[0]);
});

function uploadsImages(picture){
	var file = picture;
	var formdata = new FormData();
	formdata.append("file0", file);
	var ajax = new XMLHttpRequest();
	ajax.addEventListener("load", completeHandler1, false);
	ajax.open("POST", "file_upload_parser.php");
	ajax.send(formdata);
	function completeHandler1(event){
		if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
			if(ajax.responseText !== ""){
				$("#medias").parent().find("a").find("i").remove();
				var html = '<img src="uploads/'+ajax.responseText+'" />';
				$("input[name='picture']").val(ajax.responseText);
				$(".lx-medias-item div").html(html);
			}
		}
	}
}

$("#mediascover").on("change",function(){
	if($(this).parent().find("a").find(".fa-circle-notch").length === 0){
		$(this).parent().find("a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	uploadsCover(_("mediascover").files[0]);
});

function uploadsCover(picture){
	var file = picture;
	var formdata = new FormData();
	formdata.append("file0", file);
	var ajax = new XMLHttpRequest();
	ajax.addEventListener("load", completeHandler1, false);
	ajax.open("POST", "file_upload_parser.php");
	ajax.send(formdata);
	function completeHandler1(event){
		if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
			if(ajax.responseText !== ""){
				$("#mediascover").parent().find("a").find("i").remove();
				var html = '<img src="uploads/'+ajax.responseText+'" />';
				$("input[name='cover']").val(ajax.responseText);
				$(".lx-medias-item-cover div").html(html);
			}
		}
	}
}

$("body").delegate(".uploadreceipt","change",function(){
	if($(this).next("a").find("i").length === 0){
		$(this).next("a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	uploadReceipt(_($(this).attr("id")).files[0],$(this).attr("data-id"));
});

$("body").delegate(".lx-open-zoombox","click",function(){
	$(".lx-zoombox-content").html('<img src="'+$(this).attr("src").replace(/cropped_/,"large_")+'">').attr("data-pos",$(this).index());
});

$(".lx-zoombox-nav i.fa-angle-right").on("click",function(){
	if($(".lx-zoombox-content").attr("data-pos") < parseFloat($(".lx-open-zoombox").length) - 1){
		$(".lx-zoombox-content").attr("data-pos",parseFloat($(".lx-zoombox-content").attr("data-pos")) + 1);
	}
	else{
		$(".lx-zoombox-content").attr("data-pos",0);
	}
	$(".lx-zoombox-content").html('<img src="'+$(".lx-open-zoombox:eq("+$(".lx-zoombox-content").attr("data-pos")+")").attr("src").replace(/cropped_/,"large_")+'">');
});

$(".lx-zoombox-nav i.fa-angle-left").on("click",function(){
	if($(".lx-zoombox-content").attr("data-pos") > 0){
		$(".lx-zoombox-content").attr("data-pos",parseFloat($(".lx-zoombox-content").attr("data-pos")) - 1);
	}
	else{
		$(".lx-zoombox-content").attr("data-pos",parseFloat($(".lx-open-zoombox").length) - 1);
	}
	$(".lx-zoombox-content").html('<img src="'+$(".lx-open-zoombox:eq("+$(".lx-zoombox-content").attr("data-pos")+")").attr("src").replace(/cropped_/,"large_")+'">');
});


$("body").delegate(".lx-floating-response","click",function(){
	$(".lx-floating-response").fadeOut();
});

$("body").delegate(".lx-main-content .lx-table input[type='checkbox']:not(input[name='selectall'])","click",function(){
	if($(this).prop("checked") === true){
		$(this).parent().parent().parent().css("background","#c6e2ff");
	}
	else{
		$(this).parent().parent().parent().removeAttr("style");
		$(this).parent().parent().parent().attr("style",$(this).parent().parent().parent().attr("data-umpaid"));
	}
});

$("body").delegate(".lx-first-tr input[name='selectall']","click",function(){
	var checked = $(this).prop("checked");
	$(".lx-main .lx-table input[type='checkbox']").each(function(){
		$(this).prop("checked",checked)
	});
	var ids = "0";
	$("input[type='checkbox'][name='command']").each(function(){
		if($(this).prop("checked") === true){
			ids += ","+$(this).val();
		}
	});
	$("#idticket").val(ids);
});

$(".lx-popup-content").delegate(".lx-first-tr input[name='selectall']","click",function(){
	var checked = $(this).prop("checked");
	$(".lx-popup-content .lx-table input[type='checkbox']").each(function(){
		$(this).prop("checked",checked)
	});
	var ids = "0";
	$("input[type='checkbox'][name='command']").each(function(){
		if($(this).prop("checked") === true){
			ids += ","+$(this).val();
		}
	});
	$("#idticket").val(ids);
});

$("body").delegate(".lx-first-tr input[name='selectall']","click",function(){
	var checked = $(this).prop("checked");
	$(".lx-table input[type='checkbox']").each(function(){
		$(this).prop("checked",checked)
	});
	var ids = "0";
	$("input[type='checkbox'][name='command']").each(function(){
		if($(this).prop("checked") === true){
			ids += ","+$(this).val();
		}
	});
	$(".lx-main-content .lx-table input[type='checkbox']:not(input[name='selectall'])").each(function(){
		if($(this).prop("checked") === true){
			$(this).parent().parent().parent().css("background","#c6e2ff");
		}
		else{
			$(this).parent().parent().parent().removeAttr("style");
			$(this).parent().parent().parent().css("background",$(this).parent().parent().parent().attr("data-umpaid"));
		}
	});
	$("#idticket").val(ids);
});

$(".lx-popup-content").delegate(".lx-first-tr input[name='selectall']","click",function(){
	var checked = $(this).prop("checked");
	$(".lx-popup .lx-table input[type='checkbox']").each(function(){
		$(this).prop("checked",checked)
	});
});

$(".lx-popup-content").delegate(".lx-selectall-all-roles","click",function(){
	var checked = $(this).find("input").prop("checked");
	$(".lx-table-roles input[type='checkbox']").each(function(){
		$(this).prop("checked",checked);
	});

	$("#usersform input[name='rolestext']").val("");
	$("#usersform input[name='roles']").each(function(){
		if($(this).prop("checked") === true){
			$("#usersform input[name='rolestext']").val($("#usersform input[name='rolestext']").val() + "," + $(this).val());
		}
	});

	$("#usersform input[name='companiestext']").val("");
	$("#usersform input[name='companies']").each(function(){
		if($(this).prop("checked") === true){
			$("#usersform input[name='companiestext']").val($("#usersform input[name='companiestext']").val() + "," + $(this).val());
		}
	});
});

$("#importclient").on("change",function(){
	$(".lx-importer").css({"border-color":"green","background":"#d1e8cc"});
	$(".lx-importer span").text($(this).val().replace(/C:\\fakepath\\/i, '')).css({"font-weight":"bold","color":"green"});
});

$("#importclientsform .lx-submit a").on("click",function(){
	uploadsXLSClients(_("importclient"));
});

function uploadsXLSClients(file){
	if(file.value !== ""){
		if($("#importclientsform .lx-submit a").attr("class") !== "lx-disabled"){
			$("#importclientsform .lx-submit a").attr("class","lx-disabled");
			if($("#importclientsform .lx-submit a").find("i").length === 0){
				$("#importclientsform .lx-submit a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			$("*[data-required]").removeAttr("style");
			var formdata = new FormData();
			formdata.append("file3", file.files[0]);
			var ajax = new XMLHttpRequest();
			ajax.addEventListener("load", completeHandler, false);
			ajax.open("POST", "file_upload_parser.php");
			ajax.send(formdata);
			function completeHandler(event){
				if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
					$("#importclientsform .lx-submit a").attr("class","");
					$("#importclientsform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadClients($(".lx-pagination ul").attr("data-state"));
					$("#importclient").val("");
					$(".lx-importer").removeAttr("style");
					$(".lx-importer span").text("Choisissez un fichier (excel)").removeAttr("style");
				}
			}	
		}			
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez choisir un fichier excel !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
}

$("#importsupplier").on("change",function(){
	$(".lx-importer").css({"border-color":"green","background":"#d1e8cc"});
	$(".lx-importer span").text($(this).val().replace(/C:\\fakepath\\/i, '')).css({"font-weight":"bold","color":"green"});
});

$("#importsuppliersform .lx-submit a").on("click",function(){
	uploadsXLSSuppliers(_("importsupplier"));
});

function uploadsXLSSuppliers(file){
	if(file.value !== ""){
		if($("#importsuppliersform .lx-submit a").attr("class") !== "lx-disabled"){
			$("#importsuppliersform .lx-submit a").attr("class","lx-disabled");
			if($("#importsuppliersform .lx-submit a").find("i").length === 0){
				$("#importsuppliersform .lx-submit a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			$("*[data-required]").removeAttr("style");
			var formdata = new FormData();
			formdata.append("file4", file.files[0]);
			var ajax = new XMLHttpRequest();
			ajax.addEventListener("load", completeHandler, false);
			ajax.open("POST", "file_upload_parser.php");
			ajax.send(formdata);
			function completeHandler(event){
				if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
					$("#importsuppliersform .lx-submit a").attr("class","");
					$("#importsuppliersform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadSuppliers($(".lx-pagination ul").attr("data-state"));
					$("#importsupplier").val("");
					$(".lx-importer").removeAttr("style");
					$(".lx-importer span").text("Choisissez un fichier (excel)").removeAttr("style");
				}
			}	
		}			
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez choisir un fichier excel !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
}

$("#importproduct").on("change",function(){
	$(".lx-importer").css({"border-color":"green","background":"#d1e8cc"});
	$(".lx-importer span").text($(this).val().replace(/C:\\fakepath\\/i, '')).css({"font-weight":"bold","color":"green"});
});

$("#importproductsform .lx-submit a").on("click",function(){
	uploadsXLSProducts(_("importproduct"));
});

function uploadsXLSProducts(file){
	if(file.value !== ""){
		if($("#importproductsform .lx-submit a").attr("class") !== "lx-disabled"){
			$("#importproductsform .lx-submit a").attr("class","lx-disabled");
			if($("#importproductsform .lx-submit a").find("i").length === 0){
				$("#importproductsform .lx-submit a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			$("*[data-required]").removeAttr("style");
			var formdata = new FormData();
			formdata.append("file5", file.files[0]);
			var ajax = new XMLHttpRequest();
			ajax.addEventListener("load", completeHandler, false);
			ajax.open("POST", "file_upload_parser.php");
			ajax.send(formdata);
			function completeHandler(event){
				if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
					$("#importproductsform .lx-submit a").attr("class","");
					$("#importproductsform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadProducts($(".lx-pagination ul").attr("data-state"));
					$("#importproduct").val("");
					$(".lx-importer").removeAttr("style");
					$(".lx-importer span").text("Choisissez un fichier (excel)").removeAttr("style");
				}
			}	
		}			
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez choisir un fichier excel !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
}

$("#importservice").on("change",function(){
	$(".lx-importer").css({"border-color":"green","background":"#d1e8cc"});
	$(".lx-importer span").text($(this).val().replace(/C:\\fakepath\\/i, '')).css({"font-weight":"bold","color":"green"});
});

$("#importservicesform .lx-submit a").on("click",function(){
	uploadsXLSServices(_("importservice"));
});

function uploadsXLSServices(file){
	if(file.value !== ""){
		if($("#importservicesform .lx-submit a").attr("class") !== "lx-disabled"){
			$("#importservicesform .lx-submit a").attr("class","lx-disabled");
			if($("#importservicesform .lx-submit a").find("i").length === 0){
				$("#importservicesform .lx-submit a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			$("*[data-required]").removeAttr("style");
			var formdata = new FormData();
			formdata.append("file6", file.files[0]);
			var ajax = new XMLHttpRequest();
			ajax.addEventListener("load", completeHandler, false);
			ajax.open("POST", "file_upload_parser.php");
			ajax.send(formdata);
			function completeHandler(event){
				if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
					$("#importservicesform .lx-submit a").attr("class","");
					$("#importservicesform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadServices($(".lx-pagination ul").attr("data-state"));
					$("#importservice").val("");
					$(".lx-importer").removeAttr("style");
					$(".lx-importer span").text("Choisissez un fichier (excel)").removeAttr("style");
				}
			}	
		}			
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez choisir un fichier excel !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
}

$("#exportform .lx-submit a").on("click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	if($("#exportform input[name='columns']:checked").length){
		$("#exportform .lx-submit a").attr("class","");
		$("#exportform .lx-submit a i").remove();
		var keyword = $("#keyword").length?"&keyword="+$("#keyword").val():"&keyword=";
		var statee = $("#state").length?"&statee="+$("#state").attr("data-ids"):"&statee=";
		var status = $("#status").length?"&status="+$("#status").attr("data-ids"):"&status=";
		var paid = $("#paid").length?"&paid="+$("#paid").attr("data-ids"):"&paid=";
		var company = $("#company").length?"&company="+$("#company").attr("data-ids"):"&company=";
		var supplier = $("#supplier").length?"&supplier="+$("#supplier").attr("data-ids"):"&supplier=";
		var product = $("#product").length?"&product="+$("#product").attr("data-ids"):"&product=";
		var modepayment = $("#modepayment").length?"&modepayment="+$("#modepayment").val():"&modepayment=";
		var datestart = $("#datestart").length?"&datestart="+$("#datestart").val():"&datestart=";
		var dateend = $("#dateend").length?"&dateend="+$("#dateend").val():"&dateend=";
		var dateduestart = $("#datestart").length?"&dateduestart="+$("#dateduestart").val():"&dateduestart=";
		var datedueend = $("#datedueend").length?"&datedueend="+$("#datedueend").val():"&datedueend=";
		var datepaidstart = $("#datepaidstart").length?"&datepaidstart="+$("#datepaidstart").val():"&datepaidstart=";
		var datepaidend = $("#datepaidend").length?"&datepaidend="+$("#datepaidend").val():"&datepaidend=";
		var dateremisstart = $("#dateremisstart").length?"&dateremisstart="+$("#dateremisstart").val():"&dateremisstart=";
		var dateremisend = $("#dateremisend").length?"&dateremisend="+$("#dateremisend").val():"&dateremisend=";
		var user = $("#user").length?"&user="+$("#user").attr("data-ids"):"&user=";
		var client = $("#client").length?"&client="+$("#client").attr("data-ids"):"&client=";
		var avoir = $("#avoir").length?"&avoir="+($("#avoir").prop("checked") === true?"1":"0"):"&avoir=";
		var imputation = $("#imputation").length?"&imputation="+$("#imputation").attr("data-ids"):"&imputation=";
		var rib = $("#rib").length?"&rib="+$("#rib").attr("data-ids"):"&rib=";
		var type = $("#type").length?"&type="+$("#type").val():"&type=";
		var cmd = $("#cmd").length?"&cmd="+$("#cmd").val():"&cmd=";
		var nature = $("#nature").length?"&nature="+$("#nature").val():"&nature=";
		var pricemin = $("#pricemin").length?"&pricemin="+$("#pricemin").val():"&pricemin=";
		var pricemax = $("#pricemax").length?"&pricemax="+$("#pricemax").val():"&pricemax=";
		var table = $("#exportform input[name='table']").val();
		var typedoc = $("#exportform input[name='type']").val();
		var type = "";
		if($("#exportform input[name='type']").length){
			var type = $("#exportform input[name='type']").val();
		}
		var columntitles = "";
		var columns = "";
		$("#exportform input[name='columns']:checked").each(function(){
			columntitles += ","+$(this).attr("data-title");
			columns += ","+$(this).val();
		});
		window.location.href = "export.php?table="+table+"&typedoc="+typedoc+"&type="+type+"&columntitles="+columntitles+"&columns="+columns+keyword+statee+status+paid+company+supplier+product+modepayment+datestart+dateend+dateduestart+datedueend+datepaidstart+datepaidend+dateremisstart+dateremisend+client+user+imputation+rib+avoir+type+nature+pricemin+pricemax+cmd;
	}
	else{
		$("#exportform .lx-submit a").attr("class","");
		$("#exportform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez choisir des colonnes à exporter !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("#settingsform .lx-submit a").on("click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	var rowcolor = 0;
	if($("#settingsform input[name='rowcolor']").prop("checked") === true){
		rowcolor = 1;
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : $("#settingsform input[name='id']").val(),
			logo : $("#settingsform input[name='picture']").val(),
			cover : $("#settingsform input[name='cover']").val(),
			rowcolor : rowcolor,
			nbrows : $("#settingsform select[name='nbrows']").val(),
			store : $("#settingsform input[name='store']").val(),
			defaultstate : $("#settingsform input[name='defaultstate']:checked").val(),
			currency : $("#settingsform input[name='currency']").val(),
			action : 'editsettings'
		},
		success : function(response){
			$("#settingsform .lx-submit a i").remove();
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="fa fa-times"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
			window.location.href = "settings.php";
		}
	});
});

$("body").delegate("#editnotificationsform input[type='checkbox']","click",function(){
	if($(this).prop("checked") === true){
		$(this).parent().parent().prev("td").find("input").prop("readonly",false);
	}
	else{
		$(this).parent().parent().prev("td").find("input").val("").prop("readonly",true);
		$(this).parent().parent().prev("td").find("ins").remove();
		$(this).parent().parent().prev("td").find("input").removeAttr("style");
	}
});

$("body").delegate("#editnotificationsform .lx-submit a","click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}

	var onfacture = ($("#editnotificationsform input[name='onfacture']").prop("checked") === true?"on":"off");
	var onavoir = ($("#editnotificationsform input[name='onavoir']").prop("checked") === true?"on":"off");	
	var onunpaid = ($("#editnotificationsform input[name='onunpaid']").prop("checked") === true?"on":"off");
	var oncaissein = ($("#editnotificationsform input[name='oncaissein']").prop("checked") === true?"on":"off");
	var oncaisseout = ($("#editnotificationsform input[name='oncaisseout']").prop("checked") === true?"on":"off");
	var onremis = ($("#editnotificationsform input[name='onremis']").prop("checked") === true?"on":"off");
	var roncaissein = ($("#editnotificationsform input[name='roncaissein']").prop("checked") === true?"on":"off");
	var roncaisseout = ($("#editnotificationsform input[name='roncaisseout']").prop("checked") === true?"on":"off");

	var k = 0;
	if(onfacture === "on"){
		isNumber($("#editnotificationsform input[name='facture']"));
		if(!isNumber($("#editnotificationsform input[name='facture']"))){
			k++;
		}
	}
	if(onavoir === "on"){
		isNumber($("#editnotificationsform input[name='avoir']"));
		if(!isNumber($("#editnotificationsform input[name='avoir']"))){
			k++;
		}
	}
	if(oncaissein === "on"){
		isNumber($("#editnotificationsform input[name='caissein']"));
		if(!isNumber($("#editnotificationsform input[name='caissein']"))){
			k++;
		}
	}
	if(oncaisseout === "on"){
		isNumber($("#editnotificationsform input[name='caisseout']"));
		if(!isNumber($("#editnotificationsform input[name='caisseout']"))){
			k++;
		}
	}
	
	if(roncaissein === "on"){
		isNumber($("#editnotificationsform input[name='rcaissein']"));
		if(!isNumber($("#editnotificationsform input[name='rcaissein']"))){
			k++;
		}
	}
	if(roncaisseout === "on"){
		isNumber($("#editnotificationsform input[name='rcaisseout']"));
		if(!isNumber($("#editnotificationsform input[name='rcaisseout']"))){
			k++;
		}
	}
	
	if(k === 0){
		var ajaxurl = "ajax.php";
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				facture : onfacture+"-"+$("#editnotificationsform input[name='facture']").val(),
				avoir : onavoir+"-"+$("#editnotificationsform input[name='avoir']").val(),
				remis : onremis+"-"+$("#editnotificationsform input[name='remis']").val(),
				caissein : oncaissein+"-"+$("#editnotificationsform input[name='caissein']").val(),
				caisseout : oncaisseout+"-"+$("#editnotificationsform input[name='caisseout']").val(),
				rcaissein : roncaisseout+"-"+$("#editnotificationsform input[name='rcaissein']").val(),
				rcaisseout : roncaisseout+"-"+$("#editnotificationsform input[name='rcaisseout']").val(),
				unpaid : onunpaid+"-0",
				inwaiting : "off-0",
				outwaiting : "off-0",
				incach : "off-0",
				outcach : "off-0",
				rremis : "off-0",

				action : 'editnotifications'
			},
			success : function(response){
				$("#editnotificationsform .lx-submit a").attr("class","");
				$("#editnotificationsform .lx-submit a i").remove();
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="fa fa-times"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);
				window.location.href = window.location.href;
			}
		});
	}
	else{
		$("#editnotificationsform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$(".lx-reset-photo").on("click",function(){
	$(".lx-reset-pictures").attr("data-input",$(this).attr("data-input"));
	$(".lx-reset-pictures").attr("data-default",$(this).attr("data-default"));
	$(".lx-reset-pictures").attr("data-file",$(this).attr("data-file"));
	$("ins.lx-title").text($(this).attr("data-img"));
});

$(".lx-reset-pictures").on("click",function(){
	$("input[name='"+$(this).attr("data-input")+"']").val($(this).attr("data-default"));
	$("a[data-input='"+$(this).attr("data-input")+"']").prev("div").find("img").attr("src","images/"+$(this).attr("data-default"));
	$(".lx-popup-content > a > .material-icons").trigger("click");
	$("input[name='"+$(this).attr("data-file")+"']").val("");
});

$("#installform .lx-submit a").on("click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	var onlyproduct = 0;
	if($("#installform input[name='onlyproduct']").prop("checked") === true){
		onlyproduct = 1;
	}
	var onlyservice = 0;
	if($("#installform input[name='onlyservice']").prop("checked") === true){
		onlyservice = 1;
	}
	var useproject = 0;
	if($("#installform input[name='useproject']").prop("checked") === true){
		useproject = 1;
	}
	if($("#installform input[name='oneprice']:checked").length && (onlyproduct === 1 || onlyservice === 1)){
		var ajaxurl = "ajax.php";
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				id : $("#installform input[name='id']").val(),
				oneprice : $("#installform input[name='oneprice']:checked").val(),
				onlyproduct : onlyproduct,
				onlyservice : onlyservice,
				useproject : useproject,
				dategap : $("#settingsform input[name='dategap']").val(),
				action : 'editinstall'
			},
			success : function(response){
				$("#installform .lx-submit a i").remove();
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="fa fa-times"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);
				window.location.href = window.location.href;
			}
		});
	}
	else{
		$("#installform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir tous les options !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("#usersform input[name='roles']").click(function(){
	$("#usersform input[name='rolestext']").val("");
	$("#usersform input[name='roles']").each(function(){
		if($(this).prop("checked") === true){
			$("#usersform input[name='rolestext']").val($("#usersform input[name='rolestext']").val() + "," + $(this).val());
		}
	});
});

$("#usersform input[name='defaultstates']").click(function(){
	$("#usersform input[name='defaultstatestext']").val("");
	$("#usersform input[name='defaultstates']").each(function(){
		if($(this).prop("checked") === true){
			$("#usersform input[name='defaultstatestext']").val($("#usersform input[name='defaultstatestext']").val() + "," + $(this).val());
		}
	});
});

$("#usersform input[name='depots']").click(function(){
	$("#usersform input[name='depotstext']").val("");
	$("#usersform input[name='depots']").each(function(){
		if($(this).prop("checked") === true){
			$("#usersform input[name='depotstext']").val($("#usersform input[name='depotstext']").val() + "," + $(this).val());
		}
	});
});

$("#usersform input[name='companies']").click(function(){
	$("#usersform input[name='companiestext']").val("");
	$("#usersform input[name='companies']").each(function(){
		if($(this).prop("checked") === true){
			$("#usersform input[name='companiestext']").val($("#usersform input[name='companiestext']").val() + "," + $(this).val());
		}
	});
});

$("#usersform input[name='projects']").click(function(){
	$("#usersform input[name='projectstext']").val("");
	$("#usersform input[name='projects']").each(function(){
		if($(this).prop("checked") === true){
			$("#usersform input[name='projectstext']").val($("#usersform input[name='projectstext']").val() + "," + $(this).val());
		}
	});
});

$(".lx-new-user").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#usersform input[name='fullname']").val("");
	$("#usersform input[name='email']").val("").prop("readonly",false).css("cursor","initial");
	$("#usersform input[name='password']").val("");
	$("#usersform input[name='phone']").val("");
	$("#usersform input[name='roles']").each(function(){
		$(this).prop("checked",false);
	});
	$("#usersform input[name='rolestext']").val("");
	$("#usersform input[name='companies']").each(function(){
		$(this).prop("checked",false);
	});
	$("#usersform input[name='companiestext']").val("");
	$("#usersform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-user","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#usersform input[name='fullname']").val($(this).attr("data-fullname"));
	$("#usersform input[name='email']").val($(this).attr("data-email")).prop("readonly",true).css("cursor","not-allowed");
	$("#usersform input[name='password']").val($(this).attr("data-password"));
	$("#usersform input[name='phone']").val($(this).attr("data-phone"));
	$("#usersform input[name='rolestext']").val($(this).attr("data-roles"));
	$("#usersform input[name='roles']").each(function(){
		var val = $(this).val();
		if($(this).val() === "Consulter Factures" || $(this).val() === "Supprimer Factures" || $(this).val() === "Exporter Factures"){
			val = $(this).val()+",";
		}
		val = $("#usersform input[name='rolestext']").val().indexOf(val);
		if(val !== -1){
			$(this).prop("checked",true);
		}
		else{
			$(this).prop("checked",false);
		}
	});
	$("#usersform input[name='companiestext']").val($(this).attr("data-companies"));
	$("#usersform input[name='companies']").each(function(){
		var val = $("#usersform input[name='companiestext']").val().indexOf($(this).val());
		if(val !== -1){
			$(this).prop("checked",true);
		}
		else{
			$(this).prop("checked",false);
		}
	});
	$("#usersform input[name='id']").val($(this).attr("data-id"));
});

$("#usersform .lx-submit a").on("click",function(){
	isNotEmpty($("#usersform input[name='fullname']"));
	isNotEmpty($("#usersform input[name='email']"));
	isPassword($("#usersform input[name='password']"));
	isNotEmpty($("#usersform input[name='companiestext']"));
	if(isNotEmpty($("#usersform select[name='type']"))
	&& isNotEmpty($("#usersform input[name='email']"))
	&& isPassword($("#usersform input[name='password']"))
	&& isNotEmpty($("#usersform input[name='companiestext']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#usersform input[name='id']").val(),
					fullname : $("#usersform input[name='fullname']").val(),
					email : $("#usersform input[name='email']").val(),
					password : $("#usersform input[name='password']").val(),
					phone : $("#usersform input[name='phone']").val(),
					roles : $("#usersform input[name='rolestext']").val(),
					companies : $("#usersform input[name='companiestext']").val(),
					action : 'adduser'
				},
				success : function(response){
					$("#usersform .lx-submit a").attr("class","");
					$("#usersform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadUsers("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
						window.location.href = "users.php";
					}
				}
			});
		}
	}
	else{
		$("#usersform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-user","click",function(){
	filterClicked = "yes";
	loadUsers("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-user","click",function(){
	filterClicked = "yes";
	loadUsers("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-user","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-reset-user","click",function(){
	$(".lx-reset-pass").attr("data-id",$(this).attr("data-id"));
});

$(".lx-popup-content").delegate(".lx-reset-pass","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'resetpassword'
		},
		success : function(response){
			$(".lx-popup-content > a > .material-icons").trigger("click");
			loadUsers("1");
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien rénitialisé<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
		}
	});
});

$("body").delegate(".lx-restore-user","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoreuser'
		},
		success : function(response){
			loadUsers("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-user","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deleteuserpermanently'
		},
		success : function(response){
			if(response !== ""){
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
			else{
				loadUsers("0");
			}
		}
	});
});

function loadUsers(state){
	if($(".lx-table-users .lx-loading").length === 0){
		$(".lx-table-users").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadusers'
		},
		success : function(response){
			$(".lx-table-users .lx-loading").remove();
			$(".lx-table-users").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-show-roles","click",function(){
	if($(this).parent().next("div").css("display") === "block"){
		$(this).parent().next("div").slideToggle();
		$(this).html('<i class="fa fa-plus"></i> Afficher plus');
	}
	else{
		$(this).parent().next("div").slideToggle();
		$(this).html('<i class="fa fa-minus"></i> Afficher moins');
	}
});

$("#accountform .lx-submit a").on("click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	isNotEmpty($("#accountform input[name='fullname']"));
	isPhone($("#accountform input[name='phone']"));
	if(isNotEmpty($("#accountform input[name='fullname']"))
	&& isPhone($("#accountform input[name='phone']"))){
		var ajaxurl = "ajax.php";
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				id : $("#accountform input[name='id']").val(),
				fullname : $("#accountform input[name='fullname']").val(),
				picture : $("#accountform input[name='picture']").val(),
				phone : $("#accountform input[name='phone']").val(),
				action : 'editaccount'
			},
			success : function(response){
				$("#accountform .lx-submit a i").remove();
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);
				window.location.href = "account.php";
			}
		});
	}
	else{
		$("#accountform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("#passwordform .lx-submit a").on("click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	var ajaxurl = "ajax.php";
	isPassword($("#passwordform input[name='newpassword1']"));
	isPassword($("#passwordform input[name='newpassword2']"));
	if(isPassword($("#passwordform input[name='newpassword1']"))
	&& isPassword($("#passwordform input[name='newpassword2']"))){
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				id : $("#passwordform input[name='id']").val(),
				oldpassword : $("#passwordform input[name='oldpassword']").val(),
				newpassword1 : $("#passwordform input[name='newpassword1']").val(),
				newpassword2 : $("#passwordform input[name='newpassword2']").val(),
				action : 'editpassword'
			},
			success : function(response){
				$("#passwordform .lx-submit a i").remove();
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				if(response === "1"){
					$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
				}
				else if(response === "2"){
					$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir tous les champs<i class="material-icons"></i></p></div>');
				}
				else if(response === "3"){
					$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Ancien mot de passe incorrect<i class="material-icons"></i></p></div>');
				}
				else if(response === "4"){
					$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> les nouveaux mots de passes doivent être identiques<i class="material-icons"></i></p></div>');
				}
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);
				$("#passwordform input[name='oldpassword']").val("");
				$("#passwordform input[name='newpassword1']").val("");
				$("#passwordform input[name='newpassword2']").val("");
			}
		});
	}
	else{
		$("#passwordform .lx-submit a i").remove();
	}
});

$("#commandsform select[name='company']").on("change",function(){
	var company = $(this).val();
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : company,
			category : $("#commandsform input[name='category']").val(),
			action : 'loadproductslist'
		},
		success : function(response){
			$("#commandsform select[name='product']").html(response);
			toDropDownTargeted("#commandsform select[name='product']");
			$("#commandsform select[name='product']").trigger("change");
		}
	});
	
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : company,
			category : $("#commandsform input[name='category']").val(),
			action : 'loadunitslist'
		},
		success : function(response){
			$("#commandsform select[name='unit']").html(response);
			toDropDownTargeted("#commandsform select[name='unit']");
			$("#commandsform select[name='unit']").trigger("change");
		}
	});

	if(transform === 0){
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				company : company,
				category : $("#commandsform input[name='category']").val(),
				action : 'loadclientslist'
			},
			success : function(response){
				$("#commandsform select[name='client']").html(response);
				toDropDownTargeted("#commandsform select[name='client']");
				$("#commandsform select[name='client']").trigger("change");
			}
		});
	}
	
	if($("#commandsform input[name='id']").val() === "0" || $("#commandsform input[name='id']").val() === "-1"){
		if($(this).val() !== ""){
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					company : company,
					type : $("#commandsform input[name='type']").val(),
					category : $("#commandsform input[name='categories']").val(),
					action : 'loadcode'
				},
				success : function(response){
					$("#commandsform input[name='code']").val(response);
					$(".lx-code-label").text($("#commandsform input[name='prefix']").val()+convertDateFormat($('input[name="dateaddcommand"]').val())+"-");
					$("#commandsform input[name='code']").trigger("blur");
				}
			});
		}
		else{
			$("#commandsform input[name='code']").val("");
			$(".lx-code-label").text($("#commandsform input[name='prefix']").val()+convertDateFormat($('input[name="dateaddcommand"]').val())+"-");
			$("#commandsform input[name='code']").trigger("blur");
		}
	}
	transform = 0;
});

$("#commandsform input[name='code']").on("keyup",function(){
	if($("#commandsform input[name='prefix']").val() === "BRC"){
		$("#commandsform input[name='code']").css("padding-left","77px");
	}
	else{
		$("#commandsform input[name='code']").css("padding-left","67px");
	}
});

$("#commandsform select[name='product']").on("change",function(){
	$("#commandsform select[name='unit']").val($(this).find("option:selected").attr("data-unit"));
	toDropDownTargeted("#commandsform select[name='unit']");
});

$(".lx-new-tva").on("click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$("#tvasform input[name='tva']").val("");
	$("#tvasform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-tva","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$("#tvasform input[name='tva']").val($(this).attr("data-titl"));
	$("#tvasform input[name='id']").val($(this).attr("data-id"));
});

$("#tvasform .lx-submit a").on("click",function(){
	isNotEmpty($("#tvasform input[name='title']"));
	if(isNotEmpty($("#tvasform input[name='title']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#tvasform input[name='id']").val(),
					tva : $("#tvasform input[name='tva']").val(),
					action : 'addtva'
				},
				success : function(response){
					$("#tvasform .lx-submit a").attr("class","");
					$("#tvasform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadTVAs();
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				}
			});
		}
	}
	else{
		$("#tvasform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-tva","click",function(){
	filterClicked = "yes";
	loadTVAs();
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-tva","click",function(){
	filterClicked = "yes";
	loadTVAs();
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-tva","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-tva","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoretva'
		},
		success : function(response){
			loadTVAs();
		}
	});
});

$("body").delegate(".lx-delete-permanently-tva","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletetvapermanently'
		},
		success : function(response){
			loadTVAs();
		}
	});
});

function loadTVAs(){
	if($(".lx-table-tvas .lx-loading").length === 0){
		$(".lx-table-tvas").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'loadtvas'
		},
		success : function(response){
			$(".lx-table-tvas .lx-loading").remove();
			$(".lx-table-tvas").html(response);
		}
	});
}

$(".lx-new-company").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#companiesform input[name='rs']").val("");
	$("#companiesform input[name='phone']").val("");
	$("#companiesform input[name='address']").val("");
	$("#companiesform input[name='email']").val("");
	$("#companiesform input[name='website']").val("");
	$("#companiesform input[name='capital']").val("");
	$("#companiesform input[name='rc']").val("");
	$("#companiesform input[name='patente']").val("");
	$("#companiesform input[name='iff']").val("");
	$("#companiesform input[name='cnss']").val("");
	$("#companiesform input[name='ice']").val("");
	$("#companiesform input[name='facture']").val("1").prop("readonly",false);
	$("#companiesform input[name='devis']").val("1").prop("readonly",false);
	$("#companiesform input[name='avoir']").val("1").prop("readonly",false);
	$("#companiesform input[name='br']").val("1").prop("readonly",false);
	$("#companiesform input[name='factureproforma']").val("1").prop("readonly",false);
	$("#companiesform input[name='bl']").val("1").prop("readonly",false);
	$("#companiesform input[name='bs']").val("1").prop("readonly",false);
	$("#companiesform input[name='blcommand']").val("1").prop("readonly",false);
	$("#companiesform input[name='brcommand']").val("1").prop("readonly",false);
	$("#companiesform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-company","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#companiesform input[name='rs']").val($(this).attr("data-rs"));
	$("#companiesform input[name='phone']").val($(this).attr("data-phone"));
	$("#companiesform input[name='address']").val($(this).attr("data-address"));
	$("#companiesform input[name='email']").val($(this).attr("data-email"));
	$("#companiesform input[name='website']").val($(this).attr("data-website"));
	$("#companiesform input[name='capital']").val($(this).attr("data-capital"));
	$("#companiesform input[name='rc']").val($(this).attr("data-rc"));
	$("#companiesform input[name='patente']").val($(this).attr("data-patente"));
	$("#companiesform input[name='iff']").val($(this).attr("data-iff"));
	$("#companiesform input[name='cnss']").val($(this).attr("data-cnss"));
	$("#companiesform input[name='ice']").val($(this).attr("data-ice"));
	$("#companiesform input[name='facture']").val($(this).attr("data-facture")).prop("readonly",Boolean.valueOf($(this).attr("data-facturereadonly")));
	$("#companiesform input[name='devis']").val($(this).attr("data-devis")).prop("readonly",Boolean.valueOf($(this).attr("data-devisreadonly")));
	$("#companiesform input[name='avoir']").val($(this).attr("data-avoir")).prop("readonly",Boolean.valueOf($(this).attr("data-avoirreadonly")));
	$("#companiesform input[name='br']").val($(this).attr("data-br")).prop("readonly",Boolean.valueOf($(this).attr("data-brreadonly")));
	$("#companiesform input[name='factureproforma']").val($(this).attr("data-factureproforma")).prop("readonly",Boolean.valueOf($(this).attr("data-factureproformareadonly")));
	$("#companiesform input[name='bl']").val($(this).attr("data-bl")).prop("readonly",Boolean.valueOf($(this).attr("data-blreadonly")));
	$("#companiesform input[name='bs']").val($(this).attr("data-bs")).prop("readonly",Boolean.valueOf($(this).attr("data-bsreadonly")));
	$("#companiesform input[name='blcommand']").val($(this).attr("data-blcommand")).prop("readonly",Boolean.valueOf($(this).attr("data-blcommandreadonly")));
	$("#companiesform input[name='brcommand']").val($(this).attr("data-brcommand")).prop("readonly",Boolean.valueOf($(this).attr("data-brcommandreadonly")));
	$("#companiesform input[name='id']").val($(this).attr("data-id"));
});

$("#companiesform .lx-submit a").on("click",function(){
	isNotEmpty($("#companiesform input[name='rs']"));
	isNumber($("#companiesform input[name='facture']"));
	isNumber($("#companiesform input[name='devis']"));
	isNumber($("#companiesform input[name='avoir']"));
	isNumber($("#companiesform input[name='br']"));
	isNumber($("#companiesform input[name='factureproforma']"));
	isNumber($("#companiesform input[name='bl']"));
	isNumber($("#companiesform input[name='bs']"));
	isNumber($("#companiesform input[name='blcommand']"));
	isNumber($("#companiesform input[name='brcommand']"));
	if(isNotEmpty($("#companiesform input[name='rs']"))
	&& isNumber($("#companiesform input[name='facture']"))
	&& isNumber($("#companiesform input[name='devis']"))
	&& isNumber($("#companiesform input[name='avoir']"))
	&& isNumber($("#companiesform input[name='br']"))
	&& isNumber($("#companiesform input[name='factureproforma']"))
	&& isNumber($("#companiesform input[name='bl']"))
	&& isNumber($("#companiesform input[name='bs']"))
	&& isNumber($("#companiesform input[name='blcommand']"))
	&& isNumber($("#companiesform input[name='brcommand']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#companiesform input[name='id']").val(),
					rs : $("#companiesform input[name='rs']").val(),
					phone : $("#companiesform input[name='phone']").val(),
					address : $("#companiesform input[name='address']").val(),
					email : $("#companiesform input[name='email']").val(),
					website : $("#companiesform input[name='website']").val(),
					capital : $("#companiesform input[name='capital']").val(),
					rc : $("#companiesform input[name='rc']").val(),
					patente : $("#companiesform input[name='patente']").val(),
					iff : $("#companiesform input[name='iff']").val(),
					cnss : $("#companiesform input[name='cnss']").val(),
					ice : $("#companiesform input[name='ice']").val(),
					facture : $("#companiesform input[name='facture']").val(),
					devis : $("#companiesform input[name='devis']").val(),
					avoir : $("#companiesform input[name='avoir']").val(),
					br : $("#companiesform input[name='br']").val(),
					factureproforma : $("#companiesform input[name='factureproforma']").val(),
					bl : $("#companiesform input[name='bl']").val(),
					bs : $("#companiesform input[name='bs']").val(),
					blcommand : $("#companiesform input[name='blcommand']").val(),
					brcommand : $("#companiesform input[name='brcommand']").val(),
					action : 'addcompany'
				},
				success : function(response){
					$("#companiesform .lx-submit a").attr("class","");
					$("#companiesform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadCompanies("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				}
			});
		}
	}
	else{
		$("#companiesform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-company","click",function(){
	filterClicked = "yes";
	loadCompanies("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-company","click",function(){
	filterClicked = "yes";
	loadCompanies("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-company","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-company","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restorecompany'
		},
		success : function(response){
			loadCompanies("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-company","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletecompanypermanently'
		},
		success : function(response){
			if(response !== ""){
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
			else{
				loadCompanies("0");
			}
		}
	});
});

function loadCompanies(state){
	if($(".lx-table-companies .lx-loading").length === 0){
		$(".lx-table-companies").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadcompanies'
		},
		success : function(response){
			$(".lx-table-companies .lx-loading").remove();
			$(".lx-table-companies").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-new-bankaccount","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#bankaccountsform input[name='rib']").val("");
	$("#bankaccountsform input[name='bank']").val("");
	$("#bankaccountsform input[name='agency']").val("");
	$("#bankaccountsform input[name='company']").val($(this).attr("data-company"));
	$("#bankaccountsform input[name='id']").val($(this).attr("data-id"));
});

$("body").delegate(".lx-edit-bankaccount","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#bankaccountsform input[name='rib']").val($(this).attr("data-rib"));
	$("#bankaccountsform input[name='bank']").val($(this).attr("data-bank"));
	$("#bankaccountsform input[name='agency']").val($(this).attr("data-agency"));
	$("#bankaccountsform input[name='company']").val($(this).attr("data-company"));
	$("#bankaccountsform input[name='id']").val($(this).attr("data-id"));
});

$("#bankaccountsform .lx-submit a").on("click",function(){
	isNotEmpty($("#bankaccountsform input[name='rib']"));
	isNotEmpty($("#bankaccountsform input[name='bank']"));
	if(isNotEmpty($("#bankaccountsform input[name='rib']"))
	&& isNotEmpty($("#bankaccountsform input[name='bank']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#bankaccountsform input[name='id']").val(),
					rib : $("#bankaccountsform input[name='rib']").val(),
					bank : $("#bankaccountsform input[name='bank']").val(),
					agency : $("#bankaccountsform input[name='agency']").val(),
					company : $("#bankaccountsform input[name='company']").val(),
					action : 'addbankaccount'
				},
				success : function(response){
					$("#bankaccountsform .lx-submit a").attr("class","");
					$("#bankaccountsform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadCompanies("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				}
			});
		}
	}
	else{
		$("#bankaccountsform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-bankaccount","click",function(){
	filterClicked = "yes";
	loadCompanies("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-bankaccount","click",function(){
	filterClicked = "yes";
	loadCompanies("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-bankaccount","click",function(){
	$(".deleterecord1 .lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-bankaccount","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restorebankaccount'
		},
		success : function(response){
			loadCompanies("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-bankaccount","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletebankaccountpermanently'
		},
		success : function(response){
			if(response !== ""){
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
			else{
				loadCompanies("0");
			}
		}
	});
});

$("body").delegate(".lx-new-extrainfo","click",function(){
	$(".lx-tabs-docs a").attr("data-company",$(this).attr("data-company"));
	$("#costumizeextrainfoform input[name='company']").val($(this).attr("data-company"));
	$(".lx-tabs-docs a:eq(0)").trigger("click");
});

$(".lx-tabs-docs a").on("click",function(){
	if($(".lx-docs-form .lx-loading").length === 0){
		$(".lx-docs-form").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var el = $(this);
	var company = $(this).attr("data-company");
	var document = $(this).attr("data-document");
	$("#costumizeextrainfoform input[name='document']").val(document);
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : company,
			document : document,
			action : 'loaddocsextrainfo'
		},
		success : function(response){
			$(".lx-tabs-docs a").removeClass("active");
			el.addClass("active");
			$(".lx-docs-form .lx-loading").remove();
			$(".lx-docs-form").html(response);
		}
	});	
});

$("#costumizeextrainfoform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : $("#costumizeextrainfoform input[name='company']").val(),
			document : $("#costumizeextrainfoform input[name='document']").val(),
			modepayment : $("#costumizeextrainfoform textarea[name='modepayment']").val(),
			conditions : $("#costumizeextrainfoform textarea[name='conditions']").val(),
			abovetable : $("#costumizeextrainfoform textarea[name='abovetable']").val(),
			action : 'savedocinfo'
		},
		success : function(response){
			$("#extrainfoblform .lx-submit a i").remove();
			loadCompanies("1");
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Note enregistré<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
		}
	});
});

$(".lx-start-inventaire").on("click",function(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'startinventaire'
		},
		success : function(response){
			window.location.href = "inventaire.php";
		}
	});	
});

$(".lx-end-inventaire").on("click",function(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'endinventaire'
		},
		success : function(response){
			window.location.href = "inventaire.php";
		}
	});	
});

$("body").delegate(".lx-delete-inventaire","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

function loadInventaire(state){
	if($(".lx-table-inventaire .lx-loading").length === 0){
		$(".lx-table-inventaire").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadinventaire'
		},
		success : function(response){
			$(".lx-table-inventaire .lx-loading").remove();
			$(".lx-table-inventaire").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadInventaireDetails(){
	if($(".lx-table-inventaire-details .lx-loading").length === 0){
		$(".lx-table-inventaire-details").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			keyword : $("#keyword").val(),	
			depot : $("#depot").attr("data-ids"),	
			company : $("#company").attr("data-ids"),	
			supplier : $("#supplier").attr("data-ids"),	
			family : $("#family").attr("data-ids"),	
			brand : $("#brand").val(),	
			qtymin : ($("#qtymin").prop("checked") === true?"1":"0"),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadinventairedetails'
		},
		success : function(response){
			$(".lx-table-inventaire-details .lx-loading").remove();
			$(".lx-table-inventaire-details").html(response);

			onScroll();
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-delete-placement","click",function(){
	$(".deleterecord1 .lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$(".lx-print-inventaire").on("click",function(){
	var keyword = "keyword="+$("#keyword").val();
	var depot = "&depot="+$("#depot").attr("data-ids");
	var family = "&family="+ $("#family").attr("data-ids");
	var brand = "&brand="+ $("#brand").val();
	var qtymin = "&qtymin="+ ($("#qtymin").prop("checked") === true?"1":"0");
	var sortby = "&sortby="+ $(".lx-keyword input[name='sortby']").val();
	var orderby = "&orderby="+ $(".lx-keyword input[name='orderby']").val();
	// window.location.href = "printinventaire.php?"+keyword+depot+family+brand+qtymin+sortby+orderby;
	window.open(
		'printinventaire.php?'+keyword+depot+family+brand+qtymin+sortby+orderby,
		'_blank'
	);
});

$("body").delegate("input[name='qtyphysical']","keyup blur paste change",function(){
	if($(this).val() !== ""){
		var qtysystem = parseFloat($(this).parent().parent().parent().prev("td").find("b").text());
		var qtyphysical = parseFloat($(this).val());
		if(isNumeric(qtyphysical - qtysystem)){
			$(this).parent().parent().parent().next("td").find("span").text(qtyphysical-qtysystem);
			if(qtyphysical - qtysystem > 0){
				$(this).parent().parent().parent().next("td").css("background","#b4eeb4");
			}
			else if(qtyphysical - qtysystem < 0){
				$(this).parent().parent().parent().next("td").css("background","#ff7373");
			}
			else{
				$(this).parent().parent().parent().next("td").css("background","none");
			}
		}
	}
	else{
		$(this).parent().parent().parent().next("td").find("span").text("");
		$(this).parent().parent().parent().next("td").css("background","none");
	}
});

$("#inventaireform .lx-submit a.lx-save-inventaire").on("click",function(){
	var k = 0;
	if(confirm('Cette action est irréversible, voulez vous continue?')){
		k = 0;
	} 
	else{
		k = 1;
	}
	if(k === 0){
		var content = "";
		$("input[name='qtyphysical']").each(function(){
			if($(this).val() !== ""){
				content += "|"+$(this).attr("data-prevqty")+":"+$(this).val();
			}
		});
		if(content !== ""){
			if($(this).attr("class") !== "lx-disabled"){
				$(this).attr("class","lx-disabled");
				if($(this).find("i").length === 0){
					$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
				}
				var ajaxurl = "ajax.php";
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						note : $("#inventaireform textarea[name='note']").val(),
						content : content,
						action : 'addinventaire'
					},
					success : function(response){
						$("#inventaireform .lx-submit a").attr("class","");
						$("#inventaireform .lx-submit a i").remove();
						window.location.href = "inventaire.php";
					}
				});
			}
		}
		else{
			$("#inventaireform .lx-submit a i").remove();
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez Saisir au moins une quanitité physique !!<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
		}
	}
});

function isNumeric(value) {
  return typeof value === 'number' && !isNaN(value);
}

$(".lx-new-supplier").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#suppliersform input[name='codefo']").val("");
	$("#suppliersform input[name='company']").val("");
	$("#suppliersform .lx-advanced-select input[type='checkbox']").each(function(){
		$(this).prop("checked",false);
	});
	$("#suppliersform input[name='title']").val("");
	$("#suppliersform input[name='respname']").val("");
	$("#suppliersform input[name='respphone']").val("");
	$("#suppliersform input[name='respemail']").val("");
	$("#suppliersform input[name='respfax']").val("");
	$("#suppliersform input[name='ice']").val("");
	$("#suppliersform input[name='address']").val("");
	$("#suppliersform textarea[name='note']").val("");
	$("#suppliersform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-supplier","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#suppliersform input[name='codefo']").val($(this).attr("data-codefo"));
	$("#suppliersform input[name='company']").attr("data-ids",$(this).attr("data-company"));
	$("#suppliersform input[name='company']").val($(this).attr("data-companytxt"));
	var companies = $(this).attr("data-company").split(",");
	$("#suppliersform .lx-advanced-select input[type='checkbox']").each(function(){
		for(var i=0;i<companies.length;i++){
			if($(this).attr("data-ids") === companies[i]){
				$(this).prop("checked",true);
				break;
			}
			else{
				$(this).prop("checked",false);
			}
		}
	});
	$("#suppliersform input[name='title']").val($(this).attr("data-titl"));
	$("#suppliersform input[name='respname']").val($(this).attr("data-respname"));
	$("#suppliersform input[name='respphone']").val($(this).attr("data-respphone"));
	$("#suppliersform input[name='respemail']").val($(this).attr("data-respemail"));
	$("#suppliersform input[name='respfax']").val($(this).attr("data-respfax"));
	$("#suppliersform input[name='ice']").val($(this).attr("data-ice"));
	$("#suppliersform input[name='address']").val($(this).attr("data-address"));
	$("#suppliersform textarea[name='note']").val($(this).attr("data-note"));
	$("#suppliersform input[name='id']").val($(this).attr("data-id"));
});

$("#suppliersform .lx-submit a").on("click",function(){
	isNotEmpty($("#suppliersform input[name='company']"));
	isNotEmpty($("#suppliersform input[name='title']"));
	isNotEmpty($("#suppliersform input[name='respname']"));
	if(isNotEmpty($("#suppliersform input[name='title']"))
	&& isNotEmpty($("#suppliersform input[name='company']"))
	&& isNotEmpty($("#suppliersform input[name='respname']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#suppliersform input[name='id']").val(),
					codefo : $("#suppliersform input[name='codefo']").val(),
					company : $("#suppliersform input[name='company']").attr("data-ids"),
					title : $("#suppliersform input[name='title']").val(),
					respname : $("#suppliersform input[name='respname']").val(),
					respphone : $("#suppliersform input[name='respphone']").val(),
					respemail : $("#suppliersform input[name='respemail']").val(),
					respfax : $("#suppliersform input[name='respfax']").val(),
					ice : $("#suppliersform input[name='ice']").val(),
					address : $("#suppliersform input[name='address']").val(),
					note : $("#suppliersform textarea[name='note']").val(),
					action : 'addsupplier'
				},
				success : function(response){
					$("#suppliersform .lx-submit a").attr("class","");
					$("#suppliersform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadSuppliers("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				}
			});
		}
	}
	else{
		$("#suppliersform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-supplier","click",function(){
	filterClicked = "yes";
	loadSuppliers("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-supplier","click",function(){
	filterClicked = "yes";
	loadSuppliers("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-supplier","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-supplier","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoresupplier'
		},
		success : function(response){
			loadSuppliers("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-supplier","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletesupplierpermanently'
		},
		success : function(response){
			if(response !== ""){
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
			else{
				loadSuppliers("0");
			}
		}
	});
});

function loadSuppliers(state){
	if($(".lx-table-suppliers .lx-loading").length === 0){
		$(".lx-table-suppliers").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),	
			paid : $("#paid").attr("data-ids"),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadsuppliers'
		},
		success : function(response){
			$(".lx-table-suppliers .lx-loading").remove();
			$(".lx-table-suppliers").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$(".lx-add-command").on("click",function(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			ids : $("#commands").val().substring(1),
			action : 'loadrecallproducts'
		},
		success : function(response){
			$(".lx-table-recall-products").html(response);
		}
	});	
});

$(".lx-popup-content").delegate(".lx-new-supplier-command","click",function(){
	var company = $(this).attr("data-company");
	var project = $(this).attr("data-project");
	var supplier = $(this).attr("data-supplier");
	var invoiced = $(this).attr("data-invoiced");
	$(".lx-popup-content > a > .material-icons").trigger("click");
	window.setTimeout(function(){
		$(".lx-new-commandsupplier").trigger("click");
	},500);
	window.setTimeout(function(){
		$("#commandsuppliersform select[name='company']").val(company).prop("disabled",true).trigger("change");
		$("#commandsuppliersform select[name='supplier']").val(supplier).prop("disabled",true);
		$("#commandsuppliersform select[name='invoiced']").val(invoiced).prop("disabled",true);
	},600);
	window.setTimeout(function(){
		$("#commandsuppliersform select[name='project']").val(project);
	},600);	
	$("#commandsuppliersform select[name='invoiced']").trigger("change");
});

$(".lx-popup-content").delegate(".lx-cancel-supplier-command","click",function(){
	$(".lx-popup-content > a > .material-icons").trigger("click");
	window.setTimeout(function(){
		$(".lx-generate-blcommand").trigger("click");
	},500);
});

$(".lx-popup-content").delegate(".lx-generate-bl-command","click",function(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			ids : $("#commands").val().substring(1),
			action : 'generatebl'
		},
		success : function(response){
			if(response === ""){
				$(".lx-popup-content > a > .material-icons").trigger("click");
				$(".lx-table-commandsuppliers .lx-loading").remove();
				loadCommandSuppliers($(".lx-pagination ul").attr("data-state"));
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien généré<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);
				$(".lx-new-supplier-command").attr("data-command","");
				$(".lx-new-supplier-command").attr("data-company","");
				$(".lx-new-supplier-command").attr("data-project","");
				$(".lx-new-supplier-command").attr("data-supplier","");
				$(".lx-new-supplier-command").attr("data-invoiced","");
				$("#commandsuppliersform select[name='company']").val("").prop("disabled",false);
				$("#commandsuppliersform select[name='project']").val("0").prop("disabled",false);
				$("#commandsuppliersform select[name='supplier']").val("").prop("disabled",false);
				$("#commandsuppliersform select[name='invoiced']").val("").prop("disabled",false);
				$("#commands").val("");				
			}
			else{
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
		}
	});
});

$(".lx-popup-content").delegate(".lx-cancel-bl-command","click",function(){
	$(".lx-new-supplier-command").attr("data-command","");
	$(".lx-new-supplier-command").attr("data-company","");
	$(".lx-new-supplier-command").attr("data-project","");
	$(".lx-new-supplier-command").attr("data-supplier","");
	$(".lx-new-supplier-command").attr("data-invoiced","");
	$("#commandsuppliersform select[name='company']").val("").prop("disabled",false);
	$("#commandsuppliersform select[name='project']").val("0").prop("disabled",false);
	$("#commandsuppliersform select[name='supplier']").val("").prop("disabled",false);
	$("#commandsuppliersform select[name='invoiced']").val("").prop("disabled",false);
	$("#commands").val("");
});


$("body").delegate(".lx-duplicate-commandsupplier","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'duplicatecommandsupplier'
		},
		success : function(response){
			loadCommandSuppliers("1");
		}
	});
});

$("body").delegate(".lx-trash-commandsupplier","click",function(){
	filterClicked = "yes";
	loadCommandSuppliers("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-commandsupplier","click",function(){
	filterClicked = "yes";
	loadCommandSuppliers("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-commandsupplier","click",function(){
	$(".updatecaisse input[name='price']").val($(this).attr("data-price"));
	$(".updatecaisse input[name='command']").val($(this).attr("data-id"));
	$(".updatecaisse input[name='type']").val($(this).attr("data-type"));
	$(".updatecaisse input[name='company']").val($(this).attr("data-company"));
	$(".updatecaisse input[name='project']").val($(this).attr("data-project"));
	$(".updatecaisse input[name='libelle']").val($(this).attr("data-libelle"));
	$(".updatecaisse input[name='nature']").val($(this).attr("data-nature"));
	$(".updatecaisse input[name='invoiced']").val($(this).attr("data-invoiced"));
	$(".lx-delete-record").attr("data-action",$(this).attr("data-action"));
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$(".lx-popup-content").delegate(".lx-delete-commandsupplier-child","click",function(){
	$(".lx-delete-record").attr("data-action",$(this).attr("data-action"));
	$(".lx-delete-record").attr("data-ids",$(this).attr("data-ids"));
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-commandsupplier","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restorecommandsupplier'
		},
		success : function(response){
			loadCommandSuppliers("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-commandsupplier","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletecommandsupplierpermanently'
		},
		success : function(response){
			loadCommandSuppliers("0");
		}
	});
});

function loadCommandSuppliers(state){
	if($(".lx-table-commandsuppliers .lx-loading").length === 0){
		$(".lx-table-commandsuppliers").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),	
			supplier : $("#supplier").attr("data-ids"),	
			company : $("#company").attr("data-ids"),	
			project : $("#project").attr("data-ids"),	
			product : $("#product").attr("data-ids"),	
			service : $("#service").attr("data-ids"),
			invoiced : $("#invoiced").attr("data-ids"),
			paid : $("#paid").val(),
			modepayment : $("#modepayment").val(),	
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadcommandsuppliers'
		},
		success : function(response){
			$(".lx-table-commandsuppliers .lx-loading").remove();
			$(".lx-table-commandsuppliers").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-download-file1","click",function(){
	//$(".lx-with-signature").attr("href",$(this).attr("data-href")+"&signature=1");
	//$(".lx-without-signature").attr("href",$(this).attr("data-href")+"&signature=0");
	$("#printform input[name='href']").val($(this).attr("data-href"));
});

$("body").delegate("#printform .lx-submit a","click",function(){
	isNotEmpty($("#printform select[name='signature']"));
	isNotEmpty($("#printform select[name='header']"));
	if(isNotEmpty($("#printform select[name='signature']"))
	&& isNotEmpty($("#printform select[name='header']"))){
		window.location.href = $("#printform input[name='href']").val()+$("#printform select[name='signature']").val()+$("#printform select[name='header']").val()
	}
});

$("body").delegate(".lx-download-file","click",function(){
	$(".lx-popup-content > a > .material-icons").trigger("click");
	$(".lx-table").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	window.setTimeout(function(){
		$(".lx-table .lx-loading").remove();
	},4000);
});

$("body").delegate(".lx-edit-reception-state","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#receptionstateform input[name='refdoc']").val($(this).attr("data-refdoc"));
	if($(this).attr("data-datereceived") !== ""){
		$("#receptionstateform input[name='datereception']").val($(this).attr("data-datereceived"));
	}
	$("#receptionstateform input[name='received']").val($(this).attr("data-received"));
	var received = $(this).attr("data-received");
	$(".lx-reception-state").each(function(){
		$(this).find("i").remove();
		$(this).text($(this).text().trim());
		if($(this).text() === received){
			$(this).prepend('<i class="fa fa-check"></i> ');
		}
	});	
	$("#receptionstateform input[name='id']").val($(this).attr("data-id"));
});

$(".lx-reception-state").on("click",function(){
	$("#receptionstateform input[name='received']").val($(this).attr("data-received"));
	$(".lx-reception-state i").remove();
	$(this).prepend('<i class="fa fa-check"></i> ');
	if($("#receptionstateform input[name='received']").val() === "Réceptionné"){
		$("#receptionstateform input[name='datereception']").val(getCurrentDate());
	}
	else{
		$("#receptionstateform input[name='datereception']").val("");
		$("#receptionstateform input[name='refdoc']").val("");
	}
});

$("#receptionstateform .lx-submit a").on("click",function(){
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : $("#receptionstateform input[name='id']").val(),
			received : $("#receptionstateform input[name='received']").val(),
			refdoc : $("#receptionstateform input[name='refdoc']").val(),
			datereceived : $("#receptionstateform input[name='datereception']").val(),
			action : 'addreceptionstate'
		},
		success : function(response){
			if(response !== ""){
				$("#receptionstateform .lx-submit a i").remove();
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
			else{
				$("#receptionstateform .lx-submit a i").remove();
				$(".lx-popup-content > a > .material-icons").trigger("click");
				loadCommandSuppliers("1");
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);
			}
		}
	});				
});

$("body").delegate(".lx-upload-files input","change",function(){
	if($(this).next("a").find(".fa-circle-notch").length === 0){
		$(this).next("a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	for(var i = 0;i < _($(this).attr("id")).files.length;i++){
		uploadFiles(_($(this).attr("id")).files[i],$(this).attr("data-id"),$(this).attr("data-table"));
	}
});

function uploadFiles(file,id,table){
	var formdata = new FormData();
	formdata.append("file2", file);
	formdata.append("id", id);
	formdata.append("table", table);
	var ajax = new XMLHttpRequest();
	ajax.addEventListener("load", completeHandler, false);
	ajax.open("POST", "file_upload_parser.php");
	ajax.send(formdata);
	function completeHandler(event){
		if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
			if($(".lx-pagination ul").attr("data-table") === "devis"){
				loadDevis($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
				loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bl"){
				loadBL($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bs"){
				loadBS($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "br"){
				loadBR($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "factures"){
				loadFactures($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "avoirs"){
				loadAvoirs($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bc"){
				loadBC($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bre"){
				loadBRC($(".lx-pagination ul").attr("data-state"));
			}
		}
	}	
}

$(".lx-new-source").on("click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$("#sourcesform input[name='title']").val("");
	$("#sourcesform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-source","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$("#sourcesform input[name='title']").val($(this).attr("data-titl"));
	$("#sourcesform input[name='id']").val($(this).attr("data-id"));
});

$("#sourcesform .lx-submit a").on("click",function(){
	isNotEmpty($("#sourcesform input[name='title']"));
	if(isNotEmpty($("#sourcesform input[name='title']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#sourcesform input[name='id']").val(),
					title : $("#sourcesform input[name='title']").val(),
					action : 'addsource'
				},
				success : function(response){
					$("#sourcesform .lx-submit a").attr("class","");
					$("#sourcesform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadSources("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				}
			});
		}
	}
	else{
		$("#sourcesform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-source","click",function(){
	filterClicked = "yes";
	loadSources("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-source","click",function(){
	filterClicked = "yes";
	loadSources("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-source","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-source","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoresource'
		},
		success : function(response){
			loadSources("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-source","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletesourcepermanently'
		},
		success : function(response){
			loadSources("0");
		}
	});
});

function loadSources(state){
	if($(".lx-table-sources .lx-loading").length === 0){
		$(".lx-table-sources").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadsources'
		},
		success : function(response){
			$(".lx-table-sources .lx-loading").remove();
			$(".lx-table-sources").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$(".lx-new-client").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#clientsform input[name='codecl']").val("");
	$("#clientsform input[name='company']").val("");
	$("#clientsform .lx-advanced-select input[type='checkbox']").each(function(){
		$(this).prop("checked",false);
	});
	$("#clientsform input[name='ice']").val("");
	$("#clientsform input[name='iff']").val("");
	$("#clientsform input[name='fullname']").val("");
	$("#clientsform input[name='phone']").val("");
	$("#clientsform input[name='address']").val("");
	$("#clientsform input[name='email']").val("");
	$("#clientsform textarea[name='note']").val("");
	$("#clientsform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-client","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#clientsform input[name='codecl']").val($(this).attr("data-codecl"));
	$("#clientsform input[name='company']").attr("data-ids",$(this).attr("data-company"));
	$("#clientsform input[name='company']").val($(this).attr("data-companytxt"));
	var companies = $(this).attr("data-company").split(",");
	$("#clientsform .lx-advanced-select input[type='checkbox']").each(function(){
		for(var i=0;i<companies.length;i++){
			if($(this).attr("data-ids") === companies[i]){
				$(this).prop("checked",true);
				break;
			}
			else{
				$(this).prop("checked",false);
			}
		}
	});
	$("#clientsform input[name='ice']").val($(this).attr("data-ice"));
	$("#clientsform input[name='iff']").val($(this).attr("data-iff"));
	$("#clientsform input[name='fullname']").val($(this).attr("data-fullname"));
	$("#clientsform input[name='phone']").val($(this).attr("data-phone"));
	$("#clientsform input[name='address']").val($(this).attr("data-address"));
	$("#clientsform input[name='email']").val($(this).attr("data-email"));
	$("#clientsform textarea[name='note']").val($(this).attr("data-note"));
	$("#clientsform input[name='id']").val($(this).attr("data-id"));
});

$("#clientsform .lx-submit a").on("click",function(){
	isNotEmpty($("#clientsform input[name='company']"));
	isNotEmpty($("#clientsform input[name='fullname']"));
	if(isNotEmpty($("#clientsform input[name='fullname']"))
	&& isNotEmpty($("#clientsform input[name='company']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#clientsform input[name='id']").val(),
					codecl : $("#clientsform input[name='codecl']").val(),
					company : $("#clientsform input[name='company']").attr("data-ids"),
					ice : $("#clientsform input[name='ice']").val(),
					iff : $("#clientsform input[name='iff']").val(),
					fullname : $("#clientsform input[name='fullname']").val(),
					phone : $("#clientsform input[name='phone']").val(),
					address : $("#clientsform input[name='address']").val(),
					email : $("#clientsform input[name='email']").val(),
					note : $("#clientsform textarea[name='note']").val(),
					action : 'addclient'
				},
				success : function(response){
					$("#clientsform .lx-submit a").attr("class","");
					$("#clientsform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadClients("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
						reloadClientList();
					}
				}
			});
		}
	}
	else{
		$("#clientsform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

function reloadClientList(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'reloadclientlist'
		},
		success : function(response){
			$("#clientsform select[name='fullname']").html(response);
			toDropDownTargeted("#clientsform select[name='fullname']");
		}
	});
}

$("body").delegate(".lx-trash-client","click",function(){
	filterClicked = "yes";
	loadClients("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-client","click",function(){
	filterClicked = "yes";
	loadClients("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-client","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-client","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoreclient'
		},
		success : function(response){
			loadClients("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-client","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deleteclientpermanently'
		},
		success : function(response){
			loadClients("0");
		}
	});
});

function loadClients(state){
	if($(".lx-table-clients .lx-loading").length === 0){
		$(".lx-table-clients").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),	
			paid : $("#paid").attr("data-ids"),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadclients'
		},
		success : function(response){
			$(".lx-table-clients .lx-loading").remove();
			$(".lx-table-clients").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-edit-caisse","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");

	$("#caisseform input[name='id']").val($(this).attr("data-id"));
	$("#caisseform input[name='code1']").val($(this).attr("data-code"));
	
	$("#caisseform input[name='modepayment']").val($(this).attr("data-modepayment"));
	$(".lx-cheque").css("display","none");
	if($(this).attr("data-modepayment") === "Chèque" || $(this).attr("data-modepayment") === "Effet"){
		$(".lx-cheque").css("display","block");
	}

	$(".lx-remis").css("display","none");
	if($(this).attr("data-type") === "Entrée"){
		$(".lx-remis").css("display","block");
	}	
	$("#caisseform input[name='companytxt']").val($(this).attr("data-companytxt"));
	$("#caisseform select[name='company']").val($(this).attr("data-company")).trigger("change");
	var rib = $(this).attr("data-rib");
	window.setTimeout(function(){
		$("#caisseform select[name='rib']").val(rib);
	},100);
	$("#caisseform input[name='dateduecommand']").val($(this).attr("data-datedue"));
	$("#caisseform input[name='datepaidcommand']").val($(this).attr("data-datepaid"));
	$("#caisseform input[name='imputation']").val($(this).attr("data-imputation"));
	$("#caisseform input[name='remis']").prop("checked",$(this).attr("data-remis"));
	$("#caisseform input[name='dateremiscommand']").val($(this).attr("data-dateremis"));
	$("#caisseform input[name='nremise']").val($(this).attr("data-nremise"));
	$("#caisseform input[name='paid']").prop("checked",$(this).attr("data-paid"));
	$("#caisseform textarea[name='description']").val($(this).attr("data-description"));
	tinyMCE.get("description1").setContent($(this).attr("data-description"));
});

$(".lx-companies-list").on("change",function(){
	var id = $(this).val();
	var ajaxurl = "ajax.php";
	if($("#caisseform select[name='rib']").length){
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				id : id,
				action : 'loadbankaccountslist'
			},
			success : function(response){
				$(".lx-bankaccounts-list").html(response);
			}
		});
	}
	if($("#commandsform select[name='rib']").length){
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				id : id,
				action : 'loadbankaccountslist'
			},
			success : function(response){
				$(".lx-bankaccounts-list").html(response);
			}
		});
	}
});

$("#caisseform input[name='remis']").on("click",function(){
	if($(this).prop("checked") === true){
		$("#caisseform input[name='dateremiscommand']").val(getCurrentDate());	
	}
	else{
		$("#caisseform input[name='dateremiscommand']").val("");	
	}
});

function getCurrentDate() {
    const currentDate = new Date();

    const day = String(currentDate.getDate()).padStart(2, '0');
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const year = currentDate.getFullYear();

    const formattedDate = `${day}/${month}/${year}`;

    return formattedDate;
}

$("#caisseform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	var k = 0;
	var remis = 0;
	if($("#caisseform input[name='remis']").prop("checked") === true){
		remis = 1;
		if(!isNotEmpty($("#caisseform input[name='dateremiscommand']"))){
			k = 1;
		}
	}	
	if(k === 0){
		if($(this).attr("class") !== "lx-disabled"){
			var paid = 0;
			if($("#caisseform input[name='paid']").prop("checked") === true){
				paid = 1;
			}
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#caisseform input[name='id']").val(),
					datedue : $("#caisseform input[name='dateduecommand']").val(),
					datepaid : $("#caisseform input[name='datepaidcommand']").val(),
					rib : $("#caisseform select[name='rib']").val(),
					imputation : $("#caisseform input[name='imputation']").val(),
					note : $("#caisseform textarea[name='description']").val(),
					remis : remis,
					dateremis : $("#caisseform input[name='dateremiscommand']").val(),
					nremise : $("#caisseform input[name='nremise']").val(),
					paid : paid,
					page : $("#caisseform input[name='payments']").val(),
					action : 'addcaisse'
				},
				success : function(response){
					$("#caisseform .lx-submit a").attr("class","");
					$("#caisseform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadCaisse("1");
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);
					loadNotification();	
					loadPriceRange($("#caisseform input[name='page']").val());
				}
			});
		}
	}
	else{
		$("#caisseform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-caisse","click",function(){
	filterClicked = "yes";
	loadCaisse("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-caisse","click",function(){
	filterClicked = "yes";
	loadCaisse("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-caisse","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-caisse","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restorecaisse'
		},
		success : function(response){
			loadCaisse("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-caisse","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletecaissepermanently'
		},
		success : function(response){
			loadCaisse("0");
		}
	});
});

$("#paid,#remis").on("click",function(){
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
});

function loadCaisse(state){
	if($(".lx-table-caisse .lx-loading").length === 0){
		$(".lx-table-caisse").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			supplier : $("#supplier").attr("data-ids"),
			worker : $("#worker").attr("data-ids"),
			type : $("#type").attr("data-ids"),
			status : $("#status").attr("data-ids"),
			invoiced : $("#invoiced").attr("data-ids"),
			modepayment : $("#modepayment").val(),
			imputation : $("#imputation").attr("data-ids"),
			rib : $("#rib").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			dateduestart : $(".lx-keyword #dateduestart").val(),
			datedueend : $(".lx-keyword #datedueend").val(),	
			datepaidstart : $(".lx-keyword #datepaidstart").val(),
			datepaidend : $(".lx-keyword #datepaidend").val(),	
			dateremisstart : $(".lx-keyword #dateremisstart").val(),
			dateremisend : $(".lx-keyword #dateremisend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadcaisse'
		},
		success : function(response){
			$(".lx-table-caisse .lx-loading").remove();
			$(".lx-table-caisse").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-3").html($(".lx-caisse-total3").html());
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			document.addEventListener('DOMContentLoaded', function() {
				const infobuls = document.querySelectorAll('.infobul');
					infobuls.forEach(function(infobul) {
					infobul.removeAttribute('data-title');
				});
			});
			
			loadFamilleCaisse();
			
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadFamilleCaisse(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'loadfamillecaisse'
		},
		success : function(response){
			$("#caisseform select[name='nature']").html(response);
			toDropDownTargeted("#caisseform select[name='nature']");
		}
	});		
}

$(".lx-bulk-caisse").on("click",function(){
	var realcompany = 0;
	var company = 0;
	var prevcompany = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			if($(this).attr("data-company") !== prevcompany && prevcompany !== ""){
				company += 1;
			}
			prevcompany = $(this).attr("data-company");		
			realcompany = $(this).attr("data-company");
		}
	});
	
	var ids = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			ids += "," + $(this).val();
		}
	});
	ids = ids.substring(1);
	
	if(ids !== ""){
		if(company === 0){
			$(".lx-bulk-caisse-real").trigger("click");
			$("#caissebulkform select[name='company']").val(realcompany).trigger("change");
		}
		else{
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Vous ne pouvez pas modifier en masse des réglements pour déffirents sociétés en même temps !!<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);			
		}
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez selectionner des réglements !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}	
});

$(".lx-bulk-caisse-real").on("click",function(){
	$("#caissebulkform input[name='datepaidcommand']").val("");
	$("#caissebulkform select[name='imputation']").val("");
	$("#caissebulkform input[name='imputation']").val("");
	$("#caissebulkform select[name='rib']").val("");
});

$("#caissebulkform .lx-submit a").on("click",function(){
	var ids = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			ids += "," + $(this).val();
		}
	});
	ids = ids.substring(1);
	
	isNotEmpty($("#caissebulkform input[name='datepaidcommand']"));
	if(isNotEmpty($("#caissebulkform input[name='datepaidcommand']"))){
		if($(this).attr("class") !== "lx-disabled"){
			var k = 0;
			if(confirm('Vous être sur le point de changer plusieurs réglements en masse, voulez vous vraiment continue?')){
				k = 0;
			} 
			else{
				k = 1;
			}
			if(k === 0){
				$(this).attr("class","lx-disabled");
				if($(this).find("i").length === 0){
					$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
				}
				var ajaxurl = "ajax.php";
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						ids : ids,
						datepaid : $("#caissebulkform input[name='datepaidcommand']").val(),
						rib : $("#caissebulkform select[name='rib']").val(),
						action : 'editcaissebulk'
					},
					success : function(response){
						$("#caissebulkform .lx-submit a").attr("class","");
						$("#caissebulkform .lx-submit a i").remove();
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadCaisse("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
						loadNotification();
					}
				});
			}
		}
	}
	else{
		$("#caisseform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$(".lx-bulk-remise").on("click",function(){
	var type = 0;
	var mode = 0;
	var prevtype = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			if(($(this).attr("data-type") !== prevtype && prevtype !== "") || $(this).attr("data-type") !== "Entrée"){
				type += 1;
			}
			prevtype = $(this).attr("data-type");		
			if($(this).attr("data-mode") !== "Chèques"){
				mode += 1;
			}
		}
	});
	
	var ids = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			ids += "," + $(this).val();
		}
	});
	ids = ids.substring(1);
	
	if(ids !== ""){
		if(mode === 0 && type === 0){
			$(".lx-bulk-remise-real").trigger("click");
		}
		else{
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez sélectionner que des Chèques ou Effets reçus pour la remise en banque!!<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);			
		}
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez selectionner des réglements !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}	
});

$(".lx-bulk-remise-real").on("click",function(){
	$("#remisebulkform input[name='dateremiscommand']").val("");
	$("#remisebulkform input[name='nremise']").val("");
});

$("#remisebulkform .lx-submit a").on("click",function(){
	var ids = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			ids += "," + $(this).val();
		}
	});
	ids = ids.substring(1);
	
	isNotEmpty($("#remisebulkform input[name='dateremiscommand']"));
	if(isNotEmpty($("#remisebulkform input[name='dateremiscommand']"))){
		if($(this).attr("class") !== "lx-disabled"){
			var k = 0;
			if(confirm('Vous être sur le point de changer plusieurs réglements en masse, voulez vous vraiment continue?')){
				k = 0;
			} 
			else{
				k = 1;
			}
			if(k === 0){
				$(this).attr("class","lx-disabled");
				if($(this).find("i").length === 0){
					$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
				}
				var ajaxurl = "ajax.php";
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						ids : ids,
						dateremis : $("#remisebulkform input[name='dateremiscommand']").val(),
						nremise : $("#remisebulkform input[name='nremise']").val(),
						action : 'editremisebulk'
					},
					success : function(response){
						$("#remisebulkform .lx-submit a").attr("class","");
						$("#remisebulkform .lx-submit a i").remove();
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadCaisse("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
						loadNotification();
					}
				});
			}
		}
	}
	else{
		$("#remisebulkform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

function loadPriceRange(page){
	if($(".lx-price-range .lx-loading").length === 0){
		$(".lx-price-range").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			page : page,
			action : 'loadpricerange'
		},
		success : function(response){
			$(".lx-price-range.lx-loading").remove();
			$(".lx-price-range").html(response);
		}
	});
}

$("#expenseform select[name='type']").on("change",function(){
	$(".lx-client-expense").hide();
	$(".lx-supplier-expense").hide();
	if($(this).val() === "Entrée"){
		$(".lx-client-expense").show();
		$("#expenseform select[name='supplier']").val("");
		$("#expenseform input[name='supplier']").val("");
	}
	if($(this).val() === "Sortie"){
		$(".lx-supplier-expense").show();
		$("#expenseform select[name='client']").val("");
		$("#expenseform input[name='client']").val("");
	}
	$("#expenseform select[name='modepayment']").trigger("change");
});

$("input[name='countinprofit']").on("click",function(){
	if($(this).prop("checked") === true){
		$(".countinprofit").css("display","block");
	}
	else{
		$(".countinprofit").css("display","none");
	}
});

$("#expenseform select[name='invoiced']").on("change",function(){
	if($(this).val() === "Oui"){
		$(".countinprofit").css("display","block");
		$(".lx-invoiced-yes").css("display","block");	
		$("#expenseform input[name='tva']").val("");
	}
	else{
		$(".countinprofit").css("display","none");
		$(".lx-invoiced-yes").css("display","none");	
		$("#expenseform input[name='tva']").val("0");
	}		
});

var htmlclientform = $("#expenseform select[name='client']").clone().html();
var htmlsupplierform = $("#expenseform select[name='supplier']").clone().html();
var htmlcategoryorm = $("#expenseform select[name='nature']").clone().html();
$("#expenseform select[name='company']").on("change",function(){
	$("#expenseform select[name='client']").html(htmlclientform);
	var company = $(this).val();
	$("#expenseform select[name='client'] option[data-company]").each(function(){
		if(!$(this).attr("data-company").split(",").includes(company)){
			$(this).remove();
		}
	});
	toDropDownTargeted("#expenseform select[name='client']");
	
	$("#expenseform select[name='supplier']").html(htmlsupplierform);
	$("#expenseform select[name='supplier'] option[data-company]").each(function(){
		if(!$(this).attr("data-company").split(",").includes(company)){
			$(this).remove();
		}
	});
	toDropDownTargeted("#expenseform select[name='supplier']");
	
	$("#expenseform select[name='nature']").html(htmlcategoryorm);
	$("#expenseform select[name='nature'] option[data-company]").each(function(){
		if(!$(this).attr("data-company").split(",").includes(company)){
			$(this).remove();
		}
	});
	toDropDownTargeted("#expenseform select[name='nature']");
});			

$(".lx-new-expense").on("click",function(){
	tinyMCE.get("description").setContent('');
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#expenseform input[name='id']").val("0");
	$('input[name="dateaddcommand"]').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY'
		},
		singleDatePicker: true,
		showDropdowns: true
	});
	$("#expenseform input[name='dateaddcommand']").val(getCurrentDate()).prop("readonly",false);
	$("#expenseform input[name='dateduecommand']").val("").prop("readonly",false);
	$("#expenseform input[name='datepaidcommand']").val("").prop("readonly",false);
	$("#expenseform select[name='company']").val($("#expenseform select[name='company'] option:first").val()).prop("disabled",false).trigger("change");
	$("#expenseform select[name='rib']").val("0").prop("disabled",false);
	$("#expenseform input[name='imputation']").val("").prop("readonly",false);
	$("#expenseform select[name='client']").val("").prop("disabled",false);
	$("#expenseform input[name='client']").val("").prop("readonly",false);
	$("#expenseform select[name='supplier']").val("").prop("disabled",false);
	$("#expenseform input[name='supplier']").val("").prop("readonly",false);
	$("#expenseform input[name='title']").val("").prop("readonly",false);
	$("#expenseform input[name='price']").val("").prop("readonly",false);
	$("#expenseform select[name='modepayment']").val("").prop("disabled",false).trigger("change");			
	$("#expenseform select[name='invoiced']").val("").prop("disabled",false).trigger("change");
	$("#expenseform input[name='remis']").prop("checked",false);
	$("#expenseform input[name='dateremiscommand']").val("").prop("readonly",false);
	$("#expenseform input[name='nremise']").val("").prop("readonly",false);
	$("#expenseform input[name='paid']").prop("checked",false);
	$(".countinprofit").css("display","none");
	$("#expenseform textarea[name='description']").val("").prop("readonly",false);
	$("#expenseform select[name='tva']").val("").prop("disabled",false);
	$("#expenseform input[name='tva']").val("").prop("readonly",false);
	$("#expenseform select[name='expense']").val("").prop("disabled",false);
	$("#expenseform input[name='nature']").val("").prop("readonly",false);
	$("#expenseform select[name='type']").val("").prop("disabled",false).prop("disabled",false);
});

$("body").delegate(".lx-edit-expense","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	var readonly = false;
	var disabled = false;
	$('input[name="dateaddcommand"]').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY'
		},
		singleDatePicker: true,
		showDropdowns: true
	});
	$("#expenseform input[name='id']").val($(this).attr("data-id"));
	$("#expenseform input[name='dateaddcommand']").val($(this).attr("data-dateadd")).prop("readonly",readonly);
	$("#expenseform input[name='dateduecommand']").val($(this).attr("data-datedue"));
	$("#expenseform input[name='datepaidcommand']").val($(this).attr("data-datepaid"));
	$("#expenseform select[name='company']").val($(this).attr("data-company")).prop("disabled",disabled);
	$("#expenseform select[name='company']").trigger("change");
	var rib = $(this).attr("data-rib");
	window.setTimeout(function(){
		$("#expenseform select[name='rib']").val(rib);
	},1000);
	$("#expenseform input[name='imputation']").val($(this).attr("data-imputation"));
	$("#expenseform input[name='title']").val($(this).attr("data-titl")).prop("readonly",readonly);
	$("#expenseform input[name='price']").val($(this).attr("data-price")).prop("readonly",readonly);
	var type = $(this).attr("data-type");
	window.setTimeout(function(){
		$("#expenseform select[name='type']").val(type).trigger("change");
	},500);
	if($(this).attr("data-type") === "Entrée"){
		$(".lx-invoiced").show();
	}
	else{
		$(".lx-invoiced").hide();
	}
	var client = $(this).attr("data-client");
	var supplier = $(this).attr("data-supplier");
	var nature = $(this).attr("data-nature");
	window.setTimeout(function(){
		$("#expenseform select[name='client']").val(client).prop("disabled",disabled);
		toDropDownTargeted("#expenseform select[name='client']");
		
		$("#expenseform select[name='supplier']").val(supplier).prop("disabled",disabled);
		toDropDownTargeted("#expenseform select[name='supplier']");
		
		$("#expenseform select[name='nature']").val(nature).prop("disabled",disabled);
		toDropDownTargeted("#expenseform select[name='nature']");
	},1000);
	$("#expenseform select[name='invoiced']").val($(this).attr("data-invoiced")).prop("disabled",disabled).trigger("change");
	$("#expenseform select[name='modepayment']").val($(this).attr("data-modepayment")).prop("disabled",disabled).trigger("change");
	$("#expenseform input[name='remis']").prop("checked",$(this).attr("data-remis"));
	$("#expenseform input[name='dateremiscommand']").val($(this).attr("data-dateremis"));
	$("#expenseform input[name='nremise']").val($(this).attr("data-nremise"));
	$("#expenseform input[name='paid']").prop("checked",$(this).attr("data-paid"));
	$("#expenseform select[name='tva']").val($(this).attr("data-tva")+"%").prop("disabled",disabled);
	if($(this).attr("data-invoiced") === "Oui"){
		$(".countinprofit").css("display","block");
	}
	$("#expenseform textarea[name='description']").val($(this).attr("data-description"));
	tinyMCE.get("description").setContent($(this).attr("data-description"));
});

$("#expenseform select[name='modepayment']").on("change",function(){
	$(".lx-umpaid").css("display","none");
	$(".lx-remis").css("display","none");
	if($(this).val() === "Chèque" || $(this).val() === "Effet"){
		$(".lx-umpaid").css("display","block");
	}
	if(($(this).val() === "Chèque" || $(this).val() === "Effet") && $("#expenseform select[name='type']").val() === "Entrée"){
		$(".lx-remis").css("display","block");
	}
	else{
		$("#expenseform input[name='paid']").prop("checked",false);
		$("#expenseform input[name='remis']").prop("checked",false);
		$("#expenseform input[name='dateremiscommand']").val("");
		$("#expenseform input[name='nremise']").val("");		
	}
});

$("#expenseform input[name='remis']").on("click",function(){
	if($(this).prop("checked") === true){
		$("#expenseform input[name='dateremiscommand']").val(getCurrentDate());	
	}
	else{
		$("#expenseform input[name='dateremiscommand']").val("");	
	}
});

function getCurrentDate() {
    const currentDate = new Date();

    const day = String(currentDate.getDate()).padStart(2, '0');
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const year = currentDate.getFullYear();

    const formattedDate = `${day}/${month}/${year}`;

    return formattedDate;
}

$("#expenseform select[name='modepayment']").on("change",function(){
	//tinyMCE.get("description").setContent('');
});

$("#expenseform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	isNotEmpty($("#expenseform select[name='company']"));
	isNotEmpty($("#expenseform input[name='title']"));
	isNumber($("#expenseform input[name='price']"));
	isNotEmpty($("#expenseform select[name='type']"));
	var expenserequired = 0;
	if($("#expenseform input[name='page']").val() === "expense"){
		if(!isNotEmpty($("#expenseform select[name='expense']"))){
			expenserequired = 1;
		}
	}
	isNotEmpty($("#expenseform select[name='modepayment']"));
	isNotEmpty($("#expenseform select[name='invoiced']"));
	var k = 0;
	var remis = 0;
	if($("#expenseform input[name='remis']").prop("checked") === true){
		remis = 1;
		if(!isNotEmpty($("#expenseform input[name='dateremiscommand']"))){
			k = 1;
		}
	}
	if($("#expenseform select[name='invoiced']").val() === "Oui"){
		isNotEmpty($("#expenseform select[name='tva']"));
	}
	var clientok = 0;
	if($("#expenseform input[name='client']").val() !== ""){
		if(!isNotEmpty($("#expenseform input[name='client']"))){
			clientok = 1;
		}
	}	
	var supplierok = 0;
	if($("#expenseform input[name='supplier']").val() !== ""){
		if(!isNotEmpty($("#expenseform input[name='supplier']"))){
			supplierok = 1;
		}
	}	
	if(isNotEmpty($("#expenseform input[name='title']"))
	&& isNotEmpty($("#expenseform select[name='company']"))
	&& isNumber($("#expenseform input[name='price']"))
	&& isNotEmpty($("#expenseform select[name='type']"))
	&& expenserequired === 0
	&& clientok === 0
	&& supplierok === 0
	&& isNotEmpty($("#expenseform select[name='modepayment']"))
	&& isNotEmpty($("#expenseform select[name='invoiced']")) 
	&& (($("#expenseform select[name='invoiced']").val() === "Oui" && isNotEmpty($("#expenseform select[name='tva']"))) || $("#expenseform select[name='invoiced']").val() === "Non") && k === 0){
		if($(this).attr("class") !== "lx-disabled"){
			var k = 0;
			if($("#expenseform select[name='type']").val() === "Sortie" && $("#expenseform input[name='page']").val() === "expense"){
				if(parseFloat($(".lx-solde-now").attr("data-value")) - parseFloat($("#expenseform input[name='price']").val()) < 0){
					if(confirm('Votre solde expense est insuffisant, voulez vous vraiment continue?')){
						k = 0;
					} 
					else{
						k = 1;
					}
				}
			}
			if(k === 0){
				var paid = 0;
				if($("#expenseform input[name='paid']").prop("checked") === true){
					paid = 1;
				}
				var countinprofit = 0;
				if($("#expenseform input[name='countinprofit']").prop("checked") === true){
					countinprofit = 1;
				}
				$(this).attr("class","lx-disabled");
				if($(this).find("i").length === 0){
					$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
				}
				var ajaxurl = "ajax.php";
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						id : $("#expenseform input[name='id']").val(),
						dateadd : $("#expenseform input[name='dateaddcommand']").val(),
						datedue : $("#expenseform input[name='dateduecommand']").val(),
						datepaid : $("#expenseform input[name='datepaidcommand']").val(),
						company : $("#expenseform select[name='company']").val(),
						project : $("#expenseform select[name='project']").val(),
						rib : $("#expenseform select[name='rib']").val(),
						imputation : $("#expenseform input[name='imputation']").val(),
						client : $("#expenseform input[name='client']").val(),
						supplier : $("#expenseform input[name='supplier']").val(),
						title : $("#expenseform input[name='title']").val(),
						price : $("#expenseform input[name='price']").val(),
						modepayment : $("#expenseform select[name='modepayment']").val(),
						description : $("#expenseform textarea[name='description']").val(),
						nature : $("#expenseform input[name='nature']").val(),
						depot : $("#expenseform select[name='expense']").val(),
						type : $("#expenseform select[name='type']").val(),
						invoiced : $("#expenseform select[name='invoiced']").val(),
						remis : remis,
						dateremis : $("#expenseform input[name='dateremiscommand']").val(),
						nremise : $("#expenseform input[name='nremise']").val(),
						paid : paid,
						tva : $("#expenseform select[name='tva']").val(),
						page : $("#expenseform input[name='page']").val(),
						action : 'addexpense'
					},
					success : function(response){
						console.log(response);
						$("#expenseform .lx-submit a").attr("class","");
						$("#expenseform .lx-submit a i").remove();
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadCaisse("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
						loadNotification();	
						loadPriceRange($("#expenseform input[name='page']").val());
					}
				});
			}
		}
	}
	else{
		$("#expenseform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

function reloadClientSupplierList(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : $("#expenseform select[name='company']").val(),
			action : 'reloadclientsupplierlist'
		},
		success : function(response){
			$(".lx-clientsupplier").html(response);
			toDropDownTargeted("#expenseform select[name='client']");
			toDropDownTargeted("#expenseform select[name='supplier']");
		}
	});
}

$(".lx-new-product").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#productsform select[name='company']").val("");
	$("#productsform input[name='code']").val("");
	$("#productsform select[name='brand']").val("");
	$("#productsform input[name='brand']").val("");
	$("#productsform input[name='title']").val("");
	$("#productsform input[name='ref']").val("");
	$("#productsform input[name='family']").val("");
	$("#productsform select[name='family']").val("");
	toDropDownTargeted("#productsform select[name='family']");
	$("#productsform select[name='unit']").val("");
	$("#productsform input[name='bprice']").val("").prop("readonly",false);
	$("#productsform input[name='price']").val("");
	$("#productsform select[name='pricebase']").val("");
	$("#productsform select[name='tva']").val("");
	$("#productsform input[name='tva']").val("");
	$("#productsform select[name='bpricebase']").val("").prop("disabled",false);
	$("#productsform select[name='btva']").val("").prop("disabled",false);
	$("#productsform input[name='btva']").val("").attr("data-disabled","no").prop("readonly",false);
	$(".lx-reload-depots").html($(".lx-fresh-depots").html());
	$("#productsform input[name='placement']").val("").prop("readonly",false);
	$("#productsform select[name='invoiced']").val("").prop("disabled",false);
	$("#productsform input[name='qtymin']").val("");
	$("#productsform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-product","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#productsform select[name='company']").val($(this).attr("data-company"));
	$("#productsform input[name='code']").val($(this).attr("data-code"));
	$("#productsform select[name='brand']").val($(this).attr("data-brand"));
	$("#productsform input[name='brand']").val($(this).attr("data-brand"));
	$("#productsform input[name='title']").val($(this).attr("data-titl"));
	$("#productsform input[name='ref']").val($(this).attr("data-ref"));
	$("#productsform select[name='family']").val($(this).attr("data-family"));
	toDropDownTargeted("#productsform select[name='family']");
	$("#productsform select[name='unit']").val($(this).attr("data-unit"));
	$("#productsform input[name='bprice']").val($(this).attr("data-bprice")).prop("readonly",true);
	$("#productsform input[name='price']").val($(this).attr("data-price"));
	$("#productsform select[name='pricebase']").val($(this).attr("data-pricebase"));
	$("#productsform select[name='tva']").val($(this).attr("data-tva")+"%");
	$("#productsform input[name='tva']").val($(this).attr("data-tva"));
	$("#productsform select[name='bpricebase']").val($(this).attr("data-bpricebase")).prop("disabled",true);
	$("#productsform select[name='btva']").val($(this).attr("data-btva")+"%").prop("disabled",true);
	$("#productsform input[name='btva']").val($(this).attr("data-btva")).attr("data-disabled","yes").prop("readonly",true);
	$(".lx-reload-depots").html($(".lx-fresh-depots").html());
	$(".lx-depots input[name='placement']").val("");
	var depots = $(this).attr("data-depots").split(",");
	var placements = $(this).attr("data-placements").split(",");
	for(var i=0;i<depots.length;i++){
		$(".lx-depots input[name='placement'][data-id='"+depots[i]+"']").val(placements[i]);
	}
	$("#productsform select[name='invoiced']").val($(this).attr("data-invoiced")).prop("disabled",true);
	$("#productsform input[name='qtymin']").val($(this).attr("data-qtymin"));
	$("#productsform input[name='id']").val($(this).attr("data-id"));
});

$(".lx-move-product").on("click",function(){
	$("#moveproductsform select[name='depot']").val($("#moveproductsform select[name='depot'] option:first").val()).trigger("change");
	$("#moveproductsform select[name='depot2']").val($("#moveproductsform select[name='depot2'] option:first").val()).trigger("change");
	loadSimpleProductsList($("#moveproductsform select[name='depot']").val());
	$("#moveproductsform select[name='invoiced']").val($("#moveproductsform select[name='invoiced'] option:first").val()).prop("disabled",false);
	$("#moveproductsform input[name='qty']").val("");
});

$("#moveproductsform select[name='depot']").on("change",function(){
	loadSimpleProductsList($(this).val());
	$(".lx-restqty-move").text("");
});

function loadSimpleProductsList(depot){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			depot : depot,
			action : 'loadsimpleproductslist'
		},
		success : function(response){
			$("#moveproductsform select[name='product']").html(response);
			toDropDownTargeted("#moveproductsform select[name='product']");
			$("#moveproductsform select[name='product']").trigger("change");
		}
	});		
}

$("#moveproductsform select[name='product'],#moveproductsform select[name='invoiced']").on("change",function(){
	loadPricesProductsList($("#moveproductsform select[name='depot']").val(),$("#moveproductsform select[name='product']").val(),$("#moveproductsform select[name='invoiced']").val());
	$(".lx-restqty-move").text("");
});

$("#moveproductsform select[name='product']").on("change",function(){
	$("#moveproductsform input[name='qty']").val("");
	$("#moveproductsform select[name='depot2']").val("");
	$("#moveproductsform input[name='places']").val("").prop("readonly",false);
});

function loadPricesProductsList(depot,product,invoiced){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			depot : depot,
			product : product,
			invoiced : invoiced,
			action : 'loadpricesproductslist'
		},
		success : function(response){
			if(response !== ""){
				$(".lx-restqty-move").text("("+response+")");
			}
			else{
				$(".lx-restqty-move").text("");
			}
		}
	});		
}

$("#moveproductsform select[name='depot2']").on("change",function(){
	loadPlacementsDepot($(this).val(),$("#moveproductsform select[name='product']").val());
});

function loadPlacementsDepot(depot,product){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			depot : depot,
			product : product,
			action : 'loadplacementsdepot'
		},
		success : function(response){
			if(response !== ""){
				$("#moveproductsform input[name='places']").val(response).prop("readonly",true);
			}
			else{
				$("#moveproductsform input[name='places']").val("").prop("readonly",false);
			}
		}
	});		
}

$("#moveproductsform .lx-submit a").on("click",function(){
	isNotEmpty($("#moveproductsform select[name='depot']"));
	isNotEmpty($("#moveproductsform select[name='product']"));
	isNotEmpty($("#moveproductsform select[name='invoiced']"));
	isNotEmpty($("#moveproductsform select[name='prices']"));
	isNumber($("#moveproductsform input[name='qty']"));
	isNotEmpty($("#moveproductsform select[name='depot2']"));
	isNotEmpty($("#moveproductsform input[name='places']"));
	if(isNotEmpty($("#moveproductsform select[name='depot']"))
	&& isNotEmpty($("#moveproductsform select[name='product']"))
	&& isNotEmpty($("#moveproductsform select[name='invoiced']"))
	&& isNotEmpty($("#moveproductsform select[name='prices']"))
	&& isNumber($("#moveproductsform input[name='qty']"))
	&& isNotEmpty($("#moveproductsform select[name='depot2']"))
	&& isNotEmpty($("#moveproductsform input[name='places']"))){
		var k = 0;
		var same = 0;
		if(typeof $("#moveproductsform select[name='places'] option:selected").attr("data-same") !== typeof undefined && $("#moveproductsform select[name='places'] option:selected").attr("data-same") !== false){
			var same = $("#moveproductsform select[name='places'] option:selected").attr("data-same");
			if($("#moveproductsform select[name='places'] option:selected").attr("data-same") === "0"){
				if(confirm('Cette emplacement est déja affecter à un autre produit / prix d\'achat, voulez vous l\'écraser?')){
					k = 0;
				} 
				else{
					k = 1;
				}
			}
		}
		
		if(k === 0){
			if($(this).attr("class") !== "lx-disabled"){
				$(this).attr("class","lx-disabled");
				if($(this).find("i").length === 0){
					$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
				}
				var ajaxurl = "ajax.php";
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						id : $("#moveproductsform select[name='places'] option:selected").attr("data-id"),
						depot : $("#moveproductsform select[name='depot']").val(),
						product : $("#moveproductsform select[name='product']").val(),
						invoiced : $("#moveproductsform select[name='invoiced']").val(),
						prices : $("#moveproductsform select[name='prices'] option:selected").attr("data-price"),
						qty : $("#moveproductsform input[name='qty']").val(),
						depot2 : $("#moveproductsform select[name='depot2']").val(),
						place : $("#moveproductsform input[name='places']").val(),
						same : same,
						action : 'moveproduct'
					},
					success : function(response){
						$("#moveproductsform .lx-submit a").attr("class","");
						$("#moveproductsform .lx-submit a i").remove();
						if(response !== ""){
							$(".lx-floating-response").remove();
							window.clearTimeout(timer);
							$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
							$(".lx-floating-response").fadeIn();
							window.setTimeout(function(){
								$(".lx-floating-response").fadeOut();
							},5000);
						}
						else{
							$(".lx-popup-content > a > .material-icons").trigger("click");
							loadProducts("1");
							$(".lx-floating-response").remove();
							window.clearTimeout(timer);
							$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Produit déplacé avec succés<i class="material-icons"></i></p></div>');
							$(".lx-floating-response").fadeIn();
							window.setTimeout(function(){
								$(".lx-floating-response").fadeOut();
							},5000);
						}
					}
				});
			}
		}
	}
	else{
		$("#moveproductsform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("#productsform select[name='invoiced']").on("change",function(){
	if($(this).val() === "Non"){
		$(".invoicedyes").css("display","none");
		$("#productsform select[name='pricebase']").val("TTC");
		$("#productsform select[name='tva']").val("0%");
		$("#productsform input[name='tva']").val("0%");
		$("#productsform select[name='bpricebase']").val("TTC");
		$("#productsform select[name='btva']").val("0%");
		$("#productsform input[name='btva']").val("0%");
	}
	else{
		$(".invoicedyes").css("display","block");
	}
});

$("#productsform .lx-submit a").on("click",function(){
	var depots = "";
	var placements = "";
	$("#productsform input[name='depot']").each(function(){
		depots += ","+$(this).attr("data-id");
	});
	$("#productsform input[name='placement']").each(function(){
		placements += ","+$(this).val();
	});
	isNotEmpty($("#productsform select[name='company']"));
	isNotEmpty($("#productsform input[name='title']"));
	isNotEmpty($("#productsform input[name='ref']"));
	isNotEmpty($("#productsform select[name='family']"));
	isNotEmpty($("#productsform select[name='unit']"));
	// isNumber($("#productsform input[name='bprice']"));
	// isNotEmpty($("#productsform select[name='bpricebase']"));
	// isNotEmpty($("#productsform input[name='btva']"));
	isNumber($("#productsform input[name='price']"));
	isNotEmpty($("#productsform select[name='pricebase']"));
	isNotEmpty($("#productsform input[name='tva']"));
	isNumber($("#productsform input[name='qtymin']"));
	// isNotEmpty($("#productsform select[name='depot']"));
	// isNotEmpty($("#productsform select[name='invoiced']"));
	if(isNotEmpty($("#productsform input[name='title']"))
	&& isNotEmpty($("#productsform select[name='company']"))
	&& isNotEmpty($("#productsform input[name='ref']"))
	&& isNotEmpty($("#productsform select[name='family']"))
	&& isNotEmpty($("#productsform select[name='unit']"))
	// && isNumber($("#productsform input[name='bprice']"))
	// && isNotEmpty($("#productsform select[name='bpricebase']"))
	// && isNotEmpty($("#productsform input[name='btva']"))
	&& isNumber($("#productsform input[name='price']"))
	&& isNotEmpty($("#productsform select[name='pricebase']"))
	&& isNotEmpty($("#productsform input[name='tva']"))
	&& isNumber($("#productsform input[name='qtymin']"))
	// && isNotEmpty($("#productsform select[name='depot']"))
	// && isNotEmpty($("#productsform select[name='invoiced']"))
	){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#productsform input[name='id']").val(),
					company : $("#productsform select[name='company']").val(),
					code : $("#productsform input[name='code']").val(),
					brand : $("#productsform input[name='brand']").val(),
					title : $("#productsform input[name='title']").val(),
					ref : $("#productsform input[name='ref']").val(),
					family : $("#productsform select[name='family']").val(),
					unit : $("#productsform select[name='unit']").val(),
					bprice : $("#productsform input[name='bprice']").val(),
					price : $("#productsform input[name='price']").val(),
					pricebase : $("#productsform select[name='pricebase']").val(),
					tva : $("#productsform select[name='tva']").val(),
					bpricebase : $("#productsform select[name='bpricebase']").val(),
					btva : $("#productsform select[name='btva']").val(),
					depots : depots,
					placements : placements,
					invoiced : $("#productsform select[name='invoiced']").val(),
					qtymin : $("#productsform input[name='qtymin']").val(),
					depot : $("#productsform select[name='depot']").val(),
					action : 'addproduct'
				},
				success : function(response){
					$("#productsform .lx-submit a").attr("class","");
					$("#productsform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadProducts("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
						var exist = 0;
						$("#productsform select[name='brand'] option").each(function(){
							if($(this).text() === $("#productsform input[name='brand']").val()){
								exist = 1;
							}
						});
						if(exist === 0 && $("#productsform input[name='brand']").val() !== ""){
							$("#productsform select[name='brand']").append('<option value="'+$("#productsform input[name='brand']").val()+'">'+$("#productsform input[name='brand']").val()+'</option>');
						}
						toDropDown();
					}
				}
			});
		}
	}
	else{
		$("#productsform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-duplicate-product","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'duplicateproduct'
		},
		success : function(response){
			loadProducts("1");
		}
	});
});

$("body").delegate(".lx-trash-product","click",function(){
	filterClicked = "yes";
	loadProducts("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-product","click",function(){
	filterClicked = "yes";
	loadProducts("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-product","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-product","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoreproduct'
		},
		success : function(response){
			loadProducts("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-product","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deleteproductpermanently'
		},
		success : function(response){
			if(response !== ""){
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
			else{
				loadProducts("0");
			}
		}
	});
});

$("#qtymin").on("click",function(){
	if($(".lx-pagination ul").attr("data-table") === "products"){
		loadProducts($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "inventairedetails"){
		loadInventaireDetails($(".lx-pagination ul").attr("data-state"));
	}
});

function loadProducts(state){
	if($(".lx-table-products .lx-loading").length === 0){
		$(".lx-table-products").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),	
			company : $("#company").attr("data-ids"),	
			depot : $("#depot").attr("data-ids"),	
			supplier : $("#supplier").attr("data-ids"),	
			family : $("#family").attr("data-ids"),	
			brand : $("#brand").val(),	
			qtymin : ($("#qtymin").prop("checked") === true?"1":"0"),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadproducts'
		},
		success : function(response){
			$(".lx-table-products .lx-loading").remove();
			$(".lx-table-products").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-edit-price","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#priceouiform .lx-price-title").text($(this).attr("data-product"));
	$("#priceouiform .lx-price-qty").text($(this).attr("data-qty"));
	$("#priceouiform .lx-price-invoiced").text($(this).attr("data-invoiced"));
	$("#priceouiform .lx-price-placement").text($(this).attr("data-placement"));
	$("#priceouiform input[name='bprice']").val($(this).attr("data-price"));
	$("#priceouiform select[name='bpricebase']").val($(this).attr("data-bpricebase"));
	$("#priceouiform select[name='btva']").val($(this).attr("data-btva")+"%");
	$("#priceouiform input[name='btva']").val($(this).attr("data-btva"));
	$("#priceouiform input[name='id']").val($(this).attr("data-id"));
	$("#priceouiform input[name='idproduct']").val($(this).attr("data-idproduct"));
});

$("#priceouiform .lx-submit a").on("click",function(){
	isNumber($("#priceouiform input[name='bprice']"));
	isNotEmpty($("#priceouiform select[name='bpricebase']"));
	isNotEmpty($("#priceouiform input[name='btva']"));
	if(isNumber($("#priceouiform input[name='bprice']"))
	&& isNotEmpty($("#priceouiform select[name='bpricebase']"))
	&& isNotEmpty($("#priceouiform input[name='btva']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#priceouiform input[name='id']").val(),
					idproduct : $("#priceouiform input[name='idproduct']").val(),
					bprice : $("#priceouiform input[name='bprice']").val(),
					bpricebase : $("#priceouiform select[name='bpricebase']").val(),
					btva : $("#priceouiform select[name='btva']").val(),
					action : 'addprice'
				},
				success : function(response){
					$("#priceouiform .lx-submit a").attr("class","");
					$("#priceouiform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadProducts("1");
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);
				}
			});
		}
	}
	else{
		$("#priceouiform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-edit-price","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#pricenonform .lx-price-title").text($(this).attr("data-product"));
	$("#pricenonform .lx-price-qty").text($(this).attr("data-qty"));
	$("#pricenonform .lx-price-invoiced").text($(this).attr("data-invoiced"));
	$("#pricenonform .lx-price-placement").text($(this).attr("data-placement"));
	$("#pricenonform input[name='bprice']").val($(this).attr("data-price"));
	$("#pricenonform input[name='id']").val($(this).attr("data-id"));
	$("#pricenonform input[name='idproduct']").val($(this).attr("data-idproduct"));
});

$("#pricenonform .lx-submit a").on("click",function(){
	isNumber($("#pricenonform input[name='bprice']"));
	if(isNumber($("#pricenonform input[name='bprice']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#pricenonform input[name='id']").val(),
					idproduct : $("#pricenonform input[name='idproduct']").val(),
					bprice : $("#pricenonform input[name='bprice']").val(),
					action : 'addpricenon'
				},
				success : function(response){
					$("#pricenonform .lx-submit a").attr("class","");
					$("#pricenonform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadProducts("1");
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);
				}
			});
		}
	}
	else{
		$("#pricenonform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-edit-placement","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#placementform input[name='place']").val($(this).attr("data-placement"));
	$("#placementform select[name='place']").val($(this).attr("data-placement"));
	$("#placementform input[name='id']").val($(this).attr("data-id"));
});

$("#placementform .lx-submit a").on("click",function(){
	isNotEmpty($("#placementform input[name='place']"));
	if(isNotEmpty($("#placementform input[name='place']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#placementform input[name='id']").val(),
					placement : $("#placementform input[name='place']").val(),
					action : 'editplacement'
				},
				success : function(response){
					$("#placementform .lx-submit a").attr("class","");
					$("#placementform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadProducts("1");
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);
				}
			});
		}
	}
	else{
		$("#placementform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-upload-photos input","change",function(){
	if($(this).next("a").find(".fa-circle-notch").length === 0){
		$(this).next("a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	for(var i = 0;i < _($(this).attr("id")).files.length;i++){
		uploadPhotos(_($(this).attr("id")).files[i],$(this).attr("data-id"),$(this).attr("data-table"),$(this).attr("data-column"));
	}
});

$("body").delegate("#uploadphotos0","change",function(){
	if($(this).next("a").find(".fa-circle-notch").length === 0){
		$(this).next("a").prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	uploadPhotos(_($(this).attr("id")).files[0],$("#uploadphotos0").attr("data-id"),$("#uploadphotos0").attr("data-table"),$("#uploadphotos0").attr("data-column"));
});

function uploadPhotos(picture,id,table,column){
	var formdata = new FormData();
	formdata.append("file1", picture);
	formdata.append("id", id);
	formdata.append("table", table);
	formdata.append("column", column);
	var ajax = new XMLHttpRequest();
	ajax.addEventListener("load", completeHandler, false);
	ajax.open("POST", "file_upload_parser.php");
	ajax.send(formdata);
	function completeHandler(event){
		if (ajax.readyState === 4 && (ajax.status === 200 || ajax.status === 0)) {
			if($(".lx-pagination ul").attr("data-table") === "products"){
				loadProducts($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "services"){
				loadServices($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "companies"){
				loadCompanies($(".lx-pagination ul").attr("data-state"));
			}
		}
	}	
}

$("body").delegate(".lx-show-pictures","click",function(){
	$(this).parent().find(".lx-photos-preview").find("img").attr("src","uploads/"+$(this).attr("data-pic"));
	$(this).parent().find(".lx-photos-preview").find("a").attr("data-pic",$(this).attr("data-pic"));
});

$("body").delegate(".lx-delete-images","click",function(){
	$(this).next(".lx-delete-image-choice").toggle();
});

$("body").delegate(".lx-no-delete-image","click",function(){
	$(this).parent().toggle();
});

$("body").delegate(".lx-yes-delete-image","click",function(){
	var allpics = $(this).attr("data-allpics");
	var pic = $(this).attr("data-pic");
	var id = $(this).attr("data-id");
	var table = $(this).attr("data-table");
	var column = $(this).attr("data-column");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			allpics : allpics,
			pic : pic,
			id : id,
			table : table,
			column : column,
			action : 'deleteproductpicture'
		},
		success : function(response){
			if($(".lx-pagination ul").attr("data-table") === "products"){
				loadProducts($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "services"){
				loadServices($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "companies"){
				loadCompanies($(".lx-pagination ul").attr("data-state"));
			}
		}		
	});	
});

$(".lx-new-service").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#servicesform input[name='company']").val("");
	$("#servicesform .lx-advanced-select input[type='checkbox']").each(function(){
		$(this).prop("checked",false);
	});
	$("#servicesform input[name='title']").val("");
	$("#servicesform input[name='ref']").val("");
	$("#servicesform input[name='family']").val("");
	$("#servicesform select[name='family']").val("");
	toDropDownTargeted("#servicesform select[name='family']");
	$("#servicesform select[name='unit']").val("");
	$("#servicesform input[name='bprice']").val("").prop("readonly",false);
	$("#servicesform input[name='bprice']").parent().parent().parent().parent().css("display","block");
	$("#servicesform input[name='price']").val("");
	$("#servicesform select[name='pricebase']").val("");
	$("#servicesform select[name='tva']").val("");
	$("#servicesform input[name='tva']").val("");
	$("#servicesform input[name='btva']").val("").attr("data-disabled","no").prop("readonly",false);
	$("#servicesform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-service","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#servicesform input[name='company']").attr("data-ids",$(this).attr("data-company"));
	$("#servicesform input[name='company']").val($(this).attr("data-companytxt"));
	var companies = $(this).attr("data-company").split(",");
	$("#servicesform .lx-advanced-select input[type='checkbox']").each(function(){
		for(var i=0;i<companies.length;i++){
			if($(this).attr("data-ids") === companies[i]){
				$(this).prop("checked",true);
				break;
			}
			else{
				$(this).prop("checked",false);
			}
		}
	});
	$("#servicesform input[name='title']").val($(this).attr("data-titl"));
	$("#servicesform input[name='ref']").val($(this).attr("data-ref"));
	$("#servicesform select[name='family']").val($(this).attr("data-family"));
	toDropDownTargeted("#servicesform select[name='family']");
	$("#servicesform select[name='unit']").val($(this).attr("data-unit"));
	$("#servicesform input[name='bprice']").val($(this).attr("data-bprice")).prop("readonly",$(this).attr("data-readonly"));
	$("#servicesform input[name='bprice']").parent().parent().parent().parent().attr("style",$("#servicesform input[name='bprice']").parent().parent().parent().parent().attr("data-display"));
	$("#servicesform input[name='price']").val($(this).attr("data-price"));
	$("#servicesform select[name='pricebase']").val($(this).attr("data-pricebase"));
	$("#servicesform select[name='tva']").val($(this).attr("data-tva")+"%");
	$("#servicesform input[name='tva']").val($(this).attr("data-tva"));
	$("#servicesform input[name='btva']").val($(this).attr("data-btva")).attr("data-disabled","yes").prop("readonly",true);
	$("#servicesform input[name='id']").val($(this).attr("data-id"));
});

$("#servicesform .lx-submit a").on("click",function(){
	isNotEmpty($("#servicesform input[name='company']"));
	isNotEmpty($("#servicesform input[name='title']"));
	isNotEmpty($("#servicesform input[name='ref']"));
	isNotEmpty($("#servicesform select[name='family']"));
	isNotEmpty($("#servicesform select[name='unit']"));
	isNumber($("#servicesform input[name='bprice']"));
	isNumber($("#servicesform input[name='price']"));
	isNotEmpty($("#servicesform select[name='pricebase']"));
	isNotEmpty($("#servicesform select[name='tva']"));
	if(isNotEmpty($("#servicesform input[name='title']"))
	&& isNotEmpty($("#servicesform input[name='ref']"))
	&& isNotEmpty($("#servicesform input[name='company']"))
	&& isNotEmpty($("#servicesform select[name='family']"))
	&& isNotEmpty($("#servicesform select[name='unit']"))
	&& isNumber($("#servicesform input[name='bprice']"))
	&& isNumber($("#servicesform input[name='price']"))
	&& isNotEmpty($("#servicesform select[name='pricebase']"))
	&& isNotEmpty($("#servicesform select[name='tva']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#servicesform input[name='id']").val(),
					company : $("#servicesform input[name='company']").attr("data-ids"),
					title : $("#servicesform input[name='title']").val(),
					ref : $("#servicesform input[name='ref']").val(),
					family : $("#servicesform select[name='family']").val(),
					unit : $("#servicesform select[name='unit']").val(),
					qtymin : '0',
					bprice : $("#servicesform input[name='bprice']").val(),
					price : $("#servicesform input[name='price']").val(),
					pricebase : $("#servicesform select[name='pricebase']").val(),
					tva : $("#servicesform select[name='tva']").val(),
					bpricebase : 'HT',
					btva : $("#servicesform select[name='tva']").val(),
					action : 'addservice'
				},
				success : function(response){
					$("#servicesform .lx-submit a").attr("class","");
					$("#servicesform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadServices("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
						var exist = 0;
						$("#servicesform select[name='brand'] option").each(function(){
							if($(this).text() === $("#servicesform input[name='brand']").val()){
								exist = 1;
							}
						});
						if(exist === 0 && $("#servicesform input[name='brand']").val() !== ""){
							$("#servicesform select[name='brand']").append('<option value="'+$("#servicesform input[name='brand']").val()+'">'+$("#servicesform input[name='brand']").val()+'</option>');
						}
						toDropDown();
						reloadServiceList();
					}
				}
			});
		}
	}
	else{
		$("#servicesform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

function reloadServiceList(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'reloadservicelist'
		},
		success : function(response){
			$("#servicesform select[name='title']").html(response);
			toDropDownTargeted("#servicesform select[name='title']");
		}
	});
}

$("body").delegate(".lx-duplicate-service","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'duplicateservice'
		},
		success : function(response){
			loadServices("1");
		}
	});
});

$("body").delegate(".lx-trash-service","click",function(){
	filterClicked = "yes";
	loadServices("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-service","click",function(){
	filterClicked = "yes";
	loadServices("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-service","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-service","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoreservice'
		},
		success : function(response){
			loadServices("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-service","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deleteservicepermanently'
		},
		success : function(response){
			if(response !== ""){
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
			else{
				loadServices("0");
			}
		}
	});
});

$("#qtymin").on("click",function(){
	loadServices($(".lx-pagination ul").attr("data-state"));
});

function loadServices(state){
	if($(".lx-table-services .lx-loading").length === 0){
		$(".lx-table-services").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),	
			company : $("#company").attr("data-ids"),	
			family : $("#family").attr("data-ids"),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadservices'
		},
		success : function(response){
			$(".lx-table-services .lx-loading").remove();
			$(".lx-table-services").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$(".lx-new-unite").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#unitesform input[name='title']").val("");
	$("#unitesform input[name='id']").val("0");
});

$("body").delegate(".lx-edit-unite","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#unitesform input[name='title']").val($(this).attr("data-titl"));
	$("#unitesform input[name='id']").val($(this).attr("data-id"));
});

$("#unitesform .lx-submit a").on("click",function(){
	isNotEmpty($("#unitesform input[name='title']"));
	if(isNotEmpty($("#unitesform input[name='title']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#unitesform input[name='id']").val(),
					title : $("#unitesform input[name='title']").val(),
					type : $("#unitesform input[name='type']").val(),
					action : 'addunite'
				},
				success : function(response){
					$("#unitesform .lx-submit a").attr("class","");
					$("#unitesform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadUnites("1");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				}
			});
		}
	}
	else{
		$("#unitesform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-unite","click",function(){
	filterClicked = "yes";
	loadUnites("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-unite","click",function(){
	filterClicked = "yes";
	loadUnites("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-unite","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-unite","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restoreunite'
		},
		success : function(response){
			loadUnites("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-unite","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deleteunitepermanently'
		},
		success : function(response){
			loadUnites("0");
		}
	});
});

function loadUnites(state){
	if($(".lx-table-unites .lx-loading").length === 0){
		$(".lx-table-unites").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			type : $("#type").val(),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadunites'
		},
		success : function(response){
			$(".lx-table-unites .lx-loading").remove();
			$(".lx-table-unites").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$(".lx-new-familie").on("click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$("#familiesform select[name='company']").val("");
	$("#familiesform input[name='company']").val("");
	$("#familiesform .lx-advanced-select input[type='checkbox']").each(function(){
		$(this).prop("checked",false);
	});
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	reloadFamilies();
	$("#familiesform input[name='parent']").val("");
	$("#familiesform select[name='parent']").val("").parent().parent().css("display","block");
	$("#familiesform input[name='title']").val("");
	$("#familiesform input[name='id']").val("0");
});

function reloadFamilies(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			type : $("#type").val(),
			action : 'reloadfamilies'
		},
		success : function(response){
			$("#familiesform select[name='parent']").html(response);
			toDropDown();
		}
	});	
}

$("body").delegate(".lx-edit-familie","click",function(){
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#familiesform input[name='company']").attr("data-ids",$(this).attr("data-company"));
	$("#familiesform input[name='company']").val($(this).attr("data-companytxt"));
	var companies = $(this).attr("data-company").split(",");
	$("#familiesform .lx-advanced-select input[type='checkbox']").each(function(){
		for(var i=0;i<companies.length;i++){
			if($(this).attr("data-ids") === companies[i]){
				$(this).prop("checked",true);
				break;
			}
			else{
				$(this).prop("checked",false);
			}
		}
	});
	if($(this).attr("data-parent") === "0"){
		$("#familiesform select[name='parent']").val($(this).attr("data-parent")).parent().parent().css("display","none");
	}
	else{
		$("#familiesform select[name='parent']").val($(this).attr("data-parent")).parent().parent().css("display","block");
	}
	toDropDownTargeted("#familiesform select[name='parent']");
	$("#familiesform input[name='title']").val($(this).attr("data-titl"));
	$("#familiesform input[name='id']").val($(this).attr("data-id"));
});

$("#familiesform .lx-submit a").on("click",function(){
	isNotEmpty($("#familiesform input[name='company']"));
	isNotEmpty($("#familiesform input[name='title']"));
	if(isNotEmpty($("#familiesform input[name='title']"))
	&& isNotEmpty($("#familiesform input[name='company']"))){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#familiesform input[name='id']").val(),
					parent : $("#familiesform select[name='parent']").val(),
					title : $("#familiesform input[name='title']").val(),
					company : $("#familiesform input[name='company']").attr("data-ids"),
					type : $("#familiesform input[name='type']").val(),
					action : 'addfamilie'
				},
				success : function(response){
					$("#familiesform .lx-submit a").attr("class","");
					$("#familiesform .lx-submit a i").remove();
					if(response !== ""){
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
					else{
						$(".lx-popup-content > a > .material-icons").trigger("click");
						loadFamilies("1");
						reloadFamilies();
						toDropDownTargeted("#familiesform select[name='parent']");
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				}
			});
		}
	}
	else{
		$("#familiesform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);
	}
});

$("body").delegate(".lx-trash-familie","click",function(){
	filterClicked = "yes";
	loadFamilies("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-familie","click",function(){
	filterClicked = "yes";
	loadFamilies("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-familie","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-familie","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restorefamilie'
		},
		success : function(response){
			loadFamilies("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-familie","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletefamiliepermanently'
		},
		success : function(response){
			loadFamilies("0");
			reloadFamilies();
		}
	});
});

function loadFamilies(state){
	if($(".lx-table-families .lx-loading").length === 0){
		$(".lx-table-families").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			type : $("#type").val(),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadfamilies'
		},
		success : function(response){
			$(".lx-table-families .lx-loading").remove();
			$(".lx-table-families").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

var htmtproductcommandform = $("#commandsform select[name='product']").clone().html();
$("#commandsform select[name='depot']").on("change",function(){
	$("#commandsform select[name='product']").html(htmtproductcommandform);
	var depot = $(this).val();
	$("#commandsform select[name='product'] option[data-depot]").each(function(){
		if(!$(this).attr("data-depot").split(",").includes(depot)){
			$(this).remove();
		}
	});
	loadProductsList($("#commandsform input[name='id']").val(),$("#commandsform input[name='allproducts']").val(),$("#commandsform select[name='depot']").val(),$("#commandsform select[name='company']").val());
	$("#commandsform label del").text("");
});

$("#commandsform select[name='modepayment']").on("change",function(){
	if($(this).val() === "Espèce"){
		$(".lx-command-caisse").css("display","block");
	}
	else{
		$(".lx-command-caisse").css("display","none");
		$("#commandsform select[name='caisse']").val("");
	}
});

var transform = 0;
$(".lx-new-command").on("click",function(){
	transform = 0;
	loadProductsList($("#commandsform input[name='id']").val(),$("#commandsform input[name='allproducts']").val(),$("#commandsform select[name='depot']").val(),$("#commandsform select[name='company']").val());
	$("#commandsform input[name='table']").val($(this).attr("data-table"));
	$("#commandsform select,#commandsform input[type='text']").removeAttr("style");
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$("#commandsform select[name='transform']").html($(".lx-select-facture").html());
	$(".lx-create-avoir").css("display","block");
	$(".lx-uncreate-avoir").css("display","none");
	$("input[name='qtyback']").val("");
	$(".lx-qty-back").css("display","none");
	$(".lx-transform").css("display","none");
	$("#commandsform input[name='id']").val("0");
	$("#commandsform select[name='company']").val($("#commandsform select[name='company'] option:first").val()).prop("disabled",false).trigger("change");
	$("#commandsform input[name='new']").prop("checked",false);
	$("#commandsform input[name='exist']").val("");
	$("#commandsform input[name='client']").val("");
	$("#commandsform select[name='client']").val("");
	toDropDownTargeted("#commandsform select[name='client']");
	$("#commandsform input[name='ice']").val("");
	$("#commandsform input[name='phone']").val("");
	$("#commandsform input[name='address']").val("");
	$("#commandsform input[name='email']").val("");
	$("#commandsform input[name='product']").val("");
	$("#commandsform select[name='product']").val("0");
	$("#commandsform input[name='qty']").val("1");
	$("#commandsform input[name='uprice']").val("");
	$("#commandsform select[name='pricebase']").val("HT");
	$("#commandsform select[name='utva']").val("");
	$(".lx-list-products table tr.lx-otherrow").remove();
	$(".lx-list-products table tr.lx-secondrow").css("display","table-row");
	$(".lx-error-products").html("");
	$(".lx-error-command").html("");
	$(".lx-cash").css("display","block");
	$(".lx-client").show();
	$("#commandsform select[name='typediscount']").val("").prop("disabled",false).trigger("change");
	$("#commandsform select[name='typediscount']").parent().parent().css("display","block");
	$("#commandsform input[name='maindiscount']").val("").prop("readonly",false).attr("data-click","false").trigger("click");
	$("#commandsform input[name='maindiscount']").parent().parent().css("display","block");
	$("#commandsform input[name='price']").val("0");
	$("#commandsform input[name='tva']").val("0");
	$("#commandsform input[name='ttcprice']").val("0");
	$("#commandsform select[name='modepayment']").val("").trigger("change");
	$("#commandsform input[name='modepayment']").val("");
	$("#commandsform input[name='cash']").val("0");
	$("#commandsform input[name='rest']").val("0");
	$("#commandsform select[name='imputation']").val("");
	$("#commandsform input[name='imputation']").val("");
	$("#commandsform input[name='allproducts']").val("");
	$("#commandsform input[name='allqtys']").val("");
	$("#commandsform input[name='allprices']").val("");
	$("#commandsform input[name='dateaddcommand']").val(getCurrentDate());
	$("#commandsform textarea[name='note']").val("");
	if($("#correctdoc").length){
		$("#correctdoc").val("");
	}
	tinyMCE.get("note").setContent('');
	$(".lx-qty-back").css("display","none");
	$(".lx-delete-cell").css("display","table-cell");
	$("#commandsform select[name='transform']").val($("#commandsform select[name='transform'] option:first").val()).trigger("change");		
	$("#commandsform input[name='discount']").val("").prop("readonly",false);
});

$("body").delegate(".lx-edit-command","click",function(){
	transform = 1;
	//loadProductsList($(this).attr("data-id"),$(this).attr("data-allproducts"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	$("#commandsform input[name='id']").val($(this).attr("data-id"));
	if($(this).attr("data-id") === "-1"){
		$("#commandsform select[name='transform']").html($(".lx-select-avoir").html());
		$(".lx-create-avoir").css("display","none");
		$(".lx-uncreate-avoir").css("display","block");
	}
	else{
		$("#commandsform select[name='transform']").html($(".lx-select-facture").html());
		$(".lx-create-avoir").css("display","block");
		$(".lx-uncreate-avoir").css("display","none");
		$("input[name='qtyback']").val("");
	}
	if($(this).attr("data-id") === "0" || $(this).attr("data-id") === "-1"){
		$("#commandsform input[name='dateaddcommand']").val(getCurrentDate());
		$(".lx-transform").css("display","block");
	}
	else{
		$("#commandsform input[name='dateaddcommand']").val($(this).attr("data-dateadd"));
		$(".lx-transform").css("display","none");
	}
	$("#commandsform input[name='table']").val($(this).attr("data-table"));
	$("#commandsform select,#commandsform input[type='text']").removeAttr("style");
	$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
	$("#commandsform select[name='company']").val($(this).attr("data-company"));
	$("#commandsform select[name='company']").trigger("change");
	var rib = $(this).attr("data-rib");
	window.setTimeout(function(){
		$("#commandsform select[name='rib']").val(rib);
	},1000);
	$("#commandsform input[name='new'][value='"+$(this).attr("data-exist")+"']").prop("checked",true);
	$("#commandsform input[name='exist']").val($(this).attr("data-exist"));
	$("#commandsform input[name='ice']").val($(this).attr("data-ice"));
	$("#commandsform input[name='phone']").val($(this).attr("data-phone"));
	$("#commandsform input[name='address']").val($(this).attr("data-address"));
	$("#commandsform input[name='email']").val($(this).attr("data-email"));
	$("#commandsform input[name='product']").val("");
	$("#commandsform select[name='product']").val("0");
	$("#commandsform input[name='qty']").val("1");
	$("#commandsform input[name='uprice']").val("");
	$(".lx-list-products").html($(this).attr("data-products"));
	$(".lx-list-products tr.lx-otherrow").each(function(){
		$(this).attr("data-title",$(this).find("td:eq(0)").text());
	});
	$(".lx-error-products").html("");
	$(".lx-error-command").html("");
	$(".lx-cash").hide();
	if($(this).attr("data-exist") === "0"){
		$(".lx-client").show();
	}
	else if($(this).attr("data-exist") === "1"){
		$(".lx-client").show();
	}
	else{
		$(".lx-client").hide();
	}
	$("#commandsform select[name='typediscount']").val($(this).attr("data-discounttype"));
	if($(this).attr("data-discounttype") !== ""){
		$(".lx-discount").css("display","table-cell");
		$(".maindiscount").css("display","block");
		if($("#commandsform select[name='typediscount']").val() === "DH"){
			var discount = 0;
			$(".lx-list-products table tr").each(function(){
				if($(this).find("input[name='discount']").val() !== ""){
					discount += parseFloat($(this).find("input[name='discount']").val());
				}
			});
			$("#commandsform input[name='maindiscount']").val(discount.toFixed(2));	
		}
		else{
			var discount = 0;
			$(".lx-list-products table tr").each(function(){
				if($(this).find("input[name='discount']").val() !== ""){
					discount = parseFloat($(this).find("input[name='discount']").val());
				}
			});
			$("#commandsform input[name='maindiscount']").val(discount.toFixed(2));				
		}
	}
	$(".lx-list-products table tr td input[name='discount']").trigger("click");
	$("#commandsform select[name='modepayment']").val($(this).attr("data-modepayment")).trigger("change");
	$("#commandsform input[name='modepayment']").val($(this).attr("data-modepayment"));
	$("#commandsform input[name='cash']").val("0");
	$("#commandsform input[name='rest']").val("0");
	$("#commandsform input[name='imputation']").val($(this).attr("data-imputation"));
	$("#commandsform select[name='imputation']").val($(this).attr("data-imputation"));
	$("#commandsform textarea[name='note']").val($(this).attr("data-note"));
	tinyMCE.get("note").setContent($(this).attr("data-note"));
	$("#commandsform input[name='code']").val($(this).attr("data-code"));
	$("#correctdoc").val($(this).attr("data-correctdoc"));
	
	var client = $(this).attr("data-client");
	window.setTimeout(function(){
		$("#commandsform select[name='client']").val(client);
		toDropDownTargeted("#commandsform select[name='client']");
		addClientToOrder();		
	},1000);
	
	if($(this).attr("data-id") === "-1"){
		$(".lx-qty-back").css("display","table-cell");
		$("#commandsform select[name='typediscount']").prop("disabled",true);
		$("#commandsform input[name='discount']").prop("readonly",true);
		$("#commandsform input[name='maindiscount']").prop("readonly",true);
		if($(this).attr("data-discounttype") === ""){
			$("#commandsform select[name='typediscount']").parent().parent().css("display","none");
			$("#commandsform input[name='maindiscount']").parent().parent().css("display","none");
		}
		else{
			$("#commandsform select[name='typediscount']").parent().parent().css("display","block");
			$("#commandsform input[name='maindiscount']").parent().parent().css("display","block");			
		}
		$(".lx-delete-cell").css("display","none");
	}
	else{
		$("input[name='qtyback']").val("");
		$(".lx-qty-back").css("display","none");
		$("#commandsform select[name='typediscount']").prop("disabled",false);
		$("#commandsform input[name='discount']").prop("readonly",false);
		$("#commandsform input[name='maindiscount']").prop("readonly",false);
		$("#commandsform select[name='typediscount']").parent().parent().css("display","block");
		$("#commandsform input[name='maindiscount']").parent().parent().css("display","block");
		$(".lx-delete-cell").css("display","table-cell");
	}
	$("input[name='qtyback']").each(function(){
		$(this).attr("max",$(this).parent().parent().parent().parent().attr("data-qty"));
	});
	$("#commandsform select[name='transform']").val($("#commandsform select[name='transform'] option:first").val()).trigger("change");	
	$(".discounttype").text($(this).attr("data-discounttype"));
	calculateProfit();
	$("#commandsform input[name='modepayment']").val($(this).attr("data-modepayment"));
	$("#commandsform input[name='conditions']").val($(this).attr("data-conditions"));
	$("#commandsform input[name='abovetable']").val($(this).attr("data-abovetable"));
});

$(".lx-popup-content").delegate("input[name='qtyback']","keyup", function() {
	var maxValue = parseFloat($(this).attr('max'));
	var enteredValue = parseFloat($(this).val());

	if (enteredValue > maxValue) {
		$(this).val(maxValue); // Set the value to the maximum allowed
	}
});

$("#commandsform select[name='transform']").on("change",function(){
	transform = 1;
	$("#commandsform input[name='type']").val($(this).val());
	$("#commandsform input[name='prefix']").val($(this).find("option:selected").attr("data-prefix"));
	$("#commandsform select[name='company']").trigger("change");

	if($(this).find("option:selected").attr("data-prefix") === "BRC"){
		$("#commandsform input[name='code']").css("padding-left","82px");
	}
	else{
		$("#commandsform input[name='code']").css("padding-left","72px");
	}
	
	if($(this).parent().parent().css("display") === "none"){
		$(".lx-code-label").css("top","30px");
	}
	else{
		$(".lx-code-label").css("top","30px");
	}
});

function loadProductsList(commandid,products,depot,company){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			commandid : commandid,
			products : products,
			depot : depot,
			company : company,
			action : 'loadproductslist'
		},
		success : function(response){
			$("#commandsform select[name='product']").html(response);
			htmtproductcommandform = $("#commandsform select[name='product']").clone().html();
			toDropDownTargeted("#commandsform select[name='product']");
			adjustQtys();
			// $("#commandsform select[name='depot']").trigger("change");
		}
	});		
}

function loadClientsList(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'loadclientslist'
		},
		success : function(response){
			$("#commandsform select[name='client']").html(response);
		}
	});		
}

$("body").delegate(".lx-open-edit-menu","click",function(){
	$(".lx-edit-menu").hide();
	$(this).next(".lx-edit-menu").show();
});

$("body").delegate(".lx-edit-menu a","click",function(){
	$(".lx-edit-menu").hide();
});

$(".lx-popup-content").delegate(".lx-open-edit-menu","click",function(){
	$(".lx-edit-menu").hide();
	$(this).next(".lx-edit-menu").show();
});

$(".lx-popup-content").delegate(".lx-edit-menu a","click",function(){
	$(".lx-edit-menu").hide();
});

$("body").delegate(".lx-edit-payment","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	tinyMCE.get("note1").setContent('');
	$("#paymentform select[name='modepayment']").val("").trigger("change");
	$("#paymentform input[name='cash']").val("").removeAttr("style");
	$("#paymentform input[name='cash']").next("ins").remove();
	$("#paymentform .lx-table").html($(this).attr("data-table"));
	$("#paymentform input[name='resthidden']").val($(this).attr("data-rest"));
	$("#paymentform input[name='price']").val($(this).attr("data-price"));
	$("#paymentform input[name='rest']").val(parseFloat($(this).attr("data-rest")).toFixed(2)+" DH");
	$("#paymentform input[name='paid']").val($(this).attr("data-paid"));
	if($(this).attr("data-paid") === "Partiellement payée"){
		$("#paymentform input[name='cash']").prop("readonly",false);
	}
	else{
		$("#paymentform input[name='cash']").prop("readonly",true);
	}
	$(".lx-payment-state").each(function(){
		$(this).find("i").remove();
		$(this).text($(this).text().trim());
	});	
	$(".lx-payment-company").text($(this).attr("data-company"));
	$("#paymentform input[name='imputation']").val("");
	$("#paymentform select[name='imputation']").val("");
	$("#paymentform select[name='rib']").html($(this).attr("data-bankaccounts"));
	$("#paymentform input[name='id']").val($(this).attr("data-id"));
});

$(".lx-popup-content").delegate("#paymentform input[name='cash']","keyup blur paste change",function(){
	$("#paymentform input[name='rest']").val(parseFloat($("#paymentform input[name='resthidden']").val() - $("#paymentform input[name='cash']").val()).toFixed(2) + " DH");
});

$(".lx-payment-state").on("click",function(){
	$("#paymentform input[name='paid']").val($(this).attr("data-paid"));
	$(".lx-payment-state i").remove();
	$(this).prepend('<i class="fa fa-check"></i> ');
	if($(this).attr("data-paid") === "Payée" || $(this).attr("data-paid") === "Remboursée"){
		$("#paymentform input[name='cash']").val($("#paymentform input[name='resthidden']").val()).prop("readonly",true);
		$("#paymentform input[name='rest']").val("0.00 DH");
	}
	else if($(this).attr("data-paid") === "Non payée" || $(this).attr("data-paid") === "Non remboursée"){
		$("#paymentform input[name='cash']").val("0").prop("readonly",true);
		$("#paymentform input[name='rest']").val(parseFloat($("#paymentform input[name='price']").val()).toFixed(2) + " DH");
	}
	else{
		$("#paymentform input[name='cash']").val("0").prop("readonly",false);
		$("#paymentform input[name='rest']").val(parseFloat($("#paymentform input[name='resthidden']").val()).toFixed(2) + " DH");
		$("#paymentform input[name='cash']").trigger("keyup");
	}
});

$("#paymentform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	var k = 0;
	
	if(k === 0){
		isNotEmpty($("#paymentform select[name='modepayment']"));
		isNotZero($("#paymentform input[name='cash']"));
		if(isNotEmpty($("#paymentform select[name='modepayment']"))
		&& isNotZero($("#paymentform input[name='cash']"))){		
			if(parseFloat($("#paymentform input[name='rest']").val()) >= 0){
				if($(this).attr("class") !== "lx-disabled"){
					$(this).attr("class","lx-disabled");
					if(parseFloat($("#paymentform input[name='rest']").val()) === 0){
						$("#paymentform input[name='paid']").val("Payée");
					}
					if($(this).find("i").length === 0){
						$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
					}
					var ajaxurl = "ajax.php";
					$.ajax({
						url : ajaxurl,
						type : 'post',
						data : {
							id : $("#paymentform input[name='id']").val(),
							paid : $("#paymentform input[name='paid']").val(),
							cash : $("#paymentform input[name='cash']").val(),
							rest : $("#paymentform input[name='rest']").val(),
							modepayment : $("#paymentform select[name='modepayment']").val(),
							datedue : $("#paymentform input[name='dateduecommand']").val(),
							datepaid : $("#paymentform input[name='datepaidcommand']").val(),
							imputation : $("#paymentform input[name='imputation']").val(),
							rib : $("#paymentform select[name='rib']").val(),
							note : $("#paymentform textarea[name='note1']").val(),
							type : $("#paymentform input[name='type']").val(),
							category : $("#paymentform input[name='category']").val(),
							action : 'addpayment'
						},
						success : function(response){
							$("#paymentform input[name='cash']").val("").removeAttr("style");
							$("#paymentform input[name='cash']").next("ins").remove();
							$("#paymentform .lx-submit a").attr("class","");
							$("#paymentform .lx-submit a i").remove();
							$(".lx-popup-content > a > .material-icons").trigger("click");
							if($(".lx-pagination ul").attr("data-table") === "users"){
								loadUsers($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "suppliers"){
								loadSuppliers($(".lx-pagination ul").attr("data-state"));
							}	
							if($(".lx-pagination ul").attr("data-table") === "clients"){
								loadClients($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "devis"){
								loadDevis($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
								loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "bl"){
								loadBL($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "bs"){
								loadBS($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "br"){
								loadBR($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "factures"){
								loadFactures($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "avoirs"){
								loadAvoirs($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "bc"){
								loadBC($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "bre"){
								loadBRC($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "caisse"){
								loadCaisse($(".lx-pagination ul").attr("data-state"));
							}
							if($(".lx-pagination ul").attr("data-table") === "companies"){
								loadCompanies($(".lx-pagination ul").attr("data-state"));
							}
							$(".lx-floating-response").remove();
							window.clearTimeout(timer);
							$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
							$(".lx-floating-response").fadeIn();
							window.setTimeout(function(){
								$(".lx-floating-response").fadeOut();
							},5000);
							loadNotification();
						}
					});	
				}					
			}
			else{
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Le reste ne peut pas être négatif !!<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);		
			}
		}
		else{
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remilis les champs en rouge !!<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);		
		}
	}
});

$("#paymentform select[name='modepayment']").on("change",function(){
	tinyMCE.get("note1").setContent('');
	$(".lx-cheque").css("display","none");
	if($(this).val() == "Chèque" || $(this).val() == "Effet"){
		$(".lx-cheque").css("display","block");
		$("#paymentform input[name='dateduecommand']").val("");
		$("#paymentform input[name='datepaidcommand']").val("");
		if($(this).val() == "Chèque"){
			$("#paymentform input[name='dateduecommand']").val(getCurrentDate());
		}
	}
});

$(".lx-popup-content").delegate(".lx-delete-payment","click",function(){
	$(this).next(".lx-delete-payment-choice").slideToggle();
});

$(".lx-popup-content").delegate(".lx-no-delete-payment","click",function(){
	$(this).parent().slideToggle();
});

$(".lx-popup-content").delegate(".lx-yes-delete-payment","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletepaymenthistory'
		},
		success : function(response){
			$(".lx-popup-content > a > .material-icons").trigger("click");
			if($(".lx-pagination ul").attr("data-table") === "factures"){
				loadFactures($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "avoirs"){
				loadAvoirs($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bc"){
				loadBC($(".lx-pagination ul").attr("data-state"));
			}
			loadNotification();
		}
	});
});

$(".lx-popup-content").delegate("#commandsform input[name='new']","click",function(){
	$("#commandsform input[name='exist']").val($(this).val()).trigger("keyup");
	$("#commandsform input[name='client']").val("").removeAttr("style");
	$("#commandsform select[name='client']").val("").next("ins").remove();
	$("#commandsform input[name='ice']").val("").removeAttr("style").next("ins").remove();
	$("#commandsform input[name='phone']").val("");
	$("#commandsform input[name='address']").val("").removeAttr("style").next("ins").remove();
	$("#commandsform input[name='email']").val("");
	isNotEmpty($("#commandsform input[name='exist']"));
	if($(this).val() === "0"){
		$("#commandsform input[name='client']").attr("placeholder","Saisissez un client");
		$(".lx-autocomplete-advanced").remove();
		$(".lx-client").show();
	}
	else if($(this).val() === "1"){
		$(".lx-client").show();
		toDropDownTargeted($("#commandsform select[name='client']"));
	}
	else{
		$(".lx-autocomplete-advanced").remove();
		$(".lx-client").hide();
	}
});

$(".lx-refresh-codes").on("click",function(){
	if($("#commandsform select[name='company']").val() !== ""){
		var ajaxurl = "ajax.php";
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				company : $("#commandsform select[name='company']").val(),
				type : $("#commandsform input[name='type']").val(),
				category : $("#commandsform input[name='categories']").val(),
				action : 'loadcode'
			},
			success : function(response){
				$("#commandsform input[name='code']").val(response);
			}
		});		
	}
	else{
		$("#commandsform input[name='code']").val("");
	}
});

$("#commandsform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	var k = 0;
	
	if(k === 0){
		var products = "";
		var units = "";
		var qtys = "";
		var uprices = "";
		var tprices = "";
		var discounts = "";
		var tvas = "";
		var qtyback = "";
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			products += ","+$(this).attr("data-title");
		});
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			units += ","+$(this).attr("data-unit");
		});
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			qtys += ","+$(this).attr("data-qty");
		});
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			uprices += ","+$(this).attr("data-uprice");
		});
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			tprices += ","+$(this).attr("data-ttprice");
		});
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			discounts += ","+$(this).find("input[name='discount']").val();
		});
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			tvas += ","+$(this).attr("data-tva");
		});
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			qtyback += ","+$(this).find("input[name='qtyback']").val();
		});
		
		if($("#commandsform input[name='cash']").val() !== "" && $("#commandsform input[name='cash']").val() !== "0"){
			isNotEmpty($("#commandsform select[name='caisse']"));
		}
		isNotEmpty($("#commandsform input[name='exist']"));
		var client = "";
		if($("#commandsform input[name='exist']").val() === "0"){
			$("#commandsform input[name='client']").attr("data-isnotempty","");
			$("#commandsform input[name='client']").attr("data-message","Saisissez un client");
			if(!isNotEmpty($("#commandsform input[name='client']"))){
				client = "1";
			}
			isNotEmpty($("#commandsform input[name='ice']"));
			isNotEmpty($("#commandsform input[name='address']"));
			if(!isNotEmpty($("#commandsform input[name='ice']"))
				|| !isNotEmpty($("#commandsform input[name='address']"))){
				client = "1";
			}
		}
		if($("#commandsform input[name='exist']").val() === "1"){
			$("#commandsform input[name='client']").attr("data-isnotempty","");
			$("#commandsform input[name='client']").attr("data-message","Choisissez un client");
			if(!isNotEmpty($("#commandsform select[name='client']"))){
				$("#commandsform input[name='client']").css("border","1px solid rgb(214, 50, 50)");
				client = "1";
			}
			else{
				$("#commandsform input[name='client']").removeAttr("style");
			}
			isNotEmpty($("#commandsform input[name='ice']"));
			isNotEmpty($("#commandsform input[name='address']"));
			if(!isNotEmpty($("#commandsform input[name='ice']"))
				|| !isNotEmpty($("#commandsform input[name='address']"))){
				client = "1";
			}
		}

		var v = 0;
		if($("#commandsform input[name='cash']").val() !== "" && $("#commandsform input[name='cash']").val() !== "0"){
			if(!isNotEmpty($("#commandsform select[name='modepayment']"))){
				v++;
			}
		}
		else{
			$("#commandsform select[name='modepayment']").removeAttr("style");
			$("#commandsform select[name='modepayment']").parent().find("ins").remove();
		}

		var l = 0;
		if($("#commandsform input[name='type']").val() === "avoir" || $("#commandsform input[name='type']").val() === "br"){
			if(!isNotEmpty($("#commandsform input[name='correctdoc']"))){
				l++;
			}
		}
		
		var q = 0;
		if($(".lx-qty-back").css("display") !== "none"){
			if(!isNumber($("#commandsform input[name='qtyback']"))){
				q++;
			}
		}
		
		isNotEmpty($("#commandsform select[name='company']"));
		isNumber($("#commandsform input[name='code']"));
		if(isNotEmpty($("#commandsform select[name='company']"))
			&& isNumber($("#commandsform input[name='code']"))
			&& isNotEmpty($("#commandsform input[name='exist']"))
			&& v === 0
			&& l === 0
			&& q === 0
			&& products !== "" 
			&& client === ""){
			if(parseFloat($("#commandsform input[name='price']").val()) >= 0){
				if($(this).attr("class") !== "lx-disabled"){
					$(this).attr("class","lx-disabled");
					if($(this).find("i").length === 0){
						$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
					}
					var fullname = $("#commandsform input[name='client']").val();
					if(typeof $("#commandsform select[name='client'] option:selected").attr("data-name") !== typeof undefined && $("#commandsform select[name='client'] option:selected").attr("data-name") !== false){
						fullname = $("#commandsform select[name='client'] option:selected").attr("data-name");
					}					
					var ajaxurl = "ajax.php";
					$.ajax({
						url : ajaxurl,
						type : 'post',
						data : {
							id : $("#commandsform input[name='id']").val(),
							type : $("#commandsform input[name='type']").val(),
							category : $("#commandsform input[name='category']").val(),
							prefix : $("#commandsform input[name='prefix']").val(),
							code : $("#commandsform input[name='code']").val(),
							dateadd : $("#commandsform input[name='dateaddcommand']").val(),
							company : $("#commandsform select[name='company']").val(),
							correctdoc : ($("#correctdoc").length?$("#correctdoc").val():""),
							exist : $("#commandsform input[name='exist']").val(),
							fullname : fullname,
							client : $("#commandsform select[name='client']").val(),
							ice : $("#commandsform input[name='ice']").val(),
							phone : $("#commandsform input[name='phone']").val(),
							address : $("#commandsform input[name='address']").val(),
							email : $("#commandsform input[name='email']").val(),
							products : products,
							units : units,
							qtys : qtys,
							uprices : uprices,
							tprices : tprices,
							tvas : tvas,
							discounts : discounts,
							qtyback : qtyback,
							discounttype : $("#commandsform select[name='typediscount']").val(),
							maindiscount : $("#commandsform input[name='maindiscount']").val(),
							modepayment : $("#commandsform select[name='modepayment']").val(),
							cash : $("#commandsform input[name='cash']").val(),
							rest : $("#commandsform input[name='rest']").val(),
							rib : $("#commandsform select[name='rib']").val(),
							imputation : $("#commandsform input[name='imputation']").val(),
							note : $("#commandsform textarea[name='note']").val(),
							modepayment : $("#commandsform input[name='modepayment']").val(),
							conditions : $("#commandsform input[name='conditions']").val(),
							abovetable : $("#commandsform input[name='abovetable']").val(),
							action : 'addcommand'
						},
						success : function(response){
							if(response !== ""){
								$("#commandsform .lx-submit a").attr("class","");
								$("#commandsform .lx-submit a i").remove();
								$(".lx-floating-response").remove();
								window.clearTimeout(timer);
								$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
								$(".lx-floating-response").fadeIn();
								window.setTimeout(function(){
									$(".lx-floating-response").fadeOut();
								},5000);							
							}
							else{
								$("#commandsform .lx-submit a").attr("class","");
								$("#commandsform .lx-submit a i").remove();
								$(".lx-popup-content > a > .material-icons").trigger("click");
								if($("#commandsform input[name='type']").val() === "facture"){
									window.location.href = "factures.php";
								}
								else if($("#commandsform input[name='type']").val() === "devis"){
									window.location.href = "devis.php";
								}
								else if($("#commandsform input[name='type']").val() === "avoir"){
									window.location.href = "avoirs.php";
								}
								else if($("#commandsform input[name='type']").val() === "br"){
									window.location.href = "br.php";
								}
								else if($("#commandsform input[name='type']").val() === "factureproforma"){
									window.location.href = "facturesproforma.php";
								}
								else if($("#commandsform input[name='type']").val() === "bl"){
									window.location.href = "bl.php";
								}
								else if($("#commandsform input[name='type']").val() === "bs"){
									window.location.href = "bs.php";
								}
								else if($("#commandsform input[name='type']").val() === "bc"){
									window.location.href = "bc.php";
								}
								else if($("#commandsform input[name='type']").val() === "bre"){
									window.location.href = "bre.php";
								}
								$(".lx-floating-response").remove();
								window.clearTimeout(timer);
								$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
								$(".lx-floating-response").fadeIn();
								window.setTimeout(function(){
									$(".lx-floating-response").fadeOut();
								},5000);
							}
						}
					});
				}
			}
			else{
				$("#commandsform .lx-submit a i").remove();
				$(".lx-floating-response").remove();
				window.clearTimeout(timer);
				$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Le montant total doit être supérieur ou égale à 0 !!<i class="material-icons"></i></p></div>');
				$(".lx-floating-response").fadeIn();
				window.setTimeout(function(){
					$(".lx-floating-response").fadeOut();
				},5000);				
			}
		}
		else if(products === ""){
			$(".lx-error-products").html("<p style='padding:10px;background:#FDAFBF;font-style:italic;text-align:center;border:1px solid #FF0000;'>Veuillez ajouter au moins un produit / service !!</p>");
		}
		else{
			$("#commandsform .lx-submit a i").remove();
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
		}
	}
});

$("#commandsform select[name='client']").on("change",function(){
	addClientToOrder();	
});

$("body").delegate(".lx-check-delivery-state","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'checkdeliverystate'
		},
		success : function(response){
			if(response === "1"){
				$(".lx-return-command[data-id='"+id+"']").addClass("lx-open-popup");
				$(".lx-return-command[data-id='"+id+"']").attr("data-state","Livrée");
				$(".lx-return-command[data-id='"+id+"']").attr("data-title","returncommand");
				$(".lx-return-command[data-id='"+id+"']").trigger("click");
			}
			else{
				$(".lx-return-command[data-id='"+id+"']").removeClass("lx-open-popup");
				$(".lx-return-command[data-id='"+id+"']").attr("data-state","Nouvelle");
				$(".lx-return-command[data-id='"+id+"']").attr("data-title","");
				$(".lx-return-command[data-id='"+id+"']").trigger("click");
			}
		}
	});
});

$("body").delegate(".lx-return-command","click",function(){
	if($(this).attr("data-state") === "Livrée"){
		$("."+$(this).attr("data-title")+" .lx-form-title h3 span").text($(this).attr("data-header"));
		$(".lx-list-products-return").html($(this).attr("data-products"));
		var i = 0;
		$(".lx-list-products-return .lx-table table").each(function(){
			if($(this).find("tr").length === 1){
				$(this).prev("h2").remove();
				$(this).remove();
			}
			else{
				i = 1;
			}
		});
		if(i === 1){
			$("#returncommandsform .lx-submit a").css("display","inline-block")
		}
		else{
			$(".lx-list-products-return").html('<p><center><i>Facture retournée en totalité</i></center></p>');
			$("#returncommandsform .lx-submit a").css("display","none")		
		}
		$("#returncommandsform input[name='id']").val($(this).attr("data-id"));
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		if($(".lx-pagination ul").attr("data-table") === "commands"){
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Vous ne pouvez pas retourné une commande non livrée !!<i class="material-icons"></i></p></div>');
		}
		if($(".lx-pagination ul").attr("data-table") === "factures"){
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Vous ne pouvez pas faire un bon de retour sur une facture qui contient des commandes non livrées !!<i class="material-icons"></i></p></div>');
		}
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
});

$("#returncommandsform .lx-submit a").on("click",function(){
	var allcommands = "";
	var allproducts = "";
	var alldepots = "";
	var allqtys = "";
	var alldiscounts = "";
	var allbprices = "";
	var allprices = "";
	var errorqty = 0;
	$(".lx-list-products-return table tr[data-product]").each(function(){
		if($(this).find("input[name='discount']").val() !== "" && $(this).find("input[name='discount']").val() !== "0"){
			if(parseFloat($(this).find("input[name='discount']").val()) <= parseFloat($(this).attr("data-qty"))){
				$(this).find("input[name='discount']").removeAttr("style");
				allcommands += ","+$(this).attr("data-command");
				allproducts += ","+$(this).attr("data-product");
				alldepots += ","+$(this).attr("data-depot");
				allqtys += ",-"+$(this).find("input[name='discount']").val();
				alldiscounts += ","+(parseFloat($(this).attr("data-discount"))*$(this).find("input[name='discount']").val());
				allbprices += ","+$(this).attr("data-bprice");
				allprices += ","+$(this).attr("data-price");
			}
			else{
				$(this).find("input[name='discount']").css("border","1px solid #FF0000");
				errorqty = 1;
			}
		}
	});
			
	$("#returncommandsform input[name='allcommands']").val(allcommands);
	$("#returncommandsform input[name='allproducts']").val(allproducts);
	$("#returncommandsform input[name='alldepots']").val(alldepots);
	$("#returncommandsform input[name='allqtys']").val(allqtys);
	$("#returncommandsform input[name='alldiscounts']").val(alldiscounts);
	$("#returncommandsform input[name='allbprices']").val(allbprices);
	$("#returncommandsform input[name='allprices']").val(allprices);

	if(errorqty === 0){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#returncommandsform input[name='id']").val(),
					allcommands : $("#returncommandsform input[name='allcommands']").val(),
					allproducts : $("#returncommandsform input[name='allproducts']").val(),
					alldepots : $("#returncommandsform input[name='alldepots']").val(),
					allqtys : $("#returncommandsform input[name='allqtys']").val(),
					alldiscounts : $("#returncommandsform input[name='alldiscounts']").val(),
					allbprices : $("#returncommandsform input[name='allbprices']").val(),
					allprices : $("#returncommandsform input[name='allprices']").val(),
					action : 'returncommand'
				},
				success : function(response){
					$("#returncommandsform .lx-submit a").attr("class","");
					$("#returncommandsform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadFactures("1");
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);
					loadNotification();
				}
			});
		}
	}
	else{
		$("#returncommandsform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez verifier les quanitité !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
});

$("#returnnoncommandsform .lx-submit a").on("click",function(){
	var allcommands = "";
	var allproducts = "";
	var alldepots = "";
	var allqtys = "";
	var alldiscounts = "";
	var allbprices = "";
	var allprices = "";
	var errorqty = 0;
	$(".lx-list-products-return table tr[data-product]").each(function(){
		if($(this).find("input[name='discount']").val() !== "" && $(this).find("input[name='discount']").val() !== "0"){
			if(parseFloat($(this).find("input[name='discount']").val()) <= parseFloat($(this).attr("data-qty"))){
				$(this).find("input[name='discount']").removeAttr("style");
				allcommands += ","+$(this).attr("data-command");
				allproducts += ","+$(this).attr("data-product");
				alldepots += ","+$(this).attr("data-depot");
				allqtys += ",-"+$(this).find("input[name='discount']").val();
				alldiscounts += ","+(parseFloat($(this).attr("data-discount"))*$(this).find("input[name='discount']").val());
				allbprices += ","+$(this).attr("data-bprice");
				allprices += ","+$(this).attr("data-price");
			}
			else{
				$(this).find("input[name='discount']").css("border","1px solid #FF0000");
				errorqty = 1;
			}
		}
	});
			
	$("#returnnoncommandsform input[name='allcommands']").val(allcommands);
	$("#returnnoncommandsform input[name='allproducts']").val(allproducts);
	$("#returnnoncommandsform input[name='alldepots']").val(alldepots);
	$("#returnnoncommandsform input[name='allqtys']").val(allqtys);
	$("#returnnoncommandsform input[name='alldiscounts']").val(alldiscounts);
	$("#returnnoncommandsform input[name='allbprices']").val(allbprices);
	$("#returnnoncommandsform input[name='allprices']").val(allprices);

	if(errorqty === 0){
		if($(this).attr("class") !== "lx-disabled"){
			$(this).attr("class","lx-disabled");
			if($(this).find("i").length === 0){
				$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
			}
			var ajaxurl = "ajax.php";
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					id : $("#returnnoncommandsform input[name='id']").val(),
					allcommands : $("#returnnoncommandsform input[name='allcommands']").val(),
					allproducts : $("#returnnoncommandsform input[name='allproducts']").val(),
					alldepots : $("#returnnoncommandsform input[name='alldepots']").val(),
					allqtys : $("#returnnoncommandsform input[name='allqtys']").val(),
					alldiscounts : $("#returnnoncommandsform input[name='alldiscounts']").val(),
					allbprices : $("#returnnoncommandsform input[name='allbprices']").val(),
					allprices : $("#returnnoncommandsform input[name='allprices']").val(),
					action : 'returnnoncommand'
				},
				success : function(response){
					$("#returnnoncommandsform .lx-submit a").attr("class","");
					$("#returnnoncommandsform .lx-submit a i").remove();
					$(".lx-popup-content > a > .material-icons").trigger("click");
					loadCommands("1");
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);
					loadNotification();
				}
			});
		}
	}
	else{
		$("#returnnoncommandsform .lx-submit a i").remove();
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez verifier les quanitité !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
});

$(".lx-coli-state-delivarymen").on("click",function(){
	$("#editstateform input[name='state']").val($(this).attr("data-state"));
	$(".lx-coli-state-delivarymen i").remove();
	$(this).prepend('<i class="fa fa-check"></i> ');
});

$("body").delegate(".lx-edit-state","click",function(){
	$("#editstateform input[name='state']").val($(this).attr("data-state"));
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	var state = $(this).attr("data-state");
	$(".lx-coli-state-delivarymen").each(function(){
		$(this).find("i").remove();
		$(this).text($(this).text().trim());
		if($(this).text() === state){
			$(this).prepend('<i class="fa fa-check"></i> ');
		}
	});
	$("#editstateform textarea[name='note']").val($(this).attr("data-note"));
	tinyMCE.get("note2").setContent($(this).attr("data-note"));
	$("#editstateform input[name='invoiced']").val($(this).attr("data-invoiced"));
	$("#editstateform input[name='maybe']").val($(this).attr("data-maybe"));
	$("#editstateform input[name='id']").val($(this).attr("data-id"));
});

$("#editstateform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	if($("#editstateform input[name='invoiced']").val() === "Peut-être" && $("#editstateform input[name='state']").val() === "Retourné"){
		alert("Ces quantités vont être ajoutés aux stocks dont l'état de facturation est: "+$("#editstateform input[name='maybe']").val());	
	}
	if($(this).find("i").length === 0){
		$(this).prepend('<i class="fa fa-circle-notch fa-spin"></i> ');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : $("#editstateform input[name='id']").val(),
			state : $("#editstateform input[name='state']").val(),
			note : $("#editstateform textarea[name='note']").val(),
			action : 'editstate'
		},
		success : function(response){
			console.log(response);
			$("#editstateform .lx-submit a i").remove();
			$(".lx-popup-content > a > .material-icons").trigger("click");
			loadCommands("1");
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien enregistré<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
		}
	});
});

$("body").delegate(".lx-trash-command","click",function(){
	filterClicked = "yes";
	loadCommands("0");
	$(".lx-pagination ul").attr("data-state","0");
});

$("body").delegate(".lx-published-command","click",function(){
	filterClicked = "yes";
	loadCommands("1");
	$(".lx-pagination ul").attr("data-state","1");
});

$("body").delegate(".lx-delete-command","click",function(){
	$(".updatecaisse input[name='price']").val($(this).attr("data-price"));
	$(".updatecaisse input[name='command']").val($(this).attr("data-id"));
	$(".updatecaisse input[name='type']").val($(this).attr("data-type"));
	$(".updatecaisse input[name='company']").val($(this).attr("data-company"));
	$(".updatecaisse input[name='project']").val($(this).attr("data-project"));
	$(".updatecaisse input[name='libelle']").val($(this).attr("data-libelle"));
	$(".updatecaisse input[name='nature']").val($(this).attr("data-nature"));
	$(".updatecaisse input[name='invoiced']").val($(this).attr("data-invoiced"));
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-delete-devis","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-delete-facturesproforma","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("body").delegate(".lx-restore-command","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'restorecommand'
		},
		success : function(response){
			loadCommands("0");
		}
	});
});

$("body").delegate(".lx-delete-permanently-command","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'deletecommandpermanently'
		},
		success : function(response){
			loadCommands("0");
		}
	});
});

$("input[name='searchadvanced']").on("keyup",function(){
	var val = $(this).val();
	var valeur = new RegExp(val,'i');
	$(this).next("ul").find("li").each(function(){
		if(valeur.test($(this).text())){
			$(this).css("display","block");
		}
		else{
			$(this).css("display","none");
		}
	});
});

$(".lx-popup-content").delegate("input[name='searchadvanced']","keyup",function(){
	var val = $(this).val();
	var valeur = new RegExp(val,'i');
	$(this).next("ul").find("li").each(function(){
		if(valeur.test($(this).text())){
			$(this).css("display","block");
		}
		else{
			$(this).css("display","none");
		}
	});
});

$(".lx-advanced-select > input").on("click",function(){
	$(".lx-advanced-select div").not($(this).next("div")).css({"display":"none"});
	$(this).next("div").toggle();
});

$(".lx-advanced-select > div").on("click",function(){
	var el = $(this);
	window.setTimeout(function(){
		el.css("display","block");
	},1);
});

$(".lx-advanced-select > div label").on("click",function(){
	var val = "";
	var ids = "";
	$(this).parents(".lx-advanced-select").find("input[type='checkbox']").not("input[name='selectall']").each(function(){
		if($(this).prop("checked") === true){
			val += "," + $(this).val();
			ids += "," + $(this).attr("data-ids");
		}
	});
	$(this).parents(".lx-advanced-select").find("> input[type='text']").val(val.substring(1)).trigger("keyup");
	$(this).parents(".lx-advanced-select").find("> input[type='text']").attr("data-ids",ids.substring(1));
});

$(".lx-popup-content").delegate(".lx-advanced-select > div label","click",function(){
	var val = "";
	$(this).parents(".lx-advanced-select").find("input[type='checkbox']").not("input[name='selectall']").each(function(){
		if($(this).prop("checked") === true){
			val += "," + $(this).val();
		}
	});
	$(this).parents(".lx-advanced-select").find("> input[type='text']").val(val.substring(1)).trigger("keyup");
});

$(".lx-popup-content").delegate(".lx-single-select div ul li","click",function(){
	$(this).parents(".lx-advanced-select").find("> input[type='text']").val($(this).text()).trigger("keyup");
	$(this).parent().parent().removeAttr("style");
});

$(".lx-advanced-select > div label.lx-selectall").on("click",function(){
	var val = "";
	$(this).parents(".lx-advanced-select").find("input[type='checkbox']").not("input[name='selectall']").each(function(){
		$(this).prop("checked",$(".lx-advanced-select > div label.lx-selectall input").prop("checked"));
		if($(this).prop("checked") === true){
			val += "," + $(this).val();
		}
	});
	$(this).parents(".lx-advanced-select").find("> input[type='text']").val(val.substring(1)).trigger("keyup");
});

$(".lx-state-filter").on("click",function(){
	if($("#kpi").length){
		loadKPI();
	}
	if($("#documents").length){
		loadKPI1();
	}
	if($("#ca").length){
		loadCA();
	}
	if($("#topca").length){
		loadTop();
	}
	if($(".lx-pagination ul").attr("data-table") === "users"){
		loadUsers($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "suppliers"){
		loadSuppliers($(".lx-pagination ul").attr("data-state"));
	}	
	if($(".lx-pagination ul").attr("data-table") === "clients"){
		loadClients($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "devis"){
		loadDevis($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
		loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bl"){
		loadBL($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bs"){
		loadBS($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "br"){
		loadBR($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "factures"){
		loadFactures($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "avoirs"){
		loadAvoirs($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bc"){
		loadBC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bre"){
		loadBRC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "companies"){
		loadCompanies($(".lx-pagination ul").attr("data-state"));
	}
});

$(".lx-state-empty").on("click",function(){
	$(this).parents(".lx-advanced-select").find("input[type='checkbox']").each(function(){
		$(this).prop("checked",false);
	});
	$(this).parents(".lx-advanced-select").find("> input[type='text']").val("");
	$(this).parents(".lx-advanced-select").find("> input[type='text']").attr("data-ids","");
	if($("#kpi").length){
		loadKPI();
	}
	if($("#documents").length){
		loadKPI1();
	}
	if($("#ca").length){
		loadCA();
	}
	if($("#topca").length){
		loadTop();
	}
	if($(".lx-pagination ul").attr("data-table") === "users"){
		loadUsers($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "suppliers"){
		loadSuppliers($(".lx-pagination ul").attr("data-state"));
	}	
	if($(".lx-pagination ul").attr("data-table") === "clients"){
		loadClients($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "devis"){
		loadDevis($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
		loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bl"){
		loadBL($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bs"){
		loadBS($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "br"){
		loadBR($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "factures"){
		loadFactures($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "avoirs"){
		loadAvoirs($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bc"){
		loadBC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bre"){
		loadBRC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "companies"){
		loadCompanies($(".lx-pagination ul").attr("data-state"));
	}
});

function loadCommands(state,neworder){
	if($(".lx-table-commands .lx-loading").length === 0){
		$(".lx-table-commands").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			project : $("#project").attr("data-ids"),
			worker : $("#worker").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			family : $("#family").attr("data-ids"),
			source : $("#source").val(),
			statee : $("#states").val(),
			invoiced : $("#invoiced").attr("data-ids"),
			paid : $("#paid").val(),
			modepayment : $("#modepayment").val(),
			avoir : $("#avoir").val(),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadcommands'
		},
		success : function(response){
			$(".lx-table-commands .lx-loading").remove();
			$(".lx-table-commands").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");				
			if(neworder === "1"){
				$(".lx-table-commands table tr:eq(1) td input[type='checkbox']").prop("checked",true);
				window.setTimeout(function(){
					if($(".lx-table-commands table tr:eq(1) td input[type='checkbox']").prop("checked") === true){
						$(".lx-generate-invoice").trigger("click");
					}					
				},500);
			}
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-edit-extrainfo","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	tinyMCE.get("modepayment").setContent($(this).attr("data-modepayment"));
	tinyMCE.get("conditions").setContent($(this).attr("data-conditions"));
	tinyMCE.get("abovetable").setContent($(this).attr("data-abovetable"));
	$("#extrainfoform input[name='id']").val($(this).attr("data-id"));
});

$("#extrainfoform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : $("#extrainfoform input[name='id']").val(),
			modepayment : $("#extrainfoform textarea[name='modepayment']").val(),
			conditions : $("#extrainfoform textarea[name='conditions']").val(),
			abovetable : $("#extrainfoform textarea[name='abovetable']").val(),
			action : 'editextrainfo'
		},
		success : function(response){
			$("#extrainfoform .lx-submit a i").remove();
			$(".lx-popup-content > a > .material-icons").trigger("click");
			if($(".lx-pagination ul").attr("data-table") === "users"){
				loadUsers($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "suppliers"){
				loadSuppliers($(".lx-pagination ul").attr("data-state"));
			}	
			if($(".lx-pagination ul").attr("data-table") === "clients"){
				loadClients($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "devis"){
				loadDevis($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
				loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bl"){
				loadBL($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bs"){
				loadBS($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "br"){
				loadBR($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "factures"){
				loadFactures($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "avoirs"){
				loadAvoirs($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bc"){
				loadBC($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bre"){
				loadBRC($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "caisse"){
				loadCaisse($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "companies"){
				loadCompanies($(".lx-pagination ul").attr("data-state"));
			}
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Note enregistré<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
		}
	});
});

$("body").delegate(".lx-edit-extrainfo","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	tinyMCE.get("modepayment").setContent($(this).attr("data-modepayment"));
	tinyMCE.get("conditions").setContent($(this).attr("data-conditions"));
	tinyMCE.get("abovetable").setContent($(this).attr("data-abovetable"));
	$("#extrainfofpform input[name='id']").val($(this).attr("data-id"));
});

$("body").delegate(".lx-edit-facture","click",function(){
	$(".lx-textfield label > ins").remove();
	$(".lx-add-form .lx-textfield label > input").removeAttr("style");
	$(".lx-add-form .lx-textfield label > select").removeAttr("style");$(".lx-add-form .lx-textfield label > textarea").removeAttr("style");
	tinyMCE.get("modepayment").setContent($(this).attr("data-modepayment"));
	tinyMCE.get("conditions").setContent($(this).attr("data-conditions"));
	tinyMCE.get("abovetable").setContent($(this).attr("data-abovetable"));
	$("#factureform input[name='id']").val($(this).attr("data-id"));
});

$("#factureform .lx-submit a").on("click",function(){
	tinyMCE.triggerSave();
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : $("#factureform input[name='id']").val(),
			modepayment : $("#factureform textarea[name='modepayment']").val(),
			conditions : $("#factureform textarea[name='conditions']").val(),
			abovetable : $("#factureform textarea[name='abovetable']").val(),
			action : 'editfacture'
		},
		success : function(response){
			$("#factureform .lx-submit a i").remove();
			$(".lx-popup-content > a > .material-icons").trigger("click");
			loadFactures("1");
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Note enregistré<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);
		}
	});
});

$("body").delegate(".lx-delete-facture","click",function(){
	$(".lx-delete-record").attr("data-id",$(this).attr("data-id"));
});

$("#avoir").on("click",function(){
	loadFactures($(".lx-pagination ul").attr("data-state"));
});

$("body").delegate('.lx-open-edit-menu','click',function() {
    var container = $('.lx-table');
    container.animate({
        scrollTop: container.prop("scrollHeight")
    }, 1000);  // Adjust the speed (in milliseconds) if needed
});

function loadFactures(state){
	var avoir = 0;
	if($("#avoir").prop("checked") === true){
		var avoir = 1;
	}
	if($(".lx-table-factures .lx-loading").length === 0){
		$(".lx-table-factures").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			statee : $("#state").attr("data-ids"),
			avoir : avoir,
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadfactures'
		},
		success : function(response){
			$(".lx-table-factures .lx-loading").remove();
			$(".lx-table-factures").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadAvoirs(state){
	if($(".lx-table-avoirs .lx-loading").length === 0){
		$(".lx-table-avoirs").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			statee : $("#state").attr("data-ids"),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadavoirs'
		},
		success : function(response){
			$(".lx-table-avoirs .lx-loading").remove();
			$(".lx-table-avoirs").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadBR(state){
	if($(".lx-table-br .lx-loading").length === 0){
		$(".lx-table-br").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadbr'
		},
		success : function(response){
			$(".lx-table-br .lx-loading").remove();
			$(".lx-table-br").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadDevis(state){
	if($(".lx-table-devis .lx-loading").length === 0){
		$(".lx-table-devis").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loaddevis'
		},
		success : function(response){
			$(".lx-table-devis .lx-loading").remove();
			$(".lx-table-devis").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadFacturesProforma(state){
	if($(".lx-table-facturesproforma .lx-loading").length === 0){
		$(".lx-table-facturesproforma").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadfacturesproforma'
		},
		success : function(response){
			$(".lx-table-facturesproforma .lx-loading").remove();
			$(".lx-table-facturesproforma").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadBL(state){
	if($(".lx-table-bl .lx-loading").length === 0){
		$(".lx-table-bl").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadbl'
		},
		success : function(response){
			$(".lx-table-bl .lx-loading").remove();
			$(".lx-table-bl").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadBS(state){
	if($(".lx-table-bs .lx-loading").length === 0){
		$(".lx-table-bs").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadbs'
		},
		success : function(response){
			$(".lx-table-bs .lx-loading").remove();
			$(".lx-table-bs").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadBC(state){
	if($(".lx-table-bc .lx-loading").length === 0){
		$(".lx-table-bc").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			supplier : $("#supplier").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			statee : $("#state").attr("data-ids"),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadbc'
		},
		success : function(response){
			$(".lx-table-bc .lx-loading").remove();
			$(".lx-table-bc").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadBRC(state){
	if($(".lx-table-bre .lx-loading").length === 0){
		$(".lx-table-bre").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			state : state,
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			user : $("#user").attr("data-ids"),
			supplier : $("#supplier").attr("data-ids"),
			product : $("#product").attr("data-ids"),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadbre'
		},
		success : function(response){
			$(".lx-table-bre .lx-loading").remove();
			$(".lx-table-bre").html(response);
			$(".lx-caisse-total-1").html($(".lx-caisse-total1").html()).attr("style",$(".lx-caisse-total1").attr("style"));
			$(".lx-caisse-total-2").html($(".lx-caisse-total2").html()).attr("style",$(".lx-caisse-total2").attr("style"));
			$(".lx-caisse-total-4").html($(".lx-caisse-total3").html()).attr("style",$(".lx-caisse-total3").attr("style"));
			$(".lx-caisse-total1").html("");
			$(".lx-caisse-total2").html("");
			$(".lx-caisse-total3").html("");
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

$("body").delegate(".lx-delete-file","click",function(){
	$(this).next(".lx-delete-file-choice").toggle();
});

$("body").delegate(".lx-no-delete-file","click",function(){
	$(this).parent().toggle();
});

$("body").delegate(".lx-yes-delete-file","click",function(){
	var table = $(this).attr("data-table");
	var files = $(this).attr("data-files");
	var file = $(this).attr("data-file");
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			table : table,
			files : files,
			file : file,
			id : id,
			action : 'deletefiles'
		},
		success : function(response){
			if($(".lx-pagination ul").attr("data-table") === "devis"){
				loadDevis($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
				loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bl"){
				loadBL($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bs"){
				loadBS($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "br"){
				loadBR($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "factures"){
				loadFactures($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "avoirs"){
				loadAvoirs($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bc"){
				loadBC($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bre"){
				loadBRC($(".lx-pagination ul").attr("data-state"));
			}
		}		
	});	
});

$("body").delegate(".lx-detail-command","click",function(){
	if($(".details .lx-add-form .lx-loading").length === 0){
		$(".details .lx-add-form").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'detailscommand'
		},
		success : function(response){
			$(".details .lx-add-form .lx-loading").remove();
			$(".details .lx-add-form").html(response);
		}
	});
});

$(".lx-generate-invoice").on("click",function(){
	var ice = 0;
	var previce = "";
	var company = 0;
	var prevcompany = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			if($(this).attr("data-ice") !== previce && previce !== ""){
				ice += 1;
			}
			previce = $(this).attr("data-ice");	
			if($(this).attr("data-company") !== prevcompany && prevcompany !== ""){
				company += 1;
			}
			prevcompany = $(this).attr("data-company");				
		}
	});
	var ids = "";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			ids += "," + $(this).val();
		}
	});
	ids = ids.substring(1);
	if(ids !== ""){
		if(ice === 0 && company === 0){
			var k = 0;
			if(confirm('Voulez vous générer une facture?')){
				k = 0;
			} 
			else{
				k = 1;
			}
			if(k===0){
				if($(".lx-table-commands .lx-loading").length === 0){
					$(".lx-table-commands").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
				}
				var ajaxurl = "ajax.php";
				$.ajax({
					url : ajaxurl,
					type : 'post',
					data : {
						ids : ids,
						action : 'generateinvoice'
					},
					success : function(response){
						$(".lx-table-commands .lx-loading").remove();
						loadCommands($(".lx-pagination ul").attr("data-state"));
						$(".lx-floating-response").remove();
						window.clearTimeout(timer);
						$("body").append('<div class="lx-floating-response"><p class="lx-succes"><i class="fa fa-check"></i> Bien généré<i class="material-icons"></i></p></div>');
						$(".lx-floating-response").fadeIn();
						window.setTimeout(function(){
							$(".lx-floating-response").fadeOut();
						},5000);
					}
				});
			}
		}
		else{
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Vous ne pouvez pas créer une seule facture de plusieurs sociétés ou pour plusieurs clients !!<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);			
		}
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez selectionner des commandes !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
});

$("body").delegate(".lx-show-history","click",function(){
	var id = $(this).attr("data-id");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : 'showcommandhistory'
		},
		success : function(response){
			$(".commandhistory .lx-add-form").html(response);
			
		}
	});	
});

function loadJCaisse(){
	if($(".lx-table-jcaisse .lx-loading").length === 0){
		$(".lx-table-jcaisse").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			keyword : $("#keyword").val(),
			company : $("#company").attr("data-ids"),
			project : $("#project").attr("data-ids"),
			worker : $("#worker").attr("data-ids"),
			depot : $("#depot").attr("data-ids"),
			nature : $("#nature").val(),
			type : $("#type").val(),
			invoiced : $("#invoiced").val(),
			modepayment : $("#modepayment").val(),
			pricemin : $("#pricemin").val(),
			pricemax : $("#pricemax").val(),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			dateduestart : $(".lx-keyword #dateduestart").val(),
			datedueend : $(".lx-keyword #datedueend").val(),	
			datepaidstart : $(".lx-keyword #datepaidstart").val(),
			datepaidend : $(".lx-keyword #datepaidend").val(),	
			paid : ($("#paid").prop("checked") === true?"1":"0"),
			start : $(".lx-pagination ul").attr("data-start"),
			nbpage : $(".lx-pagination ul").attr("data-nbpage"),
			sortby : $(".lx-keyword input[name='sortby']").val(),
			orderby : $(".lx-keyword input[name='orderby']").val(),
			action : 'loadjcaisse'
		},
		success : function(response){
			$(".lx-table-jcaisse .lx-loading").remove();
			$(".lx-table-jcaisse").html(response);
			onScroll();
			if(filterClicked === 'yes'){
				initPagination();
			}
			if($("input[name='orderby']").val() === "DESC"){
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-down");
			}
			else{
				$(".lx-first-tr i[data-sort='"+$("input[name='sortby']").val()+"']").attr("class","fa fa-sort-up");
			}
		}
	});
}

function loadKPI(){
	if($("#kpi .lx-loading").length === 0){
		$("#kpi").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : $("#company").attr("data-ids"),	
			client : $("#client").attr("data-ids"),
			supplier : $("#supplier").attr("data-ids"),
			invoiced : $("#invoiced").attr("data-ids"),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			action : 'loadkpi'
		},
		success : function(response){
			$("#kpi .lx-loading").remove();
			$("#kpi").html(response);
		}
	});
}

function loadKPI1(){
	if($("#documents .lx-loading").length === 0){
		$("#documents").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : $("#company").attr("data-ids"),	
			client : $("#client").attr("data-ids"),
			supplier : $("#supplier").attr("data-ids"),
			invoiced : $("#invoiced").attr("data-ids"),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			action : 'loaddocuments'
		},
		success : function(response){
			$("#documents .lx-loading").remove();
			$("#documents").html(response);
		}
	});
}

$(".lx-table-ca ul li a").on("click",function(){
	$(".lx-table-ca ul li a").removeClass("active");
	$(this).addClass("active");
	$("#period").val($(this).attr("data-period"));
	loadCA();
});

function loadCA(){
	if($("#ca .lx-loading").length === 0){
		$("#ca").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : $("#company").attr("data-ids"),	
			typedoc : $("#typedoc").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			supplier : $("#supplier").attr("data-ids"),
			product : $("#product").attr("data-ids"),	
			period : $("#period").val(),	
			invoiced : $("#invoiced").attr("data-ids"),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			action : 'loadca'
		},
		success : function(response){
			var data = response.split("|");
			chartdates = data[0].split(",");
			chartnbdocuments = data[1].split(",").map(Number);
			chartvalue = data[2].split(",").map(Number);
			loadCAChart();
			$("#ca .lx-loading").remove();
		}
	});
}

function loadCAChart(){
	Highcharts.setOptions({
		colors: ['#39add1','#7EC855'],
	});
	Highcharts.chart('ca', {
		chart: {
			type: 'spline',
			height: 300,
			backgroundColor: null
		},
		title: {
			text: ''
		},
		yAxis: {
			title: {
				text: ''
			},
			alternateGridColor: '#FBFBFB'
		},
		xAxis: {
			categories: chartdates,
		},
		series: [{
			showInLegend: true,
			name: 'Nb. doc.',
			data: chartnbdocuments,
            tooltip: {
                valueSuffix: ''
            }
		},{
			showInLegend: true,
			name: 'Valeur',
			data: chartvalue,
            tooltip: {
                valueSuffix: ' DH'
            }
		}],
		credits: {
			 enabled: false
		},
		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						layout: 'horizontal',
						align: 'center',
						verticalAlign: 'bottom'
					}
				}
			}]
		}
	});	
}

var topname;
var topvalue;

function loadTop(){
	if($("#topca .lx-loading").length === 0){
		$("#topca").prepend('<div class="lx-loading" style="padding:10px;text-align:center;"><p>Please wait ...<br /><i class="fa fa-circle-notch fa-spin"></i></p></div>');
	}
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			company : $("#company").attr("data-ids"),	
			typedoc : $("#typedoc").attr("data-ids"),
			client : $("#client").attr("data-ids"),
			supplier : $("#supplier").attr("data-ids"),
			product : $("#product").attr("data-ids"),	
			topwho : $("#topwho").val(),	
			topwhat : $("#topwhat").val(),	
			invoiced : $("#invoiced").attr("data-ids"),
			datestart : $(".lx-keyword #datestart").val(),
			dateend : $(".lx-keyword #dateend").val(),	
			action : 'loadtop'
		},
		success : function(response){
			console.log(response);
			var data = response.split("|");
			topname = data[0].split(",");
			topvalue = data[1].split(",").map(Number);
			loadTop10();
			$("#topca .lx-loading").remove();
		}
	});
}

function loadTop10(){
	Highcharts.setOptions({
		colors: ['#39add1'],
	});
	Highcharts.chart('topca', {
		chart: {
			type: 'column',
			height: 300,
			backgroundColor: null
		},
		title: {
			text: ''
		},
		yAxis: {
			title: {
				text: ''
			},
			alternateGridColor: '#FBFBFB'
		},
		xAxis: {
			categories: topname,
		},
		series: [{
			showInLegend: true,
			name: $("#topwhat option:selected").text(),
			data: topvalue,
            tooltip: {
                valueSuffix: ' '+($("#topwhat").val()==="nbdocs"?'':'DH')
            }
		}],
		credits: {
			 enabled: false
		},
		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						layout: 'horizontal',
						align: 'center',
						verticalAlign: 'bottom'
					}
				}
			}]
		}
	});	
}

var clientpaid;
var clientencours;
var supplierpaid;
var supplierencours;

function loadPaid(){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			invoiced : $("#client").val(),	
			datestart : $(".lx-keyword #datestart2").val(),
			dateend : $(".lx-keyword #dateend2").val(),	
			action : 'loadpaid'
		},
		success : function(response){
			var data = response.split("|").map(Number);
			clientpaid = parseFloat(data[0]);
			clientencours = parseFloat(data[1]);
			supplierpaid = parseFloat(data[2]);
			supplierencours = parseFloat(data[3]);
			loadPaidPie();
		}
	});
}

function loadPaidPie(){
	Highcharts.chart('paidclient', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: ''
		},
		tooltip: {
			pointFormat: '<b>{point.y} DH</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.y} DH',
				}
			}
		},
		credits: {
			 enabled: false
		},
		series: [{
			name: 'Valeur',
			colorByPoint: true,
			data: [{
				name: 'Payée',
				y: clientpaid
			},  {
				name: 'En cours',
				y: clientencours
			}]
		}]
	});	
	
	Highcharts.chart('paidsupplier', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: ''
		},
		tooltip: {
			pointFormat: '<b>{point.y} DH</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.y} DH',
				}
			}
		},
		credits: {
			 enabled: false
		},
		series: [{
			name: 'Valeur',
			colorByPoint: true,
			data: [{
				name: 'Payée',
				y: supplierpaid
			},  {
				name: 'En cours',
				y: supplierencours
			}]
		}]
	});	
}

$("body").delegate(".lx-on-off","click",function(){
	if($(this).attr("data-state") !== "off"){
		$(this).removeClass("lx-on-off-blue");
		$(this).attr("data-state","off");
		changeState($(this).attr("data-table"),$(this).attr("data-column"),$(this).attr("data-id"),"off");
	}
	else{
		$(this).addClass("lx-on-off-blue");
		$(this).attr("data-state","on");
		changeState($(this).attr("data-table"),$(this).attr("data-column"),$(this).attr("data-id"),"on");
		if($(".lx-pagination ul").attr("data-table") === "projects"){
			$(".lx-edit-dateend").trigger("click");
			$("#endprojectform input[name='id']").val($(this).attr("data-id"));
		}
	}
});

function changeState(table,column,id,state){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			table : table,
			column : column,
			id : id,
			state : state,
			action : 'changestate'
		},
		success : function(response){
			if($(".lx-pagination ul").attr("data-table") === "projects"){
				loadProjects($(".lx-pagination ul").attr("data-state"));
			}
		}
	});
}

$(".lx-cancel-delete").on("click",function(){
	$(".lx-popup-content > a > .material-icons").trigger("click");
});

$(".lx-delete-record").on("click",function(){
	var id = $(this).attr("data-id");
	var ids = $(this).attr("data-ids");
	var action = $(this).attr("data-action");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			action : action
		},
		success : function(response){
			$(".lx-popup-content > a > .material-icons").trigger("click");
			if($(".lx-pagination ul").attr("data-table") === "users"){
				loadUsers($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "suppliers"){
				if(response !== ""){
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);				
				}
				else{
					loadSuppliers($(".lx-pagination ul").attr("data-state"));
				}
			}		
			if($(".lx-pagination ul").attr("data-table") === "clients"){
				if(response !== ""){
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);				
				}
				else{
					loadClients($(".lx-pagination ul").attr("data-state"));
				}
			}
			if($(".lx-pagination ul").attr("data-table") === "devis"){
				loadDevis($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
				loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bl"){
				loadBL($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bs"){
				loadBS($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "br"){
				loadBR($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "factures"){
				if(response !== ""){
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);				
				}
				else{
					loadFactures($(".lx-pagination ul").attr("data-state"));
				}
			}
			if($(".lx-pagination ul").attr("data-table") === "avoirs"){
				loadAvoirs($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bc"){
				loadBC($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "bre"){
				loadBRC($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "caisse"){
				loadCaisse($(".lx-pagination ul").attr("data-state"));
			}
			if($(".lx-pagination ul").attr("data-table") === "companies"){
				loadCompanies($(".lx-pagination ul").attr("data-state"));
				if(response !== ""){
					$(".lx-floating-response").remove();
					window.clearTimeout(timer);
					$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> '+response+'<i class="material-icons"></i></p></div>');
					$(".lx-floating-response").fadeIn();
					window.setTimeout(function(){
						$(".lx-floating-response").fadeOut();
					},5000);				
				}	
			}
		}
	});
});

$("body").delegate(".lx-first-tr i","click",function(){
	filterClicked = "yes";
	$(".lx-keyword input[name='sortby']").val($(this).attr("data-sort"));
	if($(".lx-keyword input[name='orderby']").val() === "ASC"){
		$(".lx-keyword input[name='orderby']").val("DESC");
	}
	else{
		$(".lx-keyword input[name='orderby']").val("ASC");
	}
	initPagination();
	if($(".lx-pagination ul").attr("data-table") === "users"){
		loadUsers($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "suppliers"){
		loadSuppliers($(".lx-pagination ul").attr("data-state"));
	}	
	if($(".lx-pagination ul").attr("data-table") === "clients"){
		loadClients($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "devis"){
		loadDevis($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
		loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bl"){
		loadBL($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bs"){
		loadBS($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "br"){
		loadBR($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "factures"){
		loadFactures($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "avoirs"){
		loadAvoirs($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bc"){
		loadBC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bre"){
		loadBRC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "companies"){
		loadCompanies($(".lx-pagination ul").attr("data-state"));
	}
});

$(".lx-search-keyword").on("click",function(){
	filterClicked = "yes";
	if($(".lx-pagination ul").attr("data-table") === "users"){
		loadUsers($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "suppliers"){
		loadSuppliers($(".lx-pagination ul").attr("data-state"));
	}	
	if($(".lx-pagination ul").attr("data-table") === "clients"){
		loadClients($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "devis"){
		loadDevis($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
		loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bl"){
		loadBL($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bs"){
		loadBS($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "br"){
		loadBR($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "factures"){
		loadFactures($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "avoirs"){
		loadAvoirs($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bc"){
		loadBC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bre"){
		loadBRC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "companies"){
		loadCompanies($(".lx-pagination ul").attr("data-state"));
	}
});

$("#topwhat,#topwho").on("change",function(){
	loadTop();
});

$("#worker,#paid,#modepayment,#type,#invoiced,#typedoc,#typedoc1,#company,#supplier,#product,#product1,#client,#imputation,#rib").on("change",function(){
	filterClicked = "yes";
	if($("#ca").length){
		loadCA();
	}
	if($("#topca").length){
		loadTop();
	}
	if($(".lx-pagination ul").attr("data-table") === "users"){
		loadUsers($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "suppliers"){
		loadSuppliers($(".lx-pagination ul").attr("data-state"));
	}	
	if($(".lx-pagination ul").attr("data-table") === "clients"){
		loadClients($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "devis"){
		loadDevis($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
		loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bl"){
		loadBL($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bs"){
		loadBS($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "br"){
		loadBR($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "factures"){
		loadFactures($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "avoirs"){
		loadAvoirs($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bc"){
		loadBC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bre"){
		loadBRC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "companies"){
		loadCompanies($(".lx-pagination ul").attr("data-state"));
	}
});

function initPagination(){
	filterClicked = 'no';
	if(parseFloat($("#posts").val()) > parseFloat($(".lx-pagination ul").attr("data-nbpage"))){
		$(".lx-pagination").css("display","block");
		$(".lx-pagination ul").attr("data-start",0);
		$(".lx-pagination ul").attr("data-posts",$("#posts").val());
		$(".lx-pagination ul li ins").text("1");
		var nbpage = Math.ceil(parseFloat($("#posts").val()) / parseFloat($(".lx-pagination ul").attr("data-nbpage")))
		$(".lx-pagination ul li abbr").text(nbpage);
		$(".lx-pagination ul li .next").removeClass("disabled");
		$(".lx-pagination ul li .previous").removeClass("disabled");
		$(".lx-pagination ul li .previous").addClass("disabled");
		$("#pgnumber option").remove();
		for(var i=0;i<nbpage;i++){
			$("#pgnumber").append('<option value="'+i+'">'+(i+1)+'</option>');
		}
	}
	else{
		$(".lx-pagination").css("display","none");
	}
}

$(".lx-pagination ul li .next").on("click",function(){
	filterClicked = 'no';
	var start = parseFloat($(".lx-pagination ul").attr("data-start"));
	var nbpage = parseFloat($(".lx-pagination ul").attr("data-nbpage"));
	var posts = parseFloat($(".lx-pagination ul").attr("data-posts"));
	if((start + nbpage) < posts){
		if((start + (nbpage * 2)) >= posts){
			$(this).addClass("disabled");
		}
		$(".lx-pagination ul li .previous").removeClass("disabled");
		$(".lx-pagination ul").attr("data-start",(start+nbpage));
		$(".lx-pagination ul li span ins").text((start+(nbpage*2))/nbpage);
		$(".lx-pagination ul li #pgnumber").val(parseFloat($(".lx-pagination ul li span ins").text()) - 1);
		if($(".lx-pagination ul").attr("data-table") === "users"){
			loadUsers($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "suppliers"){
			loadSuppliers($(".lx-pagination ul").attr("data-state"));
		}	
		if($(".lx-pagination ul").attr("data-table") === "clients"){
			loadClients($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "devis"){
			loadDevis($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
			loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bl"){
			loadBL($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bs"){
			loadBS($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "br"){
			loadBR($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "factures"){
			loadFactures($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "avoirs"){
			loadAvoirs($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bc"){
			loadBC($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bre"){
			loadBRC($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "caisse"){
			loadCaisse($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "companies"){
			loadCompanies($(".lx-pagination ul").attr("data-state"));
		}
		$('html, body').animate({
			scrollTop: $(".lx-main-content").offset().top
		}, 1000);
	}
});

$(".lx-pagination ul li .previous").on("click",function(){
	filterClicked = 'no';
	var start = parseFloat($(".lx-pagination ul").attr("data-start"));
	var nbpage = parseFloat($(".lx-pagination ul").attr("data-nbpage"));
	var posts = parseFloat($(".lx-pagination ul").attr("data-posts"));
	if(start > 0){
		if((start - nbpage) === 0){
			$(this).addClass("disabled");
		}
		$(".lx-pagination ul li .next").removeClass("disabled");
		$(".lx-pagination ul").attr("data-start",(start-nbpage));
		$(".lx-pagination ul li span ins").text(start/nbpage);
		$(".lx-pagination ul li #pgnumber").val(parseFloat($(".lx-pagination ul li span ins").text()) - 1);
		if($(".lx-pagination ul").attr("data-table") === "users"){
			loadUsers($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "suppliers"){
			loadSuppliers($(".lx-pagination ul").attr("data-state"));
		}	
		if($(".lx-pagination ul").attr("data-table") === "clients"){
			loadClients($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "devis"){
			loadDevis($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
			loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bl"){
			loadBL($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bs"){
			loadBS($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "br"){
			loadBR($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "factures"){
			loadFactures($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "avoirs"){
			loadAvoirs($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bc"){
			loadBC($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bre"){
			loadBRC($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "caisse"){
			loadCaisse($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "companies"){
			loadCompanies($(".lx-pagination ul").attr("data-state"));
		}
		$('html, body').animate({
			scrollTop: $(".lx-main-content").offset().top
		}, 1000);
	}
});

$("#pgnumber").on("change",function(){
	filterClicked = 'no';
	$(".lx-pagination ul").attr("data-start",parseFloat($(".lx-pagination ul").attr("data-nbpage")) * $(this).val())
	$(".lx-pagination ul li span ins").text(parseFloat($(this).val()) + 1);
	if((parseFloat($(this).val()) + 1) >= (parseFloat($(".lx-pagination ul").attr("data-posts")) / parseFloat($(".lx-pagination ul").attr("data-nbpage")))){
		$(".lx-pagination ul li .previous").removeClass("disabled");
		$(".lx-pagination ul li .next").addClass("disabled");
	}
	else if(parseFloat($(this).val() + 1) === 1 ){
		$(".lx-pagination ul li .previous").addClass("disabled");
		$(".lx-pagination ul li .next").removeClass("disabled");
	}
	else{
		$(".lx-pagination ul li .previous").removeClass("disabled");
		$(".lx-pagination ul li .next").removeClass("disabled");
	}
	if($(".lx-pagination ul").attr("data-table") === "users"){
		loadUsers($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "suppliers"){
		loadSuppliers($(".lx-pagination ul").attr("data-state"));
	}	
	if($(".lx-pagination ul").attr("data-table") === "clients"){
		loadClients($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "devis"){
		loadDevis($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
		loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bl"){
		loadBL($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bs"){
		loadBS($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "br"){
		loadBR($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "factures"){
		loadFactures($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "avoirs"){
		loadAvoirs($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bc"){
		loadBC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bre"){
		loadBRC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "companies"){
		loadCompanies($(".lx-pagination ul").attr("data-state"));
	}
	$('html, body').animate({
		scrollTop: $(".lx-main-content").offset().top
	}, 1000);
});

$(".lx-action-bulk a").on("click",function(){
	var ids = "0";
	$(".lx-main .lx-table input[type='checkbox']:checked").each(function(){
		if($(this).val() !== "selectall"){
			ids += "," + $(this).val();
		}
	});
	var table = $(".lx-pagination ul").attr("data-table");
	if($(".lx-pagination ul").attr("data-table") === "worker" || $(".lx-pagination ul").attr("data-table") === "dlm" || $(".lx-pagination ul").attr("data-table") === "subdlm"){
		table = "users";
	}
	if($(".lx-pagination ul").attr("data-table") === "confirmation"){
		table = "commands";
	}
	var column = "id";
	if(ids !== "0" && $(".lx-action-bulk select[name='statebulk']").val() !== ""){
		var ajaxurl = "ajax.php";
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data : {
				ids : ids,
				table : table,
				column : column,
				state : $(".lx-action-bulk select[name='statebulk']").val(),
				action : 'updatebulk'
			},
			success : function(response){
				if($(".lx-pagination ul").attr("data-table") === "users"){
					loadUsers($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "suppliers"){
					loadSuppliers($(".lx-pagination ul").attr("data-state"));
				}	
				if($(".lx-pagination ul").attr("data-table") === "clients"){
					loadClients($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "devis"){
					loadDevis($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
					loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "bl"){
					loadBL($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "bs"){
					loadBS($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "br"){
					loadBR($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "factures"){
					loadFactures($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "avoirs"){
					loadAvoirs($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "bc"){
					loadBC($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "bre"){
					loadBRC($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "caisse"){
					loadCaisse($(".lx-pagination ul").attr("data-state"));
				}
				if($(".lx-pagination ul").attr("data-table") === "companies"){
					loadCompanies($(".lx-pagination ul").attr("data-state"));
				}
			}
		});
	}
});

$(".lx-action-bulk select[name='nbrows']").on("change",function(){
	filterClicked = 'yes';
	$(".lx-pagination ul").attr("data-nbpage",$(this).val());
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			nbrows : $(".lx-pagination ul").attr("data-nbpage"),
			action : 'editnbrows'
		},
		success : function(response){}
	});
	if($(".lx-pagination ul").attr("data-table") === "users"){
		loadUsers($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "suppliers"){
		loadSuppliers($(".lx-pagination ul").attr("data-state"));
	}	
	if($(".lx-pagination ul").attr("data-table") === "clients"){
		loadClients($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "devis"){
		loadDevis($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
		loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bl"){
		loadBL($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bs"){
		loadBS($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "br"){
		loadBR($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "factures"){
		loadFactures($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "avoirs"){
		loadAvoirs($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bc"){
		loadBC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "bre"){
		loadBRC($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "caisse"){
		loadCaisse($(".lx-pagination ul").attr("data-state"));
	}
	if($(".lx-pagination ul").attr("data-table") === "companies"){
		loadCompanies($(".lx-pagination ul").attr("data-state"));
	}
});

var mover = "no";
$(document).mousemove(function(){
	if($(".lx-autocomplete:hover").length != 0){
		mover = "yes";
	} 
	else{
		mover = "no";
	}
	if($(".lx-autocomplete-advanced:hover").length != 0){
		mover = "yes";
	} 
	else{
		mover = "no";
	}
});

$("body").mouseup(function (e){
	if($(".lx-autocomplete").length){
		var container = $(".lx-autocomplete");
		var inside = $(".lx-autocomplete *");
		if (!inside.is(e.target) && mover === "no"){
			container.hide();
		}
	}
	if($(".lx-autocomplete-advanced").length){
		var container = $(".lx-autocomplete-advanced");
		var container1 = $(".lx-autocomplete-advanced").prev("input");
		var inside = $(".lx-autocomplete-advanced *");
		if (!inside.is(e.target) && !container1.is(e.target) && mover === "no"){
			container.hide();
		}
	}
	if($(".lx-advanced-select").length){
		var elToHide = $(".lx-advanced-select > div");
		var elPreventHide = $(".lx-advanced-select *");
		if (!elPreventHide.is(e.target)){
			elToHide.hide();
		}
	}
	if($(".lx-edit-menu").length){
		var elToHide = $(".lx-edit-menu");
		var elPreventHide = $(".lx-edit-menu *");
		if (!elPreventHide.is(e.target)){
			elToHide.hide();
		}
	}
	if($(".lx-notifications-list").length){
		var elToHide = $(".lx-notifications-list");
		var elPreventHide = $(".lx-notifications-list *");
		if (!elPreventHide.is(e.target)){
			elToHide.hide();
		}
	}	
	if($(".lx-account-settings").length){
		var elToHide = $(".lx-account-settings");
		var elPreventHide = $(".lx-account-settings *");
		if (!elPreventHide.is(e.target)){
			elToHide.hide();
		}
	}	
});

function isNotEmpty(el){
	var val = el.val();
	if(typeof el.attr("data-id") !== typeof undefined && el.attr("data-id") !== false){
		val = el.attr("data-id");
	}
	if(val === "" || val === null){
		if(el.parent().find("> ins").length === 0){
			el.parent().append("<ins>"+el.attr("data-message")+"</ins>");
			el.css("border-color","#d63232");
		}
		return false;
	}
	else{
		el.removeAttr("style");
		return true;
	}
}

$("*[data-isnotempty]").on("keyup blur paste change",function(){
	if($(this).val() !== "" && $(this).val() !== null && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

$(".lx-popup-content").delegate("*[data-isnotempty]","keyup blur paste change",function(){
	if($(this).val() !== "" && $(this).val() !== null && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

$("body").delegate("*[data-isnotempty]","keyup blur paste change",function(){
	if($(this).val() !== "" && $(this).val() !== null && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

function isNotZero(el){
	var val = el.val();
	if(typeof el.attr("data-id") !== typeof undefined && el.attr("data-id") !== false){
		val = el.attr("data-id");
	}
	if(val === "" || val === "0" || val === null){
		if(el.parent().find("> ins").length === 0){
			el.parent().append("<ins>"+el.attr("data-message")+"</ins>");
			el.css("border-color","#d63232");
		}
		return false;
	}
	else{
		el.removeAttr("style");
		return true;
	}
}

$("*[data-isnotzero]").on("keyup blur paste change",function(){
	if($(this).val() !== "" && $(this).val() !== "0" && $(this).val() !== null && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

function isNumber(el){
	var regex = /^[0-9,\.]+$/;
	var k = 0;
	if(typeof el.attr("data-notzero") !== typeof undefined && el.attr("data-notzero") !== false){
		k = 1;
	}
	if(k === 1 && el.val() === "0"){
		if(el.parent().find("> ins").length === 0){
			el.parent().append("<ins>"+el.attr("data-message")+"</ins>");
			el.css("border-color","#d63232");
			el.css("display","block");
		}
		return false;
	}
	else if(!regex.test(el.val())){
		if(el.parent().find("> ins").length === 0){
			el.parent().append("<ins>"+el.attr("data-message")+"</ins>");
			el.css("border-color","#d63232");
			el.css("display","block");
		}
		return false;
	}
	else{
		el.removeAttr("style");
		return true;
	}
}

$("*[data-isnumber]").on("keyup blur paste change",function(){
	var regex = /^[0-9,\.]+$/;
	if(regex.test($(this).val()) && $(this).val() !== "0" && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});
$(".lx-popup-content").delegate("*[data-isnumber]","keyup blur paste change",function(){
	var regex = /^[0-9,\.]+$/;
	if(regex.test($(this).val()) && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});
$("body").delegate("*[data-isnumber]","keyup blur paste change",function(){
	var regex = /^[0-9,\.]+$/;
	if(regex.test($(this).val()) && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

$("body").delegate("*[data-isnumber]","keypress keyup blur paste",function(){
	var regex = /\./;
	if(regex.test($(this).val())){
		return (event.charCode >= 48 && event.charCode <= 57)
	}
	else{
		return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)
	}
});

$(".lx-popup-content").delegate("*[data-isnumber]","keypress keyup blur paste",function(){
	var regex = /\./;
	if(regex.test($(this).val())){
		return (event.charCode >= 48 && event.charCode <= 57)
	}
	else{
		return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)
	}
});

function isPhone(el){
	var regex = /^([0-9 \(\)\-\+\.]){1,22}$/;
	if(!regex.test(el.val())){
		if(el.parent().find("> ins").length === 0){
			el.parent().append("<ins>"+el.attr("data-message")+"</ins>");
			el.css("border-color","#d63232");
		}
		return false;
	}
	else{
		el.removeAttr("style");
		return true;
	}
}

$("*[data-isphone]").on("keyup blur paste change",function(){
	var regex = /^([0-9 \(\)\-\+\.]){1,22}$/;
	if(regex.test($(this).val()) && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

function isEmail(el){
	var regex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
	if(!regex.test(el.val())){
		if(el.parent().find("> ins").length === 0){
			el.parent().append("<ins>"+el.attr("data-message")+"</ins>");
			el.css("border-color","#d63232");
		}
		return false;
	}
	else{
		el.removeAttr("style");
		return true;
	}
}

$("*[data-isemail]").on("keyup blur paste change",function(){
	var regex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
	if(regex.test($(this).val()) && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

function isPassword(el){
	if(el.val().length < 6){
		if(el.parent().find("> ins").length === 0){
			el.parent().append("<ins>"+el.attr("data-message")+"</ins>");
			el.css("border-color","#d63232");
		}
		return false;
	}
	else{
		el.removeAttr("style");
		return true;
	}
}

$("*[data-ispassword]").on("keyup blur paste change",function(){
	if($(this).val().length >= 6 && $(this).parent().find("> ins").length){
		$(this).parent().find("> ins").remove();
		$(this).removeAttr("style");
	}
});

$(".lx-popup-content").delegate(".todropdowninput","keyup",function(){
	$(this).parent().find("select").val($(this).parent().find("select option:eq(0)").attr("value"));
	$(this).parent().find("del").text("");
});

function toDropDown(){
	$(".todropdown").each(function(){
		$(this).parent().find(".todropdowninput").css("color","#AEAEAE");
		$(this).parent().find(".lx-autocomplete-advanced").remove();
		if($(this).prop("disabled") === false){
			$(this).parent().find(".todropdowninput").remove();
			var data_error = "";
			if(typeof $(this).attr("data-isnotempty") !== typeof undefined && $(this).attr("data-isnotempty") !== false){
				data_error = "data-isnotempty='' data-message='"+$(this).attr("data-message")+"'";
			}
			if($(this).find("option:selected").val() === ""){
				var html = '<i class="fa fa-angle-down"></i><input type="text" autocomplete="off" name="'+$(this).attr("name")+'" placeholder="'+$(this).find("option:selected").text()+'" class="todropdowninput" '+data_error+' />';
			}
			else{
				var html = '<i class="fa fa-angle-down"></i><input type="text" autocomplete="off" name="'+$(this).attr("name")+'" value="'+$(this).find("option:selected").text()+'" class="todropdowninput" '+data_error+' />';
			}
			html += '<div class="lx-autocomplete-advanced lx-autocomplete-'+$(this).attr("name")+'">';
			$(this).find("option").each(function(){				
				html += '<span data-id="'+$(this).attr("value")+'">'+$(this).text()+'</span>';
			});
			html += '</div>';
			$(this).before(html);
			$(this).parent().find(".todropdowninput").css("color","#242424");
		}
	});		
}

function toDropDownTargeted(el){
	$(el).each(function(){
		$(this).parent().find(".todropdowninput").css("color","#AEAEAE");
		$(this).parent().find(".lx-autocomplete-advanced").remove();
		if($(this).prop("disabled") === false){
			$(this).parent().find(".todropdowninput").remove();
			var data_error = "";
			if(typeof $(this).attr("data-isnotempty") !== typeof undefined && $(this).attr("data-isnotempty") !== false){
				data_error = "data-isnotempty='' data-message='"+$(this).attr("data-message")+"'";
			}
			if($(this).find("option:selected").val() === ""){
				var html = '<i class="fa fa-angle-down"></i><input type="text" autocomplete="off" name="'+$(this).attr("name")+'" placeholder="'+$(this).find("option:selected").text()+'" class="todropdowninput" '+data_error+' />';
			}
			else{
				var html = '<i class="fa fa-angle-down"></i><input type="text" autocomplete="off" name="'+$(this).attr("name")+'" value="'+$(this).find("option:selected").text()+'" class="todropdowninput" '+data_error+' />';
			}
			html += '<div class="lx-autocomplete-advanced lx-autocomplete-'+$(this).attr("name")+'">';
			$(this).find("option").each(function(){				
				html += '<span data-id="'+$(this).attr("value")+'">'+$(this).text()+'</span>';
			});
			html += '</div>';
			$(this).before(html);
			$(this).parent().find(".todropdowninput").css("color","#242424");
		}
	});		
}

function loadRecall(id,depot,bprice){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			bprice : bprice,
			depot : $("#commandsform select[name='depot']").val(),
			commandid : $("#commandsform input[name='id']").val(),
			invoiced : $("#commandsform select[name='invoiced']").val(),
			action : 'loadrecall'
		},
		success : function(response){
			$("#commandsform .lx-textfield del").html(response);
			
			/*var qtyoui = 0;
			var qtynon = 0;
			
			var allproducts = $("#commandsform input[name='allproducts']").val().split(",");
			var alldepots = $("#commandsform input[name='alldepots']").val().split(",");
			var allqtys = $("#commandsform input[name='allqtys']").val().split(",");
			var allbprices = $("#commandsform input[name='allbprices']").val().split(",");
			var allprices = $("#commandsform input[name='allprices']").val().split(",");			
			
			$("#commandsform select[name='product'] option[value='"+id+"']").each(function(){
				for(var i=1;i<allproducts.length;i++){
					if($(this).val() === allproducts[i] && $(this).attr("data-depot") === alldepots[i] && $(this).attr("data-bprice") === allbprices[i]){
						if($("#commandsform select[name='invoiced']").val() === "Oui"){
							qtyoui += parseFloat(allqtys[i]);
						}
						if($("#commandsform select[name='invoiced']").val() === "Non"){
							qtynon += parseFloat(allqtys[i]);
						}
					}
				}
			});*/

			var tqtyoui = 0;
			var tqtynon = 0;
			$("#commandsform select[name='product'] option[value='"+id+"']").each(function(){
				tqtyoui += parseFloat($(this).attr("data-qtyoui"));
				tqtynon += parseFloat($(this).attr("data-qtynon"));
			});
			$("#commandsform .lx-textfield del ins.lx-qtyoui").text(tqtyoui);
			$("#commandsform .lx-textfield del ins.lx-qtynon").text(tqtynon);
			/*$("#commandsform .lx-textfield del ins.lx-qtyoui").text(decimalOperation(tqtyoui,qtyoui,"minus"));
			$("#commandsform .lx-textfield del ins.lx-qtynon").text(decimalOperation(tqtynon,qtynon,"minus"));*/
		}
	});	
}

function adjustQtys(){
	var allproducts = $("#commandsform input[name='allproducts']").val().split(",");
	var alldepots = $("#commandsform input[name='alldepots']").val().split(",");
	var allqtys = $("#commandsform input[name='allqtys']").val().split(",");
	var allbprices = $("#commandsform input[name='allbprices']").val().split(",");
	var allprices = $("#commandsform input[name='allprices']").val().split(",");			
	
	$("#commandsform select[name='product'] option").each(function(){
		if(typeof $(this).attr("data-bprice") !== typeof undefined && $(this).attr("data-bprice") !== false){
			var prevqtyoui = $(this).attr("data-oqtyoui");
			var prevqtynon = $(this).attr("data-oqtynon");
			for(var i=1;i<allproducts.length;i++){
				if($(this).val() === allproducts[i] && $(this).attr("data-depot") === alldepots[i] && $(this).attr("data-bprice") === allbprices[i]){
					if($("#commandsform select[name='invoiced']").val() === "Oui"){
						var qtyoui = decimalOperation(parseFloat(prevqtyoui),parseFloat(allqtys[i]),"minus");
						$(this).attr("data-qtyoui",qtyoui);
						prevqtyoui = qtyoui;
						
					}
					if($("#commandsform select[name='invoiced']").val() === "Non"){
						var qtynon = decimalOperation(parseFloat(prevqtynon),parseFloat(allqtys[i]),"minus");
						$(this).attr("data-qtynon",qtynon);
						prevqtynon = qtynon;
					}
				}
			}
		}
	});	
}

$(".body").delegate(".todropdowninput","mousedown",function(){
	if(typeof $(this).attr("readonly") !== typeof undefined && $(this).attr("readonly") !== false){

	}
	else{
		$(this).parent().find(".lx-autocomplete-advanced").toggle();
	}
});

$(".lx-popup-content").delegate(".todropdowninput","mousedown",function(){
	if(typeof $(this).attr("readonly") !== typeof undefined && $(this).attr("readonly") !== false){

	}
	else{
		$(this).parent().find(".lx-autocomplete-advanced").toggle();
	}
});

$(".lx-popup-content").delegate(".todropdowninput","keyup",function(){
	var val = $(this).val();
	var valeur = new RegExp(val,'i');
	$(this).next(".lx-autocomplete-advanced").find("span").each(function(){
		if(valeur.test($(this).text())){
			$(this).not("[data-display]").css("display","block");
		}
		else{
			$(this).css("display","none");
		}
	});
});

$("body").delegate(".lx-autocomplete-advanced span","click",function(){
	if($(this).attr("data-disabled") !== "yes"){
		if($(this).attr("data-id") !== ""){
			$(this).parent().prev("input").val($(this).text());
		}
		else{
			$(this).parent().prev("input").val("");
		}
		$(this).parent().next("select").find("option:eq("+$(this).index()+")").prop("selected",true);
		$(this).parent().hide();
		$(this).parent().next("select").trigger("keyup");
		if($(".lx-pagination ul").attr("data-table") === "users"){
			loadUsers($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "suppliers"){
			loadSuppliers($(".lx-pagination ul").attr("data-state"));
		}	
		if($(".lx-pagination ul").attr("data-table") === "clients"){
			loadClients($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "devis"){
			loadDevis($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "facturesproforma"){
			loadFacturesProforma($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bl"){
			loadBL($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bs"){
			loadBS($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "br"){
			loadBR($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "factures"){
			loadFactures($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "avoirs"){
			loadAvoirs($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bc"){
			loadBC($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "bre"){
			loadBRC($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "caisse"){
			loadCaisse($(".lx-pagination ul").attr("data-state"));
		}
		if($(".lx-pagination ul").attr("data-table") === "companies"){
			loadCompanies($(".lx-pagination ul").attr("data-state"));
		}
	}
});

$(".lx-popup-content").delegate(".lx-autocomplete-advanced span","click",function(){
	if($(this).attr("data-id") !== ""){
		$(this).parent().prev("input").val($(this).text());
	}
	else{
		$(this).parent().prev("input").val("");
	}
	$(this).parent().next("select").find("option[value='"+$(this).attr("data-id")+"']").prop("selected",true);
	$(this).parent().hide();
	$(this).parent().prev("input").removeAttr("style");
	$(this).parent().next("select").trigger("change");
});

function loadPlacements(product){
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			product : product,
			action : 'loadplacements'
		},
		success : function(response){
			$("select[name='placement']").html(response);
			toDropDownTargeted("select[name='placement']");
		}		
	});		
}

$("body").delegate(".todropdowninput","keyup",function(){
	var val = $(this).val();
	var valeur = new RegExp(val,'i');
	$(this).next(".lx-autocomplete-advanced").find("span").each(function(){
		if(valeur.test($(this).text())){
			$(this).not("[data-display]").css("display","block");
		}
		else{
			$(this).css("display","none");
		}
	});
});

$(".lx-popup-content").delegate(".lx-add-product-to-order","click",function(){
	isNotEmpty($("#commandsform input[name='product']"));
	isNotEmpty($("#commandsform input[name='unit']"));
	isNumber($("#commandsform input[name='qty']"));
	isNotEmpty($("#commandsform select[name='utva']"));
	isNumber($("#commandsform input[name='uprice']"));
	if(isNotEmpty($("#commandsform input[name='product']"))
	&& isNotEmpty($("#commandsform input[name='unit']"))
	&& isNumber($("#commandsform input[name='qty']"))
	&& isNotEmpty($("#commandsform select[name='utva']"))
	&& isNumber($("#commandsform input[name='uprice']"))){
		var title = $("#commandsform input[name='product']").val();
		var unit = $("#commandsform input[name='unit']").val();
		var qty = parseFloat($("#commandsform input[name='qty']").val());
		var pricebase = $("#commandsform select[name='pricebase']").val();
		var tva = parseFloat($("#commandsform select[name='utva']").val());
		var uprice = parseFloat($("#commandsform input[name='uprice']").val());
		if(pricebase === "TTC"){
			uprice = uprice / (1 + (tva / 100));
		}
		
		if($(".lx-list-products table tr.lx-otherrow[data-title='"+addSlashes(title)+"'][data-unit='"+addSlashes(unit)+"']").length){
			var el = $(".lx-list-products table tr.lx-otherrow[data-title='"+addSlashes(title)+"'][data-unit='"+addSlashes(unit)+"']");
			el.attr("data-qty",parseFloat(el.attr("data-qty")) + qty);
			el.attr("data-uprice",uprice);
			el.attr("data-tva",tva);
			el.attr("data-tprice",(parseFloat(el.attr("data-qty")) * uprice).toFixed(2));
			el.attr("data-ttprice",(parseFloat(el.attr("data-qty")) * uprice).toFixed(2));
			el.find("td:eq(1)").text((parseFloat(el.attr("data-qty")))+' '+unit);
			el.find("td:eq(2)").text(uprice.toFixed(2));
			el.find("td:eq(4)").text(tva+'%');
			el.find("td:eq(5)").text((parseFloat(el.attr("data-qty")) * uprice).toFixed(2));
		}
		else{
			var html = '<tr class="lx-otherrow" data-title="'+title+'" data-qty="'+qty+'" data-unit="'+unit+'" data-uprice="'+uprice+'" data-remise="" data-tva="'+tva+'" data-tprice="'+(qty * uprice).toFixed(2)+'" data-ttprice="'+(qty * uprice).toFixed(2)+'">';
			html += '<td><span>'+title+'</span></td>';
			html += '<td><span>'+qty+' '+unit+'</span></td>';
			html += '<td><span>'+uprice.toFixed(2)+'</span></td>';
			html += '<td class="lx-discount"><div class="lx-textfield"><label><input type="number" name="discount" /><u class="discounttype" style="position:relative;top:0px;right:0px;margin-left:5px;"></u></label></div></td>';
			html += '<td><span>'+tva+'%</span></td>';
			html += '<td><span>'+(qty * uprice).toFixed(2)+'</span></td>';
			html += '<td class="lx-qty-back"><div class="lx-textfield"><label><input type="number" name="qtyback" min="1"data-isnumber="" data-message="Saisissez une quantité !" /></label></div></td>';
			html += '<td class="lx-edit-cell"><a href="javascript:;" class="lx-edit-this"><i class="fa fa-edit" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#39add1;"></i></a></td>';
			html += '<td class="lx-delete-cell"><a href="javascript:;" class="lx-delete-this"><i class="fa fa-trash-alt" style="background:none;padding:0px;box-shadow:0px 0px 0px;color:#d81143;"></i></a></td>';
			html += '</tr>';
			$(".lx-list-products table").append(html);	
		}
		
		$("#commandsform input[name='product']").val("");
		$("#commandsform select[name='product']").val("");
		$("#commandsform input[name='unit']").val("");
		$("#commandsform select[name='unit']").val("");
		$("#commandsform input[name='qty']").val("1");
		$("#commandsform select[name='pricebase']").val("HT");
		$("#commandsform select[name='utva']").val("");
		$("#commandsform input[name='uprice']").val("");
		
		if($("#commandsform select[name='typediscount']").val() === ""){
			$(".lx-discount").css("display","none");
			$(".maindiscount").css("display","none");
		}
		else{
			$(".lx-discount").css("display","table-cell");
			$(".maindiscount").css("display","block");
			$(".discounttype").text($("#commandsform select[name='typediscount']").val());
		}
				
		$(".lx-list-products table tr td input[name='discount']").trigger("keyup");
		
		$("#commandsform input[name='maindiscount']").trigger("keyup");
		
		$(".lx-list-products table tr.lx-secondrow").css("display","none");
		
		$(".lx-error-products").html("");
		reloadProducts();
		calculateProfit();
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);			
	}
});

function addSlashes(str) {
    return str.replace(/['"\\]/g, '\\$&');
}

$(".lx-popup-content").delegate(".lx-edit-this","click",function(){
	$(this).find("i").attr("class","fa fa-save").css("color","#7EC855");
	$(this).attr("class","lx-save-this");
	var row = $(this).parent().parent();
	row.find("td:eq(0)").html('<div class="lx-textfield"><label><input type="text" name="title" value="'+row.attr("data-title")+'" data-isnotempty="" data-message="Saisissez un produit" /></label></div>');
	row.find("td:eq(1)").html('<div class="lx-textfield"><label><input type="text" name="qty" value="'+row.attr("data-qty")+'" data-isnumber="" data-message="Saisissez une qté" style="width:60px;" /></label></div>');
	row.find("td:eq(2)").html('<div class="lx-textfield"><label><input type="text" name="uprice" value="'+row.attr("data-uprice")+'" data-isnumber="" data-message="Saisissez un prix" style="width:100px;" /></label></div>');
	row.find("td:eq(4)").html('<div class="lx-textfield"><label><input type="text" name="tva" value="'+row.attr("data-tva")+'" data-isnumber="" data-message="Saisissez une tva" style="width:60px;" /></label></div>');
});

$(".lx-popup-content").delegate(".lx-save-this","click",function(){
	var row = $(this).parent().parent();
	isNotEmpty(row.find("input[name='title']"));
	isNumber(row.find("input[name='qty']"));
	isNumber(row.find("input[name='uprice']"));
	isNumber(row.find("input[name='tva']"));
	if(isNotEmpty(row.find("input[name='title']"))
	&& isNumber(row.find("input[name='qty']"))
	&& isNumber(row.find("input[name='uprice']"))
	&& isNumber(row.find("input[name='tva']"))){
		var title = row.find("input[name='title']").val();
		var qty = parseFloat(row.find("input[name='qty']").val());
		var uprice = parseFloat(row.find("input[name='uprice']").val());
		var tva = parseFloat(row.find("input[name='tva']").val());
		row.attr("data-title",title);
		row.attr("data-qty",qty);
		row.attr("data-uprice",uprice);
		row.attr("data-tva",tva);
		row.attr("data-tprice",(qty*uprice).toFixed(2));
		row.attr("data-ttprice",(qty*uprice).toFixed(2));
		row.find("td:eq(0)").html('<span>'+title+'</span>');
		row.find("td:eq(1)").html('<span>'+qty+' '+row.attr("data-unit")+'</span>');
		row.find("td:eq(2)").html('<span>'+uprice.toFixed(2)+'</span>');
		row.find("td:eq(4)").html('<span>'+tva+'%</span>');
		row.find("td:eq(5)").html('<span>'+(qty*uprice).toFixed(2)+'</span>');
		reloadProducts();
		calculateProfit();
		$(this).find("i").attr("class","fa fa-edit").css("color","#39add1");
		$(this).attr("class","lx-edit-this");
		$("input[name='discount']").trigger("keyup");
	}		
});

function reloadProducts(){
	$(".lx-list-products table tr.lx-otherrow").each(function(){
		var title = $(this).attr("data-title");
		var unit = $(this).attr("data-unit");
		
		if($("#commandsform select[name='product'] option[value='"+title+"']").length === 0){
			$("#commandsform select[name='product']").append('<option value="'+title+'" data-unit="'+unit+'">'+title+'</option>');
			$(".lx-autocomplete-product").append('<span data-id="'+title+'">'+title+'</span>');
		}

		if($("#commandsform select[name='unit'] option[value='"+unit+"']").length === 0){
			$("#commandsform select[name='unit']").append('<option value="'+unit+'">'+unit+'</option>');
			$(".lx-autocomplete-unit").append('<span data-id="'+unit+'">'+unit+'</span>');
		}
		
		/*$("#commandsform select[name='product'] option").each(function(){
			if(($(this).val() !== title && $(this).val() !== "") || $("#commandsform select[name='product'] option").length === 1){
				$("#commandsform select[name='product']").append('<option value="'+title+'" data-unit="'+unit+'">'+title+'</option>');
			}
		});
		
		$("#commandsform select[name='unit'] option").each(function(){
			if(($(this).val() !== unit && $(this).val() !== "") || $("#commandsform select[name='unit'] option").length === 1){
				$("#commandsform select[name='unit']").append('<option value="'+unit+'">'+unit+'</option>');
			}
		});*/
		
		$(".lx-autocomplete-product span").css("display","block");
		$(".lx-autocomplete-unit span").css("display","block");
	});
	
	//removeDuplicatesFromSelect($("#commandsform select[name='product']"));
	//removeDuplicatesFromSelect($("#commandsform select[name='unit']"));
	//toDropDownTargeted("#commandsform select[name='product']");
	//toDropDownTargeted("#commandsform select[name='unit']");
}

/*function removeDuplicatesFromSelect(selectElement) {
	const seenValues = {};
	selectElement.find('option').each(function() {
		const value = $(this).val();
		if (seenValues[value]) {
			$(this).remove();
		} else {
			seenValues[value] = true;
		}
	});
}*/
		
function calculateProfit(){
	var price = 0;
	var tva = 0;
	
	$(".lx-list-products table tr.lx-otherrow").each(function(){
		price += parseFloat($(this).attr("data-ttprice"));
		tva += parseFloat($(this).attr("data-ttprice")) * (parseFloat($(this).attr("data-tva")) / 100);
	});	
	
	$("#commandsform input[name='price']").val(price.toFixed(2));
	$("#commandsform input[name='tva']").val(tva.toFixed(2));
	$("#commandsform input[name='ttcprice']").val((price + tva).toFixed(2));
	$("#commandsform input[name='rest']").val((price + tva).toFixed(2));
}

$(".lx-popup-content").delegate(".lx-delete-this","click",function(){
	$(this).parent().parent().remove();
	if($(".lx-list-products table tr.lx-otherrow").length === 0){
		$(".lx-list-products table tr.lx-secondrow").css("display","table-row");
		$("#commandsform input[name='maindiscount']").val("");
	}
	
	$(".lx-list-products table tr td input[name='discount']").trigger("keyup");
	$(".lx-list-products table tr td input[name='discount']").trigger("click");
	
	calculateProfit();
});

$(".lx-popup-content").delegate("#commandsform select[name='typediscount']","change",function(){
	if($(this).val() === ""){
		$(".lx-discount").css("display","none");
		$(".maindiscount").css("display","none");
	}
	else{
		$(".lx-discount").css("display","table-cell");
		$(".maindiscount").css("display","block");
		$(".discounttype").text($(this).val());
	}
	$("#commandsform input[name='maindiscount']").val("");
	$(".lx-list-products table tr td input[name='discount']").val("");
	$(".lx-list-products table tr td input[name='discount']").trigger("keyup");
	$(".lx-list-products table tr td input[name='discount']").trigger("click");
});

function floorTwoDecimals(str){
	/*var str = parseFloat(str);*/
	str = str.toFixed(2);
	return str;
}

$(".lx-popup-content").delegate(".lx-list-products table tr td input[name='discount']","keyup blur paste change",function(event){
		var el = $(this).parent().parent().parent().parent();
		var discount = 0;
		if($(this).val() !== ""){
			discount = parseFloat($(this).val());
		}
		var price = parseFloat(el.attr("data-tprice"));
		if($("#commandsform select[name='typediscount']").val() === "%"){
			price = price - (price * parseFloat(discount) / 100);
		}
		else if($("#commandsform select[name='typediscount']").val() === "DH"){
			price = price - parseFloat(discount);
		}
		else{
			price = price;
		}
		price = (parseFloat(price)).toFixed(2);
		el.find("td:eq(5)").text(price);
		el.attr("data-ttprice",price);
		
		price = 0;

		$(".lx-list-products table tr.lx-otherrow").each(function(){
			var discount = 0;
			if($(this).find("input[name='discount']").val() !== ""){
				discount = parseFloat($(this).find("input[name='discount']").val());
			}
			if($("#commandsform select[name='typediscount']").val() === "%"){
				discount = ((parseFloat($(this).attr("data-tprice"))).toFixed(2) * parseFloat(discount) / 100);
			}
			else if($("#commandsform select[name='typediscount']").val() === "DH"){
				discount = parseFloat(discount);
			}
			var discountttc = (discount);
			price += parseFloat($(this).attr("data-tprice"));
			var tvaprice = ((parseFloat($(this).attr("data-tprice")) - discountttc) - ((parseFloat($(this).attr("data-tprice"))) - discount)).toFixed(2);
		});
		
		if(event.which){
			if($("#commandsform select[name='typediscount']").val() === "DH"){
				var discount = 0;
				$(".lx-list-products table tr.lx-otherrow").each(function(){
					if($(this).find("input[name='discount']").val() !== ""){
						discount += parseFloat($(this).find("input[name='discount']").val());
					}
				});
				$("#commandsform input[name='maindiscount']").val(discount.toFixed(2));	
			}
			else{
				var discount = 0;
				$(".lx-list-products table tr.lx-otherrow").each(function(){
					if($(this).find("input[name='discount']").val() !== ""){
						discount = parseFloat($(this).find("input[name='discount']").val());
					}
				});
				$("#commandsform input[name='maindiscount']").val(discount.toFixed(2));					
			}
		}
						
		calculateProfit();
});

$(".lx-popup-content").delegate(".lx-list-products table tr td input[name='discount']","click",function(event){
	if($("#commandsform select[name='typediscount']").val() === "DH"){
		var discount = 0;
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			if($(this).find("input[name='discount']").val() !== ""){
				discount += parseFloat($(this).find("input[name='discount']").val());
			}
		});
		$("#commandsform input[name='maindiscount']").val(discount.toFixed(2));	
	}
	else{
		var discount = 0;
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			if($(this).find("input[name='discount']").val() !== ""){
				discount = parseFloat($(this).find("input[name='discount']").val());
			}
		});
		$("#commandsform input[name='maindiscount']").val(discount.toFixed(2));	
	}
});

$("#commandsform input[name='maindiscount']").on("keyup click",function(event){
	/*if (event.which || $(this).attr("data-click") === "true") {*/
		var price = 0;

		$(".lx-list-products table tr.lx-otherrow").each(function(){
			price += parseFloat($(this).attr("data-tprice"));
		});
		if($("#commandsform select[name='typediscount']").val() === "%"){
			$(".lx-list-products table tr td input[name='discount']").val($(this).val());
		}
		else if($("#commandsform select[name='typediscount']").val() === "DH"){
			var discount = ($(this).val() * 100) / price.toFixed(2);
			$(".lx-list-products table tr.lx-otherrow").each(function(){
				price = parseFloat($(this).attr("data-tprice"));
				$(this).find("input[name='discount']").val((price * (discount / 100)).toFixed(2));
			});
		}

		$(".lx-list-products table tr td input[name='discount']").trigger("keyup");
	/*}
	else{
		var price = 0;
		$(".lx-list-products table tr.lx-otherrow").each(function(){
			price += parseFloat($(this).attr("data-tprice"));
		});
		
		if($("#commandsform select[name='typediscount']").val() === "%"){

		}
		else if($("#commandsform select[name='typediscount']").val() === "DH"){
			var discount = ($(this).val() * 100) / price.toFixed(2);
			$(".lx-list-products table tr.lx-otherrow").each(function(){
				price = parseFloat($(this).attr("data-tprice"));
			});		
		}

		$(".lx-list-products table tr td input[name='discount']").trigger("keyup");		
	}*/
});


$(".lx-popup-content").delegate(".lx-add-service-to-order","click",function(){
	isNotEmpty($("#commandsform select[name='service']"));
	isNumber($("#commandsform input[name='qtyservice']"));
	isNumber($("#commandsform input[name='upriceservice']"));
	if(isNotEmpty($("#commandsform select[name='service']"))
	&& isNumber($("#commandsform input[name='qtyservice']"))
	&& isNumber($("#commandsform input[name='upriceservice']"))){
		if($("#commandsform select[name='invoiced']").val() !== ""){
			addServiceToOrder();
		}
		else{
			$(".lx-floating-response").remove();
			window.clearTimeout(timer);
			$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Choisissez un état de facturation !!<i class="material-icons"></i></p></div>');
			$(".lx-floating-response").fadeIn();
			window.setTimeout(function(){
				$(".lx-floating-response").fadeOut();
			},5000);			
		}
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Veuillez remplir les champs en rouge !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);		
	}
});

function getProduct(code){
	$("#commandsform select[name='product'] option[data-code='"+code+"']").prop("selected",true);
	$("#commandsform input[name='product']").val($("#commandsform select[name='product'] option[data-code='"+code+"']").text());
}

function addProductToOrder(){
	if(parseFloat($("#commandsform input[name='qty']").val()) > 0){
		$(".lx-unit-product").text("");
		$(".lx-error-products").html("");
		var el = $(".lx-list-products ul li span[data-id='"+$("#commandsform select[name='product']").val()+"']");
		var price = (parseFloat($("#commandsform input[name='uprice']").val()) * parseFloat($("#commandsform input[name='qty']").val()));
		price = decimalOperation(price,0,"plus");
		if(el.length){
			var qty = decimalOperation(parseFloat($("#commandsform input[name='qty']").val()),parseFloat(el.attr("data-qty")),"plus");
			el.html($("#commandsform select[name='product'] option:selected").attr("data-ref")+' x '+qty+' '+$("#commandsform select[name='product'] option:selected").attr("data-unit"));
			el.attr("data-qty",qty);
			el.attr("data-price",parseFloat(el.attr("data-price")) + (price));
			el.attr("data-bprices",el.attr("data-bprices") + "," + $("#commandsform select[name='product'] option:selected").attr("data-bprice"));
			if($("#commandsform select[name='invoiced']").val() === "Oui"){
				el.next("ins").text((parseFloat(el.attr("data-price")) + (price)).toFixed(2) + " HT (TVA: "+$("#commandsform select[name='product'] option:selected").attr("data-tva")+"'%)");
			}
			else{
				el.next("ins").text((parseFloat(el.attr("data-price")) + (parseFloat($("#commandsform input[name='uprice']").val()) * parseFloat($("#commandsform input[name='qty']").val()))).toFixed(2));
			}
			$(".lx-list-products ul li del input").trigger("keyup");
			$("#commandsform input[name='maindiscount']").trigger("keyup");
		}
		else{
			var html = '<li>';
			html += '<span data-id="'+$("#commandsform select[name='product']").val()+'" data-qty="'+$("#commandsform input[name='qty']").val()+'" data-price="'+decimalOperation(parseFloat($("#commandsform input[name='uprice']").val()),parseFloat($("#commandsform input[name='qty']").val()),"multiply")+'" data-bprice="'+$("#commandsform select[name='product'] option:selected").attr("data-bprice")+'" data-bprices="'+$("#commandsform select[name='product'] option:selected").attr("data-bprice")+'" data-tva="'+$("#commandsform select[name='product'] option:selected").attr("data-tva")+'" data-btva="'+$("#commandsform select[name='product'] option:selected").attr("data-btva")+'" data-depot="'+$("#commandsform select[name='product'] option:selected").attr("data-depot")+'">'+$("#commandsform select[name='product'] option:selected").attr("data-ref")+' x '+$("#commandsform input[name='qty']").val()+' '+$("#commandsform select[name='product'] option:selected").attr("data-unit")+'</span>';
			if($("#commandsform select[name='invoiced']").val() === "Oui"){
				html += '<ins>'+(parseFloat($("#commandsform input[name='uprice']").val()) * (price)).toFixed(2)+' HT (TVA: '+$("#commandsform select[name='product'] option:selected").attr("data-tva")+'%)</ins>';
			}
			else{
				html += '<ins>'+(parseFloat($("#commandsform input[name='uprice']").val()) * parseFloat($("#commandsform input[name='qty']").val())).toFixed(2)+'</ins>';
			}
			html += '<a href="javascript:;"><i class="fa fa-trash-alt"></i></a>';
			html += '<del>Remise:&nbsp;<input type="number" min="0" name="discount" /></del>';
			html += '</li>';
			$(".lx-list-products ul").prepend(html);	
		}
		if($("#commandsform select[name='typediscount']").val() === ""){
			$(".lx-list-products ul li del").css("display","none");
		}
		else{
			$(".lx-list-products ul li del").css("display","inline-block");
		}
		$("#commandsform input[name='allproducts']").val($("#commandsform input[name='allproducts']").val()+","+$("#commandsform select[name='product']").val());
		$("#commandsform input[name='alldepots']").val($("#commandsform input[name='alldepots']").val()+","+$("#commandsform select[name='product'] option:selected").attr("data-depot"));
		$("#commandsform input[name='allqtys']").val($("#commandsform input[name='allqtys']").val()+","+$("#commandsform input[name='qty']").val());
		$("#commandsform input[name='allbprices']").val($("#commandsform input[name='allbprices']").val()+","+$("#commandsform select[name='product'] option:selected").attr("data-bprice"));
		$("#commandsform input[name='allprices']").val($("#commandsform input[name='allprices']").val()+","+$("#commandsform input[name='uprice']").val());
		window.setTimeout(function(){
			$("#commandsform input[name='product']").val("");
			$("#commandsform select[name='product']").val("");
			$("#commandsform input[name='qty']").val("1");
			$("#commandsform input[name='uprice']").val("");
			$("#commandsform label del").text("");			
		},200);
		var price = 0;
		var tva = 0;
		var ttprice = 0;
		$(".lx-list-products ul li").each(function(){
			price += parseFloat($(this).find("> ins").text());
			tva += parseFloat($(this).find("> span").attr("data-price")) - parseFloat($(this).find("> ins").text());
			ttprice += parseFloat($(this).find("> ins").text()) + (parseFloat($(this).find("> span").attr("data-price")) - parseFloat($(this).find("> ins").text()));
		});
		/*$("#commandsform input[name='price']").val(price.toFixed(2));
		$("#commandsform input[name='tva']").val(tva.toFixed(2));
		$("#commandsform input[name='ttcprice']").val((Math.floor(ttprice)).toFixed(2));*/		
		$("#commandsform input[name='cash']").trigger("keyup");
		$("#commandsform input[name='maindiscount']").attr("data-click","true").trigger("keyup");
		calculateProfit();
	}
}

function addServiceToOrder(){
	if(parseFloat($("#commandsform input[name='qtyservice']").val()) > 0){
		$(".lx-unit-service").text("");
		$(".lx-error-products").html("");
		var el = $(".lx-list-products ul li span[data-id='"+$("#commandsform select[name='service']").val()+"']");
		if($("#commandsform select[name='invoiced']").val() === "Oui"){
			var price = (parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val())) / (1+(parseInt($("#commandsform select[name='service'] option:selected").attr("data-tva"))/100));
		}
		else{
			var price = (parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val()));
		}
		price = decimalOperation(price,0,"plus");
		var bprice = parseFloat($("#commandsform select[name='service'] option:selected").attr("data-bprice"));
		if($("#commandsform select[name='invoiced']").val() === "Non"){
			var bprice = parseFloat($("#commandsform select[name='service'] option:selected").attr("data-bprice")) / (1+(parseFloat($("#commandsform select[name='service'] option:selected").attr("data-tva"))/100));
		}
		if(el.length){
			var qty = decimalOperation(parseFloat($("#commandsform input[name='qtyservice']").val()),parseFloat(el.attr("data-qty")),"plus");
			el.html(($("#onlyproduct").val()=="1"?'<b>Service: </b>':'')+$("#commandsform select[name='service'] option:selected").attr("data-ref")+' x '+qty+' '+$("#commandsform select[name='service'] option:selected").attr("data-unit"));
			el.attr("data-qty",qty);
			el.attr("data-price",parseFloat(el.attr("data-price")) + (parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val())));
			el.attr("data-bprices",el.attr("data-bprices") + "," + bprice);
			if($("#commandsform select[name='invoiced']").val() === "Oui"){
				el.next("ins").text(((parseFloat(el.attr("data-price")) + (parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val()))) / (1+(parseInt($("#commandsform select[name='service'] option:selected").attr("data-tva"))/100))).toFixed(2) + " HT (TVA: "+$("#commandsform select[name='service'] option:selected").attr("data-tva")+"'%)");
			}
			else{
				el.next("ins").text((parseFloat(el.attr("data-price")) + (parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val()))).toFixed(2));
			}
			$(".lx-list-products ul li del input").trigger("keyup");
			$("#commandsform input[name='maindiscount']").trigger("keyup");
		}
		else{
			var html = '<li>';
			html += '<span data-id="'+$("#commandsform select[name='service']").val()+'" data-qty="'+$("#commandsform input[name='qtyservice']").val()+'" data-price="'+(parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val()))+'" data-bprice="'+bprice.toFixed(2)+'" data-bprices="'+bprice.toFixed(2)+'" data-tva="'+$("#commandsform select[name='service'] option:selected").attr("data-tva")+'" data-btva="'+$("#commandsform select[name='service'] option:selected").attr("data-btva")+'" data-depot="'+$("#commandsform select[name='service'] option:selected").attr("data-depot")+'">'+($("#onlyproduct").val()=="1"?'<b>Service: </b>':'')+$("#commandsform select[name='service'] option:selected").attr("data-ref")+' x '+$("#commandsform input[name='qtyservice']").val()+' '+$("#commandsform select[name='service'] option:selected").attr("data-unit")+'</span>';
			if($("#commandsform select[name='invoiced']").val() === "Oui"){
				html += '<ins>'+(parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val()) / (1+(parseInt($("#commandsform select[name='service'] option:selected").attr("data-tva"))/100))).toFixed(2)+' HT (TVA: '+$("#commandsform select[name='service'] option:selected").attr("data-tva")+'%)</ins>';
			}
			else{
				html += '<ins>'+(parseFloat($("#commandsform input[name='upriceservice']").val()) * parseFloat($("#commandsform input[name='qtyservice']").val())).toFixed(2)+'</ins>';
			}
			html += '<a href="javascript:;"><i class="fa fa-trash-alt"></i></a>';
			html += '<del>Remise:&nbsp;<input type="number" min="0" name="discount" /></del>';
			html += '</li>';
			$(".lx-list-products ul").prepend(html);	
		}

		if($("#commandsform select[name='typediscount']").val() === ""){
			$(".lx-list-products ul li del").css("display","none");
		}
		else{
			$(".lx-list-products ul li del").css("display","inline-block");
		}

		$("#commandsform input[name='allproducts']").val($("#commandsform input[name='allproducts']").val()+","+$("#commandsform select[name='service']").val());
		$("#commandsform input[name='alldepots']").val($("#commandsform input[name='alldepots']").val()+","+$("#commandsform select[name='service'] option:selected").attr("data-depot"));
		$("#commandsform input[name='allqtys']").val($("#commandsform input[name='allqtys']").val()+","+$("#commandsform input[name='qtyservice']").val());
		$("#commandsform input[name='allbprices']").val($("#commandsform input[name='allbprices']").val()+","+bprice);
		$("#commandsform input[name='allprices']").val($("#commandsform input[name='allprices']").val()+","+$("#commandsform input[name='upriceservice']").val());

		$("#commandsform input[name='service']").val("");
		$("#commandsform select[name='service']").val("");
		$("#commandsform input[name='qtyservice']").val("1");
		$("#commandsform input[name='upriceservice']").val("");
		$("#commandsform label del").text("");

		var price = 0;
		var tva = 0;
		var ttprice = 0;
		$(".lx-list-products ul li").each(function(){
			price += parseFloat($(this).find("> ins").text());
			tva += parseFloat($(this).find("> span").attr("data-price")) - parseFloat($(this).find("> ins").text());
			ttprice += parseFloat($(this).find("> ins").text()) + (parseFloat($(this).find("> span").attr("data-price")) - parseFloat($(this).find("> ins").text()));
		});
		$("#commandsform input[name='price']").val(price.toFixed(2));
		$("#commandsform input[name='tva']").val(tva.toFixed(2));
		$("#commandsform input[name='ttcprice']").val(ttprice.toFixed(2));
		$("#commandsform input[name='cash']").trigger("keyup");
		$("#commandsform input[name='maindiscount']").attr("data-click","true").trigger("keyup");
		calculateProfit();
	}
	else{
		$(".lx-floating-response").remove();
		window.clearTimeout(timer);
		$("body").append('<div class="lx-floating-response"><p class="lx-error"><i class="fa fa-exclamation-triangle"></i> Saisissez une quantité !!<i class="material-icons"></i></p></div>');
		$(".lx-floating-response").fadeIn();
		window.setTimeout(function(){
			$(".lx-floating-response").fadeOut();
		},5000);			
	}
}

function addClientToOrder(){
	$("#commandsform select[name='client']").val($("#commandsform select[name='client'] option:selected").attr("data-id"));
	$("#commandsform input[name='client']").val($("#commandsform select[name='client'] option:selected").attr("data-name"));
	$("#commandsform input[name='ice']").val($("#commandsform select[name='client'] option:selected").attr("data-ice")).trigger("keyup");
	$("#commandsform input[name='phone']").val($("#commandsform select[name='client'] option:selected").attr("data-phone"));
	$("#commandsform input[name='address']").val($("#commandsform select[name='client'] option:selected").attr("data-address")).trigger("keyup");
	$("#commandsform input[name='email']").val($("#commandsform select[name='client'] option:selected").attr("data-email"));
}

$(".lx-popup-content").delegate(".lx-list-products ul li a","click",function(){
	var allproducts = $("#commandsform input[name='allproducts']").val().split(",");
	var alldepots = $("#commandsform input[name='alldepots']").val().split(",");
	var allqtys = $("#commandsform input[name='allqtys']").val().split(",");
	var allbprices = $("#commandsform input[name='allbprices']").val().split(",");
	var allprices = $("#commandsform input[name='allprices']").val().split(",");
	$("#commandsform input[name='allproducts']").val("");
	$("#commandsform input[name='alldepots']").val("");
	$("#commandsform input[name='allqtys']").val("");
	$("#commandsform input[name='allbprices']").val("");
	$("#commandsform input[name='allprices']").val("");
	for(var i=1;i<allproducts.length;i++){
		if(allproducts[i] !== $(this).parent().find("span").attr("data-id")){
			$("#commandsform input[name='allproducts']").val($("#commandsform input[name='allproducts']").val()+","+allproducts[i]);
			$("#commandsform input[name='alldepots']").val($("#commandsform input[name='alldepots']").val()+","+alldepots[i]);
			$("#commandsform input[name='allqtys']").val($("#commandsform input[name='allqtys']").val()+","+allqtys[i]);
			$("#commandsform input[name='allbprices']").val($("#commandsform input[name='allbprices']").val()+","+allbprices[i]);			
			$("#commandsform input[name='allprices']").val($("#commandsform input[name='allprices']").val()+","+allprices[i]);			
		}
		else{
			if($("#commandsform select[name='invoiced']").val() === "Oui"){
				var qty = parseFloat($("#commandsform select[name='product'][value='"+allproducts[i]+"'][bprice='"+allbprices[i]+"']").attr("data-qtyoui"));
				$("#commandsform select[name='product'][value='"+allproducts[i]+"'][bprice='"+allbprices[i]+"']").attr("data-qtyoui",qty + parseFloat(allqtys[i]));
			}
			else if($("#commandsform select[name='invoiced']").val() === "Non"){
				var qty = parseFloat($("#commandsform select[name='product'][value='"+allproducts[i]+"'][bprice='"+allbprices[i]+"']").attr("data-qtynon"));
				$("#commandsform select[name='product'][value='"+allproducts[i]+"'][bprice='"+allbprices[i]+"']").attr("data-qtynon",qty + parseFloat(allqtys[i]));				
			}
		}
	}
	$("#commandsform select[name='depot']").val("").trigger("change");
	$("#commandsform input[name='product']").val("");
	$("#commandsform select[name='product']").val("");
	$("#commandsform input[name='qty']").val("1");
	$("#commandsform input[name='uprice']").val("");
	$("#commandsform label del").text("");
	$(this).parent().remove();

	var price = 0;
	var tva = 0;
	var ttprice = 0;
	$(".lx-list-products ul li").each(function(){
		price += parseFloat($(this).find("> ins").text());
		tva += parseFloat($(this).find("> span").attr("data-price")) - parseFloat($(this).find("> ins").text());
		ttprice += parseFloat($(this).find("> ins").text()) + (parseFloat($(this).find("> span").attr("data-price")) - parseFloat($(this).find("> ins").text()));
	});
	$("#commandsform input[name='price']").val(price.toFixed(2));
	$("#commandsform input[name='tva']").val(tva.toFixed(2));
	$("#commandsform input[name='ttcprice']").val(ttprice.toFixed(2));
	$("#commandsform input[name='cash']").trigger("keyup");
	$("#commandsform input[name='maindiscount']").attr("data-click","true").trigger("keyup");
	
	calculateProfit();
});

$("#commandsform input[name='cash']").on("keyup blur paste change",function(){
	var regex = /^[+-]?\d+(\.\d+)?$/;
	if(regex.test($("#commandsform input[name='cash']").val())){
		$("#commandsform input[name='rest']").val((parseFloat($("#commandsform input[name='ttcprice']").val()) - parseFloat($("#commandsform input[name='cash']").val())).toFixed(2));
	}
	else{
		$("#commandsform input[name='rest']").val((parseFloat($("#commandsform input[name='ttcprice']").val()) - 0).toFixed(2));
	}
});

$("body").delegate(".lx-product-depot > span > i","click",function(){
	$(this).next("div").slideToggle();
	if($(this).attr("class") === "fa fa-plus-square"){ 
		$(this).attr("class","fa fa-minus-square");
	}
	else{
		$(this).attr("class","fa fa-plus-square");
	}
});

$(".lx-table").on('touchmove', onScroll);
$(".lx-table").on('scroll', onScroll); 

function onScroll(){
	$(".lx-table-shadow").css({"height":$(".lx-table:eq(0) table").height()+"px","top":$(".lx-table:eq(0) table").position().top+"px"});
	if(Math.round($(".lx-table").width()) === Math.round($(".lx-table table").width())){
		$(".lx-table-shadow").hide();
	}
	else if($(".lx-table").scrollLeft() === 0){
		$(".lx-table-shadow-left").hide();
	}
	else if(Math.ceil($(".lx-table").scrollLeft()+$(".lx-table").width()) >= $(".lx-table table").width()){
		$(".lx-table-shadow-right").hide();
	}
	else{
		$(".lx-table-shadow").show();
	}
}

$(".lx-add-service").on("click",function(){
	$(".serviceyes").toggle();
	if($(".serviceyes").css("display") === "block"){
		$(this).find("i").attr("class","fa fa-minus");
	}
	else{
		$(this).find("i").attr("class","fa fa-plus");
	}
});


function decimalOperation(a,b,operation){
	if(operation === "plus"){
		return parseFloat((a+b).toFixed(5));
	}
	else if(operation === "minus"){
		return parseFloat((a-b).toFixed(5));
	}
	else if(operation === "multiply"){
		return parseFloat((a*b).toFixed(5));
	}
	else if(operation === "div"){
		return parseFloat((a/b).toFixed(5));
	}
}

$("body").delegate(".lx-transform-avoir","click",function(){
	var id = $(this).attr("data-id");
	var code = $(this).attr("data-code");
	var dateadd = $(this).attr("data-dateadd");
	$(".lx-transform-to-avoir").attr("data-id",id);
	$(".lx-transform-to-avoir").attr("data-code",code);
	$(".lx-transform-to-avoir").attr("data-dateadd",dateadd);
});

$(".lx-transform-to-avoir").on("click",function(){
	var id = $(this).attr("data-id");
	var code = $(this).attr("data-code");
	var dateadd = $(this).attr("data-dateadd");
	var ajaxurl = "ajax.php";
	$.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			id : id,
			code : code,
			dateadd : dateadd,
			action : 'transformtoavoir'
		},
		success : function(response){
			$(".lx-popup-content > a > .material-icons").trigger("click");
			loadAvoirs("1");
		}
	});
});