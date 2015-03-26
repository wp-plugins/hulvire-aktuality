var previousDivToToggle = false;
var previousMoreLink = false;
var previousPostId;

function toggleDiv(divToToggle,moreLink,postId) {

       if (previousMoreLink && previousDivToToggle && (previousDivToToggle != divToToggle)){
           var isPreviousOpen = (jQuery("#"+previousMoreLink).text() == 'Zobraz menej');
           if (isPreviousOpen){
               jQuery("#"+previousDivToToggle).hide("normal");
               jQuery("#"+previousMoreLink).text('Zobraz viac');
			jQuery('#thumbFor'+previousPostId).removeClass("lupa-visible"); //in case it has more thmubs, remove the hint class
           }
       }

       // continue - hide or show current link
       jQuery("#"+divToToggle).toggle('normal');
       var n = jQuery("#"+moreLink).text();
	if (n=="Zobraz viac") {
		// rozbalujeme ponuku
		jQuery("#"+moreLink).text("Zobraz menej");

		// pridaj lupu k obrazku ak je tam viac obrazkov

		var items = jQuery('a[rel="lightbox['+postId+']"]');
		if(items.length > 1){
			jQuery('#thumbFor'+postId).addClass("lupa-visible");
		}
		
   	}
	else 
	{
		jQuery("#"+moreLink).text("Zobraz viac"); 
		jQuery('#thumbFor'+postId).removeClass("lupa-visible");
	}

       previousDivToToggle = divToToggle;
	previousMoreLink    = moreLink;
	previousPostId      = postId;

}// function toggle div

jQuery(document).ready(function($) {
	
	$(".more_img a img").remove();

});

