// Sidebar Toggle - Standalone
(function() {
    'use strict';
    
    console.log('🔧 Sidebar Toggle: Loading...');
    
    function initSidebarToggle() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        
        console.log('🔧 Elements found:', {
            sidebar: !!sidebar,
            toggleBtn: !!toggleBtn,
            toggleIcon: !!toggleIcon
        });
        
        if (!sidebar || !toggleBtn) {
            console.error('❌ Sidebar or toggle button not found!');
            return;
        }
        
        // Make sure sidebar starts expanded
        sidebar.classList.add('sidebar-expanded');
        sidebar.classList.remove('sidebar-collapsed');
        
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔧 Toggle button clicked!');
            
            const isExpanded = sidebar.classList.contains('sidebar-expanded');
            console.log('🔧 Current state: ' + (isExpanded ? 'EXPANDED' : 'COLLAPSED'));
            
            if (isExpanded) {
                // Collapse it
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                sidebar.style.width = '70px';
                
                if (toggleIcon) {
                    toggleIcon.style.transform = 'rotate(180deg)';
                    toggleIcon.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                }
                
                console.log('✅ Sidebar COLLAPSED');
            } else {
                // Expand it
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                sidebar.style.width = '260px';
                
                if (toggleIcon) {
                    toggleIcon.style.transform = 'rotate(0deg)';
                    toggleIcon.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                }
                
                console.log('✅ Sidebar EXPANDED');
            }
            
            console.log('🔧 New classes:', sidebar.className);
            console.log('🔧 New width:', sidebar.style.width);
        });
        
        console.log('✅ Sidebar toggle initialized successfully!');
    }
    
    // Try multiple times to ensure it loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarToggle);
    } else {
        initSidebarToggle();
    }
    
    setTimeout(initSidebarToggle, 100);
    setTimeout(initSidebarToggle, 500);
    
})();
