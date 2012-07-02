//
// avoids jQuery/Prototype conflicts in call
//

//$j=jQuery.noConflict();

$j(document).ready(function(){
	
	//Sidebar Accordion Menu:
		
		$j("#main-nav li ul").hide(); // Hide all sub menus
		$j("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu
		
		$j("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
			function () {
				$j(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$j(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				return false;
			}
		);
		
		$j("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
			function () {
				window.location.href=(this.href); // Just open the link instead of a sub menu
				return false;
			}
		); 

    // Sidebar Accordion Menu Hover Effect:
		
		$j("#main-nav li .nav-top-item").hover(
			function () {
				$j(this).stop().animate({ paddingRight: "25px" }, 400);
			}, 
			function () {
				$j(this).stop().animate({ paddingRight: "15px" });
			}
		);
		/*
		$j("#main-nav li .tabChat").hover(
				function () {
					$j(this).find('img').animate({ left: "40px" }, 400, function() {
						jQuery(this).css("left", "40px");
						
					});
				}, 
				function () {
					$j(this).find('img').animate({ left: "50px" }, 400, function() {
						jQuery(this).css("left", "50px");
						
					});
				}
			);
			*/
    //Minimize Content Box
		
		$j(".content-box-header h3").css({ "cursor":"s-resize" }); // Give the h3 in Content Box Header a different cursor
		$j(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
		$j(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"
		
		$j(".content-box-header h3").click( // When the h3 is clicked...
			function () {
			  $j(this).parent().next().toggle(); // Toggle the Content Box
			  $j(this).parent().parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
			  $j(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
			}
		);

    // Content box tabs:
		
		$j('.content-box .content-box-content div.tab-content').hide(); // Hide the content divs
		$j('ul.content-box-tabs li a.default-tab').addClass('current'); // Add the class "current" to the default tab
		$j('.content-box-content div.default-tab').show(); // Show the div with class "default-tab"
		
		$j('.content-box ul.content-box-tabs li a').click( // When a tab is clicked...
			function() { 
				$j(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$j(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $j(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				$j(currentTab).siblings().hide(); // Hide all content divs
				$j(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false; 
			}
		);

    //Close button:
		
		$j(".close").click(
			function () {
				$j(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$j(this).slideUp(400);
				});
				return false;
			}
		);

    // Alternating table rows:
		
		$j('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows

    // Check all checkboxes when the one in a table head is checked:
		
		$j('.check-all').click(
			function(){
				$j(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $j(this).is(':checked'));   
			}
		);

    // Initialise Facebox Modal window:
		
		//$j('a[rel*=modal]').facebox(); // Applies modal window to any link with attribute rel="modal"

    // Initialise jQuery WYSIWYG:
		
		//$j(".wysiwyg").wysiwyg(); // Applies WYSIWYG editor to any textarea with the class "wysiwyg"
		
		
		$j(".switchAccountLink").click(function(){
			changeAccount($j(this).attr('href'));
			return false;
		});
});
  
  
  