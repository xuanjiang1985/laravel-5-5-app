$(document).ready(function () {

    $("#ajax-status").click(function(){
        $(this).hide();
    });
    //根据#nav-line > data-treeview > data-treeview-menu 打开侧边栏
    function openSidebar() {
    	if (typeof($("#nav-line").attr("data-treeview")) == "undefined") {
    		return false;
    	}
    	
    	var treeview_line = $("#nav-line").attr("data-treeview");
    	var treeview_menu_line = $("#nav-line").attr("data-treeview-menu");
    	var obj = $(".sidebar-menu").find(".treeview").eq(treeview_line -1);
    	obj.addClass("active menu-open");
    	obj.children(".treeview-menu").find("li").eq(treeview_menu_line - 1).addClass("active");
    }
    openSidebar();
   
});