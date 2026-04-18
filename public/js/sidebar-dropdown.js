// Sidebar Dropdown Toggle
(function() {
    'use strict';
    
    console.log('🔽 Sidebar Dropdown: Loading...');
    
    function initDropdown() {
        const dropdownToggle = document.getElementById('financeDropdownToggle');
        const financeSubmenu = document.getElementById('financeSubmenu');
        
        console.log('🔽 Dropdown toggle element:', dropdownToggle);
        console.log('🔽 Submenu element:', financeSubmenu);
        
        if (!dropdownToggle || !financeSubmenu) {
            console.error('❌ Dropdown elements not found!');
            console.log('Available elements:', {
                toggle: !!dropdownToggle,
                submenu: !!financeSubmenu
            });
            return;
        }
        
        const dropdownArrow = dropdownToggle.querySelector('.dropdown-arrow');
        console.log('🔽 Arrow element:', dropdownArrow);
        
        // Dropdown is CLOSED by default
        financeSubmenu.classList.remove('open');
        if (dropdownArrow) {
            dropdownArrow.classList.remove('open');
        }
        
        // Add click event
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔽 DROPDOWN BUTTON CLICKED!');
            
            const isOpen = financeSubmenu.classList.contains('open');
            console.log('🔽 Current state - isOpen:', isOpen);
            
            if (isOpen) {
                financeSubmenu.classList.remove('open');
                if (dropdownArrow) {
                    dropdownArrow.classList.remove('open');
                }
                console.log('✅ Dropdown CLOSED');
            } else {
                financeSubmenu.classList.add('open');
                if (dropdownArrow) {
                    dropdownArrow.classList.add('open');
                }
                console.log('✅ Dropdown OPENED');
            }
        });
        
        // Test if button is clickable
        dropdownToggle.style.pointerEvents = 'auto';
        dropdownToggle.style.cursor = 'pointer';
        
        console.log('✅ Sidebar dropdown initialized successfully!');
        console.log('✅ Button styles:', {
            pointerEvents: window.getComputedStyle(dropdownToggle).pointerEvents,
            cursor: window.getComputedStyle(dropdownToggle).cursor,
            zIndex: window.getComputedStyle(dropdownToggle).zIndex
        });
    }
    
    // Initialize multiple times to ensure it works
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDropdown);
    } else {
        initDropdown();
    }
    
    setTimeout(initDropdown, 100);
    setTimeout(initDropdown, 500);
    setTimeout(initDropdown, 1000);
    
})();
