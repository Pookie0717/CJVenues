//
// Global init of core components
//

// for dinamically loaded elements
document.addEventListener('DOMContentLoaded', function () {
    // Attach the click event listener to a parent element, e.g., document.body
    document.body.addEventListener('click', function(event) {
        // Find the closest ancestor of the clicked element (or the clicked element itself)
        // that has the attribute data-kt-menu-trigger="click"
        var actionButton = event.target.closest('[data-kt-menu-trigger="click"]');
        
        if (actionButton) {
            KTApp.init();
            KTDrawer.init();
            KTMenu.init();
            KTScroll.init();
            KTSticky.init();
            KTSwapper.init();
            KTToggle.init();
            KTScrolltop.init();
            KTDialer.init();    
            KTImageInput.init();
            KTPasswordMeter.init(); 
            // The actions associated with the button should be triggered by the library/framework.
            // No need to manually dispatch another click event.
        }
    });
});

// Init components
var KTComponents = function () {
    // Public methods
    return {
        init: function () {
            KTApp.init();
			KTDrawer.init();
			KTMenu.init();
			KTScroll.init();
			KTSticky.init();
			KTSwapper.init();
			KTToggle.init();
			KTScrolltop.init();
			KTDialer.init();	
			KTImageInput.init();
			KTPasswordMeter.init();	
        }
    }	
}();

// On document ready
if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", function() {
		KTComponents.init();
	});
 } else {
	KTComponents.init();
 }

 // Init page loader
window.addEventListener("load", function() {
    KTApp.hidePageLoading();
});

// Declare KTApp for Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
	window.KTComponents = module.exports = KTComponents;
}