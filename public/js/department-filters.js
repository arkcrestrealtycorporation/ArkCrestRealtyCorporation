// Department Expense Filter Functions

function populateYearFilter() {
    const rows = document.querySelectorAll('#tableBody tr');
    const years = new Set();
    
    rows.forEach(row => {
        if (row.cells.length > 0) {
            const dateCell = row.cells[0].textContent.trim();
            if (dateCell && dateCell.includes('-')) {
                const year = dateCell.split('-')[0];
                if (year && year.length === 4) {
                    years.add(year);
                }
            }
        }
    });
    
    const yearFilter = document.getElementById('yearFilter');
    const sortedYears = Array.from(years).sort((a, b) => b - a);
    
    sortedYears.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearFilter.appendChild(option);
    });
}

function applyFilters() {
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    
    if (!monthFilter || !yearFilter) {
        console.error('Filter elements not found');
        return;
    }
    
    const selectedMonth = monthFilter.value;
    const selectedYear = yearFilter.value;
    
    const rows = document.querySelectorAll('#tableBody tr');
    let visibleTotal = 0;
    
    rows.forEach(row => {
        try {
            if (row.cells.length === 0) return;
            
            const dateCell = row.cells[0].textContent.trim();
            if (!dateCell || !dateCell.includes('-')) {
                row.style.display = 'none';
                return;
            }
            
            const [year, month, day] = dateCell.split('-');
            
            if (!year || !month || !day) {
                console.warn('Invalid date format:', dateCell);
                row.style.display = 'none';
                return;
            }
            
            const monthMatch = selectedMonth === 'all' || month === selectedMonth.padStart(2, '0');
            const yearMatch = selectedYear === 'all' || year === selectedYear;
            
            if (monthMatch && yearMatch) {
                row.style.display = '';
                const totalCell = row.querySelector('.total-col');
                if (totalCell) {
                    const totalText = totalCell.textContent.replace('₱ ', '').replace(/,/g, '').trim();
                    const amount = parseFloat(totalText) || 0;
                    visibleTotal += amount;
                }
            } else {
                row.style.display = 'none';
            }
        } catch (error) {
            console.error('Error parsing date:', row.cells[0].textContent, error);
            row.style.display = 'none';
        }
    });
    
    updateDisplays(visibleTotal);
    updateURL(selectedMonth, selectedYear);
}

function updateDisplays(visibleTotal) {
    const currentBudget = parseFloat(document.getElementById('allowableBudget').value) || 0;
    const remaining = currentBudget - visibleTotal;
    
    // Update grand total
    const grandTotalElement = document.getElementById('grandTotal');
    if (grandTotalElement) {
        grandTotalElement.textContent = '₱ ' + visibleTotal.toLocaleString('en-US', {minimumFractionDigits: 2});
    }
    
    // Update remaining budget
    const remainingDisplay = document.getElementById('remainingDisplay');
    if (remainingDisplay) {
        remainingDisplay.textContent = '₱ ' + remaining.toLocaleString('en-US', {minimumFractionDigits: 2});
        
        // Update color based on remaining
        if (remaining < 0) {
            remainingDisplay.style.color = '#e74c3c';
        } else {
            remainingDisplay.style.color = '#27ae60';
        }
    }
}

function updateURL(month, year) {
    const url = new URL(window.location);
    
    if (month === 'all') {
        url.searchParams.delete('month');
    } else {
        url.searchParams.set('month', month);
    }
    
    if (year === 'all') {
        url.searchParams.delete('year');
    } else {
        url.searchParams.set('year', year);
    }
    
    window.history.replaceState({}, '', url);
}

function initializeFilters() {
    const urlParams = new URLSearchParams(window.location.search);
    let month = urlParams.get('month') || 'all';
    let year = urlParams.get('year') || 'all';
    
    // Validate month
    if (month !== 'all') {
        const monthNum = parseInt(month);
        if (isNaN(monthNum) || monthNum < 1 || monthNum > 12) {
            month = 'all';
        }
    }
    
    // Validate year
    if (year !== 'all') {
        const yearNum = parseInt(year);
        if (isNaN(yearNum) || year.length !== 4) {
            year = 'all';
        }
    }
    
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    
    if (monthFilter) monthFilter.value = month;
    if (yearFilter) yearFilter.value = year;
    
    applyFilters();
}

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', () => {
    populateYearFilter();
    initializeFilters();
    
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    
    if (monthFilter) {
        monthFilter.addEventListener('change', applyFilters);
    }
    
    if (yearFilter) {
        yearFilter.addEventListener('change', applyFilters);
    }
});
