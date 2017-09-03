$(function(){
	//top
    $(window).scroll(function(){  
                if ($(window).scrollTop()>200){  
                    $("#back-to-top").show();  
                }  
                else  
                {  
                    $("#back-to-top").css("display","none");  
                }  
            });
    		//back to top
            $("#back-to-top").click(function(){  
                $('body,html').animate({scrollTop:0},200);  
                return false;  
            });
     });