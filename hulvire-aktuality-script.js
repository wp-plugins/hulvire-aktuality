var previousDivToToggle = false;
var previousMoreLink = false;
var previousPostId;

function toggleDiv(divToToggle,moreLink,postId) {
	
	var viacmenej = jQuery(".textRight a").attr("title");
	viacmenej = viacmenej.split("|");

       if (previousMoreLink && previousDivToToggle && (previousDivToToggle != divToToggle)){
           var isPreviousOpen = (jQuery("#"+previousMoreLink).text() == viacmenej[0]);
           if (isPreviousOpen){
               jQuery("#"+previousDivToToggle).hide("normal");
               jQuery("#"+previousMoreLink).text(viacmenej[1]);
			jQuery('#thumbFor'+previousPostId).removeClass("lupa-visible"); //in case it has more thmubs, remove the hint class
           }
       }

       // continue - hide or show current link
       jQuery("#"+divToToggle).toggle('normal');
       var n = jQuery("#"+moreLink).text();
	if (n==viacmenej[1]) {
		// rozbalujeme ponuku
		jQuery("#"+moreLink).text(viacmenej[0]);

		// pridaj lupu k obrazku ak je tam viac obrazkov

		var items = jQuery('a[rel="lightbox['+postId+']"]');
		if(items.length > 1){
			jQuery('#thumbFor'+postId).addClass("lupa-visible");
		}
		
   	}
	else 
	{
		jQuery("#"+moreLink).text(viacmenej[1]); 
		jQuery('#thumbFor'+postId).removeClass("lupa-visible");
	}

       previousDivToToggle = divToToggle;
	previousMoreLink    = moreLink;
	previousPostId      = postId;

}// function toggle div

jQuery(document).ready(function($) {
	
	$(".more_img a img").remove();

});

