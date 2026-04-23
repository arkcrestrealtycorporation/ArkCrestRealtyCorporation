// Global Search Functionality
(function() {
    'use strict';
    
    console.log('🔍 Global Search: Initializing...');
    
    // Wait for DOM to be ready
    function initSearch() {
        const searchToggle = document.getElementById('searchToggle');
        const searchBar = document.getElementById('searchBar');
        const searchInput = document.getElementById('globalSearchInput');
        const searchResults = document.getElementById('searchResults');
        const notificationPanel = document.getElementById('notificationPanel');
        
        console.log('🔍 Search elements found:', {
            toggle: !!searchToggle,
            bar: !!searchBar,
            input: !!searchInput,
            results: !!searchResults
        });
        
        if (!searchToggle || !searchBar || !searchInput || !searchResults) {
            console.error('❌ Search elements not found!');
            return;
        }
        
        // Search Data
        const searchData = [
            {
                title: 'Dashboard',
                description: 'View overview and statistics',
                url: '/dashboard',
                icon: 'home'
            },
            {
                title: 'Admin Department',
                description: 'Administrative expenses and requests',
                url: '/departments/admin',
                icon: 'building'
            },
            {
                title: 'Sales & Marketing',
                description: 'Sales department expenses',
                url: '/departments/admin',
                icon: 'building'
            },
            {
                title: 'Human Resources',
                description: 'HR department expenses',
                url: '/departments/admin',
                icon: 'building'
            },
            {
                title: 'Finance Department',
                description: 'Finance expenses and budgets',
                url: '/departments/admin',
                icon: 'building'
            },
            {
                title: 'Executive Department',
                description: 'Executive expenses',
                url: '/departments/admin',
                icon: 'building'
            },
            {
                title: 'Summary Report',
                description: 'Monthly and yearly reports',
                url: '/summary-report',
                icon: 'chart'
            },
            {
                title: 'Settings',
                description: 'System configuration',
                url: '/settings',
                icon: 'settings'
            }
        ];
        
        // Search Icons
        const searchIcons = {
            home: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            building: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
            chart: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            settings: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
            document: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            person: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
            location: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
            calendar: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
        };
        
        // Toggle Search Bar
        searchToggle.addEventListener('click', function(e) {
            console.log('🔍 Search toggle clicked!');
            e.preventDefault();
            e.stopPropagation();
            
            const isVisible = searchBar.classList.contains('show');
            
            if (isVisible) {
                searchBar.classList.remove('show');
                searchResults.classList.remove('show');
                console.log('🔍 Search bar hidden');
            } else {
                searchBar.classList.add('show');
                searchInput.focus();
                console.log('🔍 Search bar shown');
                
                // Close notification panel if open
                if (notificationPanel) {
                    notificationPanel.classList.remove('show');
                }
                // Close notes panel if open
                const np = document.getElementById('notesPanel');
                if (np) np.style.display = 'none';
            }
        });
        
        // Search Input Handler
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            console.log('🔍 Search query:', query);
            
            if (query.length === 0) {
                searchResults.classList.remove('show');
                return;
            }
            
            // Debounce search for 150ms (faster response)
            searchTimeout = setTimeout(function() {
                performSearch(query);
            }, 150);
        });
        
        // Perform Search
        function performSearch(query) {
            try {
                console.log('🔍 Performing API search for:', query);
                
                // Show loading state
                searchResults.innerHTML = `
                    <div style="padding: 20px; text-align: center; color: #6b7280;">
                        <div style="font-size: 13px;">Searching...</div>
                    </div>
                `;
                searchResults.classList.add('show');
                
                // Call API for global search
                fetch('/api/global-search?q=' + encodeURIComponent(query))
                    .then(response => {
                        console.log('🔍 API response status:', response.status);
                        if (!response.ok) {
                            throw new Error('API error: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('🔍 API results:', data.length, 'items');
                        console.log('🔍 First result:', data[0]);
                        displaySearchResults(data, query);
                    })
                    .catch(error => {
                        console.error('❌ API error:', error);
                        searchResults.innerHTML = `
                            <div style="padding: 20px; text-align: center; color: #ef4444;">
                                <div style="font-size: 13px; font-weight: 600;">Search error</div>
                                <div style="font-size: 11px; margin-top: 4px;">${error.message}</div>
                            </div>
                        `;
                    });
                
            } catch (error) {
                console.error('❌ Search error:', error);
                showErrorMessage();
            }
        }
        
        // Search Current Page
        function searchCurrentPage(query) {
            const results = [];
            
            try {
                // Search in tables
                const tables = document.querySelectorAll('table tbody tr');
                console.log('🔍 Searching in', tables.length, 'table rows');
                
                tables.forEach(function(row, index) {
                    if (results.length >= 5) return; // Limit to 5 results
                    
                    try {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(query)) {
                            const cells = row.querySelectorAll('td');
                            if (cells.length > 0) {
                                let title = cells[0].textContent.trim();
                                let description = 'Table row';
                                
                                // Get more context from other cells
                                if (cells.length >= 4) {
                                    const col1 = cells[0].textContent.trim();
                                    const col2 = cells[1].textContent.trim();
                                    const col3 = cells[2].textContent.trim();
                                    
                                    title = col1 + ' - ' + col2;
                                    description = col3;
                                } else if (cells.length > 1) {
                                    description = cells[1].textContent.trim();
                                }
                                
                                results.push({
                                    title: title.substring(0, 50),
                                    description: description.substring(0, 60),
                                    url: '#',
                                    icon: 'document',
                                    row: row
                                });
                            }
                        }
                    } catch (rowError) {
                        console.error('Row error:', rowError);
                    }
                });
                
            } catch (error) {
                console.error('Page search error:', error);
            }
            
            return results;
        }
        
        // Display Search Results
        function displaySearchResults(results, query) {
            if (results.length === 0) {
                searchResults.innerHTML = `
                    <div style="padding: 32px; text-align: center;">
                        <svg style="width: 48px; height: 48px; color: #9ca3af; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <div style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 4px;">No results found</div>
                        <div style="font-size: 12px; color: #6b7280;">Try searching for expenses, departments, or pages</div>
                    </div>
                `;
            } else {
                let html = '';
                
                results.forEach(function(item, index) {
                    const iconSvg = searchIcons[item.icon] || searchIcons.document;
                    const highlightedTitle = highlightText(item.title, query);
                    const highlightedDesc = highlightText(item.description, query);
                    
                    // Add badge for type
                    let badge = '';
                    if (item.type === 'expense') {
                        badge = '<span style="background:#dbeafe;color:#1e40af;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;margin-left:6px;">' + (item.status || 'EXPENSE') + '</span>';
                    } else if (item.type === 'client') {
                        badge = '<span style="background:#ede9fe;color:#5b21b6;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;margin-left:6px;">' + (item.status || 'CLIENT') + '</span>';
                    } else if (item.type === 'trip') {
                        badge = '<span style="background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;margin-left:6px;">' + (item.status || 'TRIP') + '</span>';
                    } else if (item.type === 'department') {
                        badge = '<span style="background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;margin-left:6px;">DEPT</span>';
                    } else if (item.type === 'report') {
                        badge = '<span style="background:#d1fae5;color:#065f46;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;margin-left:6px;">REPORT</span>';
                    } else if (item.type === 'page') {
                        badge = '<span style="background:#f1f5f9;color:#64748b;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:600;margin-left:6px;">PAGE</span>';
                    }
                    
                    // Add amount if available
                    let amountHtml = '';
                    if (item.amount) {
                        amountHtml = '<div style="font-size: 11px; color: #059669; font-weight: 600; margin-top: 2px;">' + item.amount + '</div>';
                    }
                    
                    html += `
                        <div class="search-result-item" data-index="${index}">
                            <div class="search-result-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${iconSvg}
                                </svg>
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">${highlightedTitle}${badge}</div>
                                <div class="search-result-description">${highlightedDesc}</div>
                                ${amountHtml}
                            </div>
                        </div>
                    `;
                });
                
                searchResults.innerHTML = html;
                
                // Add click handlers
                const resultItems = searchResults.querySelectorAll('.search-result-item');
                resultItems.forEach(function(item) {
                    item.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'));
                        const result = results[index];
                        
                        // Store search query in sessionStorage
                        sessionStorage.setItem('searchQuery', query);
                        
                        // Close search
                        searchBar.classList.remove('show');
                        searchResults.classList.remove('show');
                        searchInput.value = '';
                        
                        if (result.highlight_id) {
                            // If we're already on the page, scroll to element
                            if (result.url === window.location.pathname) {
                                scrollAndHighlight(result.highlight_id, query);
                            } else {
                                // Navigate with hash
                                window.location.href = result.url + '#' + result.highlight_id;
                            }
                        } else if (result.url) {
                            // Navigate to page
                            window.location.href = result.url;
                        }
                    });
                });
            }
            
            searchResults.classList.add('show');
        }
        
        // Scroll and highlight element
        function scrollAndHighlight(elementId, searchQuery) {
            setTimeout(function() {
                const element = document.getElementById(elementId);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Highlight the row
                    element.style.backgroundColor = '#fef3c7';
                    element.style.transition = 'background-color 0.3s';
                    
                    // Highlight the specific text that was searched in the row ONLY
                    if (searchQuery) {
                        highlightTextInElement(element, searchQuery);
                    }
                    
                    setTimeout(function() {
                        element.style.backgroundColor = '';
                    }, 4000);
                } else {
                    // Try finding by data attribute
                    const row = document.querySelector('[data-id="' + elementId.replace('expense-', '') + '"]');
                    if (row) {
                        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        row.style.backgroundColor = '#fef3c7';
                        
                        if (searchQuery) {
                            highlightTextInElement(row, searchQuery);
                        }
                        
                        setTimeout(function() {
                            row.style.backgroundColor = '';
                        }, 4000);
                    }
                }
            }, 300);
        }
        
        // Highlight specific text within an element
        function highlightTextInElement(element, searchText) {
            // Highlight in table cells
            const cells = element.querySelectorAll('td');
            
            cells.forEach(function(cell) {
                highlightInNode(cell, searchText);
            });
        }
        
        // Highlight text in a specific node
        function highlightInNode(node, searchText) {
            // Skip if node has input/select/textarea children (to avoid breaking form fields)
            if (node.querySelector('input, select, textarea, button')) {
                return;
            }
            
            const originalHTML = node.innerHTML;
            const originalText = node.textContent;
            const lowerText = originalText.toLowerCase();
            const lowerSearch = searchText.toLowerCase();
            
            if (lowerText.includes(lowerSearch)) {
                // Use regex to find all occurrences (case insensitive)
                const regex = new RegExp('(' + searchText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                
                // Get all text nodes
                const walker = document.createTreeWalker(
                    node,
                    NodeFilter.SHOW_TEXT,
                    null,
                    false
                );
                
                const textNodes = [];
                let currentNode;
                while (currentNode = walker.nextNode()) {
                    if (currentNode.textContent.trim().length > 0) {
                        textNodes.push(currentNode);
                    }
                }
                
                // Highlight each text node
                textNodes.forEach(function(textNode) {
                    const text = textNode.textContent;
                    if (regex.test(text)) {
                        const span = document.createElement('span');
                        span.innerHTML = text.replace(regex, '<mark style="background: #fbbf24; color: #78350f; padding: 2px 4px; border-radius: 3px; font-weight: 600; animation: pulse 0.5s;">$1</mark>');
                        textNode.parentNode.replaceChild(span, textNode);
                    }
                });
                
                // Store original HTML to restore later
                node.setAttribute('data-original-html', originalHTML);
                
                // Remove highlight after 4 seconds
                setTimeout(function() {
                    const original = node.getAttribute('data-original-html');
                    if (original) {
                        node.innerHTML = original;
                        node.removeAttribute('data-original-html');
                    }
                }, 4000);
            }
        }
        
        // Highlight text across the entire page
        function highlightTextInPage(searchText) {
            // Find all text-containing elements on the page
            const selectors = [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 
                'p', 'span', 'div', 'td', 'th', 
                'label', 'a', 'li',
                '.card-title', '.card-text',
                '.stat-value', '.stat-label',
                '.department-name', '.category-name',
                '.metric-value', '.metric-label',
                '.summary-value', '.summary-label',
                '.total-amount', '.expense-amount',
                '.card-body', '.info-card',
                '.stat-card', '.metric-card'
            ];
            
            selectors.forEach(function(selector) {
                const elements = document.querySelectorAll(selector);
                elements.forEach(function(element) {
                    // Skip if element is inside a form input or has contenteditable
                    if (element.closest('input, select, textarea, button, [contenteditable="true"]')) {
                        return;
                    }
                    
                    // Skip if already processed
                    if (element.hasAttribute('data-highlighted')) {
                        return;
                    }
                    
                    const text = element.textContent;
                    if (text.toLowerCase().includes(searchText.toLowerCase())) {
                        highlightInNode(element, searchText);
                        element.setAttribute('data-highlighted', 'true');
                        
                        // Remove marker after highlight is removed
                        setTimeout(function() {
                            element.removeAttribute('data-highlighted');
                        }, 4100);
                    }
                });
            });
        }
        
        // Store search query globally
        let currentSearchQuery = '';
        
        // Check for hash on page load
        if (window.location.hash) {
            const elementId = window.location.hash.substring(1);
            // Try to get search query from sessionStorage
            const storedQuery = sessionStorage.getItem('searchQuery');
            if (storedQuery) {
                scrollAndHighlight(elementId, storedQuery);
                sessionStorage.removeItem('searchQuery');
            } else {
                scrollAndHighlight(elementId);
            }
        }
        
        // Highlight matching text
        function highlightText(text, query) {
            const regex = new RegExp('(' + query + ')', 'gi');
            return text.replace(regex, '<span style="background: #fef3c7; color: #92400e; font-weight: 600; padding: 0 2px; border-radius: 2px;">$1</span>');
        }
        
        // Show error message
        function showErrorMessage() {
            searchResults.innerHTML = `
                <div style="padding: 32px; text-align: center;">
                    <div style="font-size: 14px; font-weight: 600; color: #ef4444; margin-bottom: 4px;">Search temporarily unavailable</div>
                    <div style="font-size: 12px; color: #6b7280;">Please try again</div>
                </div>
            `;
            searchResults.classList.add('show');
        }
        
        // Close search when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-container')) {
                searchBar.classList.remove('show');
                searchResults.classList.remove('show');
            }
        });
        
        // Close search on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchBar.classList.remove('show');
                searchResults.classList.remove('show');
            }
        });
        
        console.log('✅ Global Search: Initialized successfully!');
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
        initSearch();
    }
    
    // Also try after a short delay to ensure everything is loaded
    setTimeout(initSearch, 100);
    setTimeout(initSearch, 500);
    
})();
