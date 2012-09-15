$(document).ready(function() {
	$(".iframebox").colorbox({width:"75%", height:"75%", iframe:true});
	$(".minigallery a").colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" });// {'hideOnContentClick': true,'overlayShow':true,'overlayOpacity':0.75,'overlayColor':"#000" });			
	$("article a[href$='.jpg']:not(.nocolorbox) img").parent().colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" });// {'hideOnContentClick': true,'overlayShow':true,'overlayOpacity':0.75,'overlayColor':"#000" });
	$("#sidebar a[href$='.jpg']:not(.nocolorbox) img").parent().colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" });// {'hideOnContentClick': true,'overlayShow':true,'overlayOpacity':0.75,'overlayColor':"#000" });

	$("article a.colorbox").colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" });
	$("article a.fancybox").colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" });
	$("article a.thickbox").colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" }); 
    $("article a.fancybox_inline").colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" }); 
    $("article a.fancybox_iframe").colorbox({current: "zdjęcie {current} z {total}", previous: "poprzednie",next: "następne",close: "zamknij" }); 
});
