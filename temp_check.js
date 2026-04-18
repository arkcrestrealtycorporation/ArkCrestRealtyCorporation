
const categories = @json($categories);

// Save and restore scroll position of page-content
window.addEventListener('beforeunload', function() {
    const pageContent = document.querySelector('.page-content');
    if (pageContent) {
        sessionStorage.setItem('scrollPos', pageContent.scrollTop);
    }
});

window.addEventListener('load', function() {
    const scrollPos = sessionStorage.getItem('scrollPos');
    if (scrollPos) {
        const pageContent = document.querySelector('.page-content');
        if (pageContent) {
            pageContent.scrollTop = parseInt(scrollPos);
        }
        sessionStorage.removeItem('scrollPos');
    }
});

// Toast Notification Function
let deleteConfirmId = null;

function showToast(type, title, message, callback) {
    const toast = document.getElementById('toastNotification');
    const icon = document.getElementById('toastIcon');
    const titleEl = document.getElementById('toastTitle');
    const messageEl = document.getElementById('toastMessage');
    
    const icons = {
        success: 'âœ“',
        error: 'âœ•',
        warning: 'âš ',
        info: 'â„¹',
        confirm: '?'
    };
    
    icon.textContent = icons[type] || icons.info;
    titleEl.textContent = title;
    messageEl.textContent = message;
    
    toast.classList.remove('success', 'error', 'warning', 'info', 'confirm', 'hiding');
    toast.classList.add(type);
    toast.classList.add('show');
    
    if (type !== 'confirm') {
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => {
                toast.classList.remove('show', 'hiding');
                if (callback) callback();
            }, 300);
        }, 5000);
    }
}

function showConfirm(title, message, onConfirm) {
    const toast = document.getElementById('toastNotification');
    const icon = document.getElementById('toastIcon');
    const titleEl = document.getElementById('toastTitle');
    const messageEl = document.getElementById('toastMessage');
    
    icon.textContent = '?';
    titleEl.textContent = title;
    messageEl.innerHTML = message + '<div class="confirm-buttons"><button class="btn-confirm-yes" onclick="confirmYes()">Yes</button><button class="btn-confirm-no" onclick="confirmNo()">No</button></div>';
    
    toast.classList.remove('success', 'error', 'warning', 'info', 'confirm', 'hiding');
    toast.classList.add('confirm');
    toast.classList.add('show');
    
    window.confirmCallback = onConfirm;
}

function confirmYes() {
    const toast = document.getElementById('toastNotification');
    toast.classList.add('hiding');
    setTimeout(() => {
        toast.classList.remove('show', 'hiding');
        if (window.confirmCallback) {
            window.confirmCallback();
            window.confirmCallback = null;
        }
    }, 300);
}

function confirmNo() {
    const toast = document.getElementById('toastNotification');
    toast.classList.add('hiding');
    setTimeout(() => {
        toast.classList.remove('show', 'hiding');
        window.confirmCallback = null;
    }, 300);
}

// Helper function to close all dropdowns
function closeAllDropdowns() {
    document.getElementById('requestorDropdown').style.display = 'none';
    document.getElementById('departmentDropdown').style.display = 'none';
    document.getElementById('categoryDropdown').style.display = 'none';
    
    const editRequestorDropdown = document.getElementById('editRequestorDropdown');
    const editDepartmentDropdown = document.getElementById('editDepartmentDropdown');
    const editCategoryDropdown = document.getElementById('editCategoryDropdown');
    
    if (editRequestorDropdown) editRequestorDropdown.style.display = 'none';
    if (editDepartmentDropdown) editDepartmentDropdown.style.display = 'none';
    if (editCategoryDropdown) editCategoryDropdown.style.display = 'none';
}

// Requestor Name Combobox
function toggleRequestorDropdown() {
    const dropdown = document.getElementById('requestorDropdown');
    const isVisible = dropdown.style.display === 'block';
    closeAllDropdowns();
    if (!isVisible) {
        const items = dropdown.getElementsByClassName('dropdown-item');
        Array.from(items).forEach(item => item.style.display = 'block');
        dropdown.style.display = 'block';
    }
}

function selectRequestor(value) {
    document.getElementById('requestor_name').value = value;
    closeAllDropdowns();
}

function filterRequestors(searchText) {
    closeAllDropdowns();
    const dropdown = document.getElementById('requestorDropdown');
    const items = dropdown.getElementsByClassName('dropdown-item');
    
    if (searchText === '') {
        Array.from(items).forEach(item => item.style.display = 'block');
    } else {
        Array.from(items).forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchText.toLowerCase()) ? 'block' : 'none';
        });
    }
    dropdown.style.display = 'block';
}

// Department Combobox
function toggleDepartmentDropdown() {
    const dropdown = document.getElementById('departmentDropdown');
    const isVisible = dropdown.style.display === 'block';
    closeAllDropdowns();
    if (!isVisible) {
        const items = dropdown.getElementsByClassName('dropdown-item');
        Array.from(items).forEach(item => item.style.display = 'block');
        dropdown.style.display = 'block';
    }
}

function selectDepartment(value) {
    document.getElementById('department').value = value;
    updateCategoryDropdown(value);
    closeAllDropdowns();
}

function filterDepartments(searchText) {
    closeAllDropdowns();
    const dropdown = document.getElementById('departmentDropdown');
    const items = dropdown.getElementsByClassName('dropdown-item');
    
    if (searchText === '') {
        Array.from(items).forEach(item => item.style.display = 'block');
    } else {
        Array.from(items).forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchText.toLowerCase()) ? 'block' : 'none';
        });
    }
    dropdown.style.display = 'block';
}

// Category Combobox
function toggleCategoryDropdown() {
    const dropdown = document.getElementById('categoryDropdown');
    const isVisible = dropdown.style.display === 'block';
    closeAllDropdowns();
    if (!isVisible) {
        const items = dropdown.getElementsByClassName('dropdown-item');
        Array.from(items).forEach(item => item.style.display = 'block');
        dropdown.style.display = 'block';
    }
}

function selectCategory(value) {
    document.getElementById('category').value = value;
    closeAllDropdowns();
}

function filterCategories(searchText) {
    closeAllDropdowns();
    const dropdown = document.getElementById('categoryDropdown');
    const department = document.getElementById('department').value;
    const defaultCats = department && categories[department] ? categories[department] : [];
    dropdown.innerHTML = '';
    
    const filtered = defaultCats.filter(cat => 
        cat.toLowerCase().includes(searchText.toLowerCase())
    );
    
    if (filtered.length === 0) {
        dropdown.innerHTML = '<div class="dropdown-item" style="color: #999;">No matches found</div>';
    } else {
        filtered.forEach(cat => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.textContent = cat;
            item.onclick = function() { selectCategory(cat); };
            dropdown.appendChild(item);
        });
    }
    dropdown.style.display = 'block';
}

function updateCategoryDropdown(dept) {
    const dropdown = document.getElementById('categoryDropdown');
    dropdown.innerHTML = '';
    
    if (dept && categories[dept]) {
        categories[dept].forEach(cat => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.textContent = cat;
            item.onclick = function() { selectCategory(cat); };
            dropdown.appendChild(item);
        });
    } else {
        dropdown.innerHTML = '<div class="dropdown-item">Select Department First</div>';
    }
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.combobox-wrapper')) {
        closeAllDropdowns();
    }
});

// Edit Modal Combobox Functions
function toggleEditRequestorDropdown() {
    const dropdown = document.getElementById('editRequestorDropdown');
    const isVisible = dropdown.style.display === 'block';
    closeAllDropdowns();
    if (!isVisible) {
        const items = dropdown.getElementsByClassName('dropdown-item');
        Array.from(items).forEach(item => item.style.display = 'block');
        dropdown.style.display = 'block';
    }
}

function selectEditRequestor(value) {
    document.getElementById('edit_requestor_name').value = value;
    closeAllDropdowns();
}

function filterEditRequestors(searchText) {
    closeAllDropdowns();
    const dropdown = document.getElementById('editRequestorDropdown');
    const items = dropdown.getElementsByClassName('dropdown-item');
    
    if (searchText === '') {
        Array.from(items).forEach(item => item.style.display = 'block');
    } else {
        Array.from(items).forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchText.toLowerCase()) ? 'block' : 'none';
        });
    }
    dropdown.style.display = 'block';
}

function toggleEditDepartmentDropdown() {
    const dropdown = document.getElementById('editDepartmentDropdown');
    const isVisible = dropdown.style.display === 'block';
    closeAllDropdowns();
    if (!isVisible) {
        const items = dropdown.getElementsByClassName('dropdown-item');
        Array.from(items).forEach(item => item.style.display = 'block');
        dropdown.style.display = 'block';
    }
}

function selectEditDepartment(value) {
    document.getElementById('edit_department').value = value;
    updateEditCategoryDropdown(value);
    closeAllDropdowns();
}

function filterEditDepartments(searchText) {
    closeAllDropdowns();
    const dropdown = document.getElementById('editDepartmentDropdown');
    const items = dropdown.getElementsByClassName('dropdown-item');
    
    if (searchText === '') {
        Array.from(items).forEach(item => item.style.display = 'block');
    } else {
        Array.from(items).forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchText.toLowerCase()) ? 'block' : 'none';
        });
    }
    dropdown.style.display = 'block';
}

function toggleEditCategoryDropdown() {
    const dropdown = document.getElementById('editCategoryDropdown');
    const isVisible = dropdown.style.display === 'block';
    closeAllDropdowns();
    if (!isVisible) {
        const items = dropdown.getElementsByClassName('dropdown-item');
        Array.from(items).forEach(item => item.style.display = 'block');
        dropdown.style.display = 'block';
    }
}

function selectEditCategory(value) {
    document.getElementById('edit_category').value = value;
    closeAllDropdowns();
}

function filterEditCategories(searchText) {
    closeAllDropdowns();
    const dropdown = document.getElementById('editCategoryDropdown');
    const items = dropdown.getElementsByClassName('dropdown-item');
    
    if (searchText === '') {
        Array.from(items).forEach(item => item.style.display = 'block');
    } else {
        Array.from(items).forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchText.toLowerCase()) ? 'block' : 'none';
        });
    }
    dropdown.style.display = 'block';
}

function updateEditCategoryDropdown(dept) {
    const dropdown = document.getElementById('editCategoryDropdown');
    dropdown.innerHTML = '';
    
    if (dept && categories[dept]) {
        categories[dept].forEach(cat => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.textContent = cat;
            item.onclick = function() { selectEditCategory(cat); };
            dropdown.appendChild(item);
        });
    } else {
        dropdown.innerHTML = '<div class="dropdown-item">Select Department First</div>';
    }
}

// Auto-calculate Amount Returned
function calculateAmountReturned() {
    const requestedAmount = parseFloat(document.getElementById('requested_amount').value) || 0;
    const totalExpenses = parseFloat(document.getElementById('total_expenses').value) || 0;
    const amountReturned = requestedAmount - totalExpenses;
    document.getElementById('amount_returned').value = amountReturned >= 0 ? amountReturned.toFixed(2) : '0.00';
}

document.getElementById('requested_amount').addEventListener('input', calculateAmountReturned);
document.getElementById('total_expenses').addEventListener('input', calculateAmountReturned);

function calculateEditAmountReturned() {
    const requestedAmount = parseFloat(document.getElementById('edit_requested_amount').value) || 0;
    const totalExpenses = parseFloat(document.getElementById('edit_total_expenses').value) || 0;
    const amountReturned = requestedAmount - totalExpenses;
    document.getElementById('edit_amount_returned').value = amountReturned >= 0 ? amountReturned.toFixed(2) : '0.00';
}

document.getElementById('edit_requested_amount').addEventListener('input', calculateEditAmountReturned);
document.getElementById('edit_total_expenses').addEventListener('input', calculateEditAmountReturned);

// Add request
document.getElementById('addRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        requestor_name: document.getElementById('requestor_name').value.trim(),
        department: document.getElementById('department').value.trim(),
        category: document.getElementById('category').value.trim(),
        date_requested: document.getElementById('date_requested').value || null,
        requested_amount: parseFloat(document.getElementById('requested_amount').value) || 0,
        status: document.getElementById('status').value,
        date_released: document.getElementById('date_released').value || null,
        total_expenses: document.getElementById('total_expenses').value ? parseFloat(document.getElementById('total_expenses').value) : null,
        amount_returned: document.getElementById('amount_returned').value ? parseFloat(document.getElementById('amount_returned').value) : null,
        date_of_amount_returned: document.getElementById('date_of_amount_returned').value || null
    };
    
    fetch('/api/departmental-expenses', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Error adding request');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('success', 'Success', 'Request added successfully!');
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', error.message || 'Error adding request');
    });
});

// Edit request
function editRequest(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const cells = row.cells;
    
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_control_number').value = cells[0].textContent;
    document.getElementById('edit_requestor_name').value = cells[1].textContent;
    document.getElementById('edit_department').value = cells[2].textContent;
    
    updateEditCategoryDropdown(cells[2].textContent);
    
    setTimeout(() => {
        document.getElementById('edit_category').value = cells[3].textContent;
    }, 100);
    
    const dateRequested = cells[4].textContent.trim();
    if (dateRequested !== '-') {
        const [month, day, year] = dateRequested.split('/');
        document.getElementById('edit_date_requested').value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    } else {
        document.getElementById('edit_date_requested').value = '';
    }
    
    document.getElementById('edit_requested_amount').value = cells[5].textContent.replace('â‚± ', '').replace(/,/g, '');
    document.getElementById('edit_status').value = cells[6].querySelector('.status-badge').textContent.trim();
    
    if (cells[7].textContent !== '-') {
        const dateReleased = cells[7].textContent.trim();
        const [month, day, year] = dateReleased.split('/');
        document.getElementById('edit_date_released').value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    } else {
        document.getElementById('edit_date_released').value = '';
    }
    
    if (cells[8].textContent !== '-') {
        document.getElementById('edit_total_expenses').value = cells[8].textContent.replace('â‚± ', '').replace(/,/g, '');
    } else {
        document.getElementById('edit_total_expenses').value = '';
    }
    
    if (cells[9].textContent !== '-') {
        document.getElementById('edit_amount_returned').value = cells[9].textContent.replace('â‚± ', '').replace(/,/g, '');
    } else {
        document.getElementById('edit_amount_returned').value = '';
    }
    
    if (cells[10].textContent !== '-') {
        const dateReturned = cells[10].textContent.trim();
        const [month, day, year] = dateReturned.split('/');
        document.getElementById('edit_date_of_amount_returned').value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    } else {
        document.getElementById('edit_date_of_amount_returned').value = '';
    }
    
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// View request
function viewRequest(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const cells = row.cells;
    
    document.getElementById('view_control_number').textContent = cells[0].textContent;
    document.getElementById('view_requestor_name').textContent = cells[1].textContent;
    document.getElementById('view_department').textContent = cells[2].textContent;
    document.getElementById('view_category').textContent = cells[3].textContent;
    document.getElementById('view_date_requested').textContent = cells[4].textContent;
    document.getElementById('view_requested_amount').textContent = cells[5].textContent;
    
    const statusBadge = cells[6].querySelector('.status-badge').cloneNode(true);
    const statusContainer = document.getElementById('view_status');
    statusContainer.innerHTML = '';
    statusContainer.appendChild(statusBadge);
    
    document.getElementById('view_date_released').textContent = cells[7].textContent;
    document.getElementById('view_total_expenses').textContent = cells[8].textContent;
    document.getElementById('view_amount_returned').textContent = cells[9].textContent;
    document.getElementById('view_date_of_amount_returned').textContent = cells[10].textContent;
    
    document.getElementById('viewModal').style.display = 'block';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

// Budget Modal Functions
function openBudgetModal(deptId, deptName, currentBudget, totalExpenses) {
    document.getElementById('budget_dept_id').value = deptId;
    document.getElementById('budget_dept_name').value = deptName;
    document.getElementById('budget_amount').value = currentBudget;
    document.getElementById('budget_total_expenses').value = 'â‚± ' + parseFloat(totalExpenses).toLocaleString('en-US', {minimumFractionDigits: 2});
    
    const remaining = currentBudget - totalExpenses;
    document.getElementById('budget_remaining').value = 'â‚± ' + parseFloat(remaining).toLocaleString('en-US', {minimumFractionDigits: 2});
    
    document.getElementById('budgetModal').style.display = 'block';
}

function closeBudgetModal() {
    document.getElementById('budgetModal').style.display = 'none';
}

function calculateRemainingBudget() {
    const allowableBudget = parseFloat(document.getElementById('budget_amount').value) || 0;
    const totalExpensesText = document.getElementById('budget_total_expenses').value.replace('â‚± ', '').replace(/,/g, '');
    const totalExpenses = parseFloat(totalExpensesText) || 0;
    const remaining = allowableBudget - totalExpenses;
    
    document.getElementById('budget_remaining').value = 'â‚± ' + parseFloat(remaining).toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Update Budget
document.getElementById('budgetUpdateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const deptId = document.getElementById('budget_dept_id').value;
    const newBudget = document.getElementById('budget_amount').value;
    
    fetch(`/api/departments/${deptId}/budget`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            allowable_budget: newBudget
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Success', 'Budget updated successfully!');
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Error updating budget');
    });
});

// Update request
document.getElementById('editRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('edit_id').value;
    const formData = {
        control_number: document.getElementById('edit_control_number').value.trim(),
        requestor_name: document.getElementById('edit_requestor_name').value.trim(),
        department: document.getElementById('edit_department').value.trim(),
        category: document.getElementById('edit_category').value.trim(),
        date_requested: document.getElementById('edit_date_requested').value || null,
        requested_amount: parseFloat(document.getElementById('edit_requested_amount').value) || 0,
        status: document.getElementById('edit_status').value,
        date_released: document.getElementById('edit_date_released').value || null,
        total_expenses: document.getElementById('edit_total_expenses').value ? parseFloat(document.getElementById('edit_total_expenses').value) : null,
        amount_returned: document.getElementById('edit_amount_returned').value ? parseFloat(document.getElementById('edit_amount_returned').value) : null,
        date_of_amount_returned: document.getElementById('edit_date_of_amount_returned').value || null
    };
    
    fetch(`/api/departmental-expenses/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Error updating request');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('success', 'Success', 'Request updated successfully!');
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', error.message || 'Error updating request');
    });
});

// Delete request
function deleteRequest(id) {
    showConfirm('Confirm Delete', 'Are you sure you want to delete this request?', function() {
        fetch(`/api/departmental-expenses/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Success', 'Request deleted successfully!');
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error', 'Error deleting request');
        });
    });
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        closeEditModal();
    }
}

// Simple Table Search - Multiple words support
const searchInput = document.getElementById('tableSearch');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#requestsTableBody tr');
        
        if (searchText.length > 0) {
            const searchWords = searchText.split(/\s+/).filter(word => word.length > 0);
            
            let visibleCount = 0;
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const allWordsFound = searchWords.every(word => text.includes(word));
                
                if (allWordsFound) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
        } else {
            applyMonthYearFilters();
        }
        
        checkNoResults();
    });
}

// Month/Year Filter Function
function applyMonthYearFilters() {
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    
    if (!monthFilter || !yearFilter) {
        return;
    }
    
    const selectedMonth = monthFilter.value;
    const selectedYear = yearFilter.value;
    
    localStorage.setItem('expensesMonthFilter', selectedMonth);
    localStorage.setItem('expensesYearFilter', selectedYear);
    
    const rows = document.querySelectorAll('#requestsTableBody tr');
    
    rows.forEach(row => {
        if (row.cells.length === 0) {
            row.style.display = 'none';
            return;
        }
        
        const dateCell = row.cells[4].textContent.trim();
        
        if (dateCell === '-' || !dateCell) {
            row.style.display = 'none';
            return;
        }
        
        const [month, day, year] = dateCell.split('/');
        
        if (!month || !year) {
            row.style.display = 'none';
            return;
        }
        
        const monthMatch = selectedMonth === 'all' || month === selectedMonth.padStart(2, '0');
        const yearMatch = selectedYear === 'all' || year === selectedYear;
        
        if (monthMatch && yearMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    checkNoResults();
}

// Populate month filter from table data
function populateMonthFilter() {
    const rows = document.querySelectorAll('#requestsTableBody tr');
    const months = new Set();
    
    rows.forEach(row => {
        if (row.cells.length > 4) {
            const dateCell = row.cells[4].textContent.trim();
            if (dateCell && dateCell !== '-' && dateCell.includes('/')) {
                const parts = dateCell.split('/');
                if (parts.length === 3) {
                    const month = parts[0];
                    if (month && month.length <= 2) {
                        months.add(month);
                    }
                }
            }
        }
    });
    
    const monthFilter = document.getElementById('monthFilter');
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                        'July', 'August', 'September', 'October', 'November', 'December'];
    
    const sortedMonths = Array.from(months).sort((a, b) => parseInt(a) - parseInt(b));
    
    sortedMonths.forEach(month => {
        const monthNum = parseInt(month);
        const option = document.createElement('option');
        option.value = month.padStart(2, '0');
        option.textContent = monthNames[monthNum - 1];
        monthFilter.appendChild(option);
    });
}

// Populate year filter from table data
function populateYearFilter() {
    const rows = document.querySelectorAll('#requestsTableBody tr');
    const years = new Set();
    
    rows.forEach(row => {
        if (row.cells.length > 4) {
            const dateCell = row.cells[4].textContent.trim();
            if (dateCell && dateCell !== '-' && dateCell.includes('/')) {
                const parts = dateCell.split('/');
                if (parts.length === 3) {
                    const year = parts[2];
                    if (year && year.length === 4) {
                        years.add(year);
                    }
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

function checkNoResults() {
    const tableRows = document.querySelectorAll('#requestsTableBody tr');
    const noResultsMsg = document.getElementById('noResultsMessage');
    const table = document.querySelector('.requests-table');
    
    let visibleCount = 0;
    tableRows.forEach(row => {
        if (row.style.display !== 'none') {
            visibleCount++;
        }
    });
    
    if (visibleCount === 0) {
        table.style.display = 'none';
        noResultsMsg.style.display = 'block';
    } else {
        table.style.display = 'table';
        noResultsMsg.style.display = 'none';
    }
}

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    populateMonthFilter();
    populateYearFilter();
    
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    
    const savedMonth = localStorage.getItem('expensesMonthFilter');
    const savedYear = localStorage.getItem('expensesYearFilter');
    
    if (savedMonth && monthFilter) {
        monthFilter.value = savedMonth;
    }
    
    if (savedYear && yearFilter) {
        yearFilter.value = savedYear;
    }
    
    if ((savedMonth && savedMonth !== 'all') || (savedYear && savedYear !== 'all')) {
        applyMonthYearFilters();
    }
    
    if (monthFilter) {
        monthFilter.addEventListener('change', applyMonthYearFilters);
    }
    
    if (yearFilter) {
        yearFilter.addEventListener('change', applyMonthYearFilters);
    }
});

// Print Table Function - Opens liquidation forms in NEW WINDOW
function printTable() {
    console.log('Print button clicked!');
    const rows = document.querySelectorAll('#requestsTableBody tr');
    const visibleRows = [];
    console.log('Total rows found:', rows.length);
    
    // Collect all visible rows from the table
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.cells;
            visibleRows.push({
                controlNumber: cells[0].textContent.trim(),
                requestorName: cells[1].textContent.trim(),
                department: cells[2].textContent.trim(),
                category: cells[3].textContent.trim(),
                dateRequested: cells[4].textContent.trim(),
                requestedAmount: cells[5].textContent.replace('â‚± ', '').replace(/,/g, '').trim(),
                status: cells[6].textContent.trim(),
                dateReleased: cells[7].textContent.trim(),
                totalExpenses: cells[8].textContent.replace('â‚± ', '').replace(/,/g, '').trim(),
                amountReturned: cells[9].textContent.replace('â‚± ', '').replace(/,/g, '').trim(),
                dateReturned: cells[10].textContent.trim()
            });
        }
    });
    
    if (visibleRows.length === 0) {
        console.log('No visible rows found!');
        alert('No data to print. Please adjust your filters or search.');
        return;
    }
    
    console.log('Visible rows:', visibleRows.length);
    
    // Group by control number
    const groupedByControl = {};
    visibleRows.forEach(row => {
        if (!groupedByControl[row.controlNumber]) {
            groupedByControl[row.controlNumber] = [];
        }
        groupedByControl[row.controlNumber].push(row);
    });
    
    // Generate forms HTML for each control number
    let formsHTML = '';
    Object.keys(groupedByControl).forEach((controlNumber, index) => {
        const controlRows = groupedByControl[controlNumber];
        const firstRow = controlRows[0];
        
        // Calculate totals
        let totalExpenses = 0;
        controlRows.forEach(row => {
            const expense = parseFloat(row.totalExpenses) || 0;
            totalExpenses += expense;
        });
        
        const requestedAmount = parseFloat(firstRow.requestedAmount) || 0;
        const amountReturned = parseFloat(firstRow.amountReturned) || 0;
        
        formsHTML += generateLiquidationForm(firstRow, controlRows, totalExpenses, requestedAmount, amountReturned, index > 0);
    });
    
    console.log('Forms HTML generated, length:', formsHTML.length);
    console.log('Opening new window...');
    
    // Open NEW WINDOW with liquidation forms
    const printWindow = window.open('', '_blank', 'width=900,height=700');
    printWindow.document.write('<html><head><title>Liquidation Forms</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: letter; margin: 0.5in; }');
    printWindow.document.write('* { box-sizing: border-box; }');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 0; }');
    printWindow.document.write('.page-break { page-break-after: always; }');
    printWindow.document.write('.form-container { width: 100%; padding: 20px; }');
    printWindow.document.write('.header { text-align: center; margin-bottom: 15px; }');
    printWindow.document.write('.header-flex { display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 10px; }');
    printWindow.document.write('.logo { width: 60px; height: 60px; border: 2px solid #333; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 10px; }');
    printWindow.document.write('.dept-title { font-size: 16px; font-weight: bold; text-decoration: underline; margin: 0; }');
    printWindow.document.write('.form-title { color: #4472C4; font-size: 14px; font-weight: bold; margin: 5px 0; }');
    printWindow.document.write('.control-number { color: red; font-weight: bold; text-align: right; margin: 10px 0; font-size: 13px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin: 8px 0; }');
    printWindow.document.write('table, th, td { border: 1px solid black; }');
    printWindow.document.write('th, td { padding: 6px; text-align: left; font-size: 11px; }');
    printWindow.document.write('th { background-color: #f0f0f0; font-weight: bold; }');
    printWindow.document.write('.note { color: red; font-size: 10px; margin: 8px 0; line-height: 1.4; }');
    printWindow.document.write('.section-title { background-color: #4472C4; color: white; text-align: center; font-weight: bold; padding: 6px; margin: 15px 0 8px; font-size: 13px; }');
    printWindow.document.write('.summary-row { background-color: #FFF2CC; font-weight: bold; }');
    printWindow.document.write('.signature-table td { height: 50px; vertical-align: bottom; text-align: center; font-size: 10px; }');
    printWindow.document.write('.editable { background-color: #ffffcc; }');
    printWindow.document.write('[contenteditable="true"] { min-height: 18px; padding: 2px; }');
    printWindow.document.write('[contenteditable="true"]:focus { outline: 2px solid #4472C4; background-color: white; }');
    printWindow.document.write('@media print { .editable { background-color: white; } [contenteditable="true"] { border: none; outline: none; } }');
    printWindow.document.write('</style></head><body>');
    printWindow.document.write(formsHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Trigger print dialog after a short delay
    setTimeout(() => {
        printWindow.print();
    }, 500);
}

function generateLiquidationForm(firstRow, controlRows, totalExpenses, requestedAmount, amountReturned, addPageBreak) {
    const department = firstRow.department.toUpperCase();
    const controlNumber = firstRow.controlNumber;
    
    // Generate liquidation report rows (max 14 rows to fit in one page)
    let reportRows = '';
    for (let i = 0; i < 14; i++) {
        if (i < controlRows.length) {
            const row = controlRows[i];
            const date = row.dateReleased !== '-' ? row.dateReleased : '';
            const amount = parseFloat(row.totalExpenses) || 0;
            reportRows += '<tr>';
            reportRows += '<td contenteditable="true" style="width: 12%;">' + date + '</td>';
            reportRows += '<td contenteditable="true" style="width: 18%;"></td>';
            reportRows += '<td contenteditable="true" style="width: 50%;">' + row.category + '</td>';
            reportRows += '<td contenteditable="true" style="width: 20%; text-align: right;">â‚± ' + amount.toFixed(2) + '</td>';
            reportRows += '</tr>';
        } else {
            reportRows += '<tr>';
            reportRows += '<td contenteditable="true" style="width: 12%;"></td>';
            reportRows += '<td contenteditable="true" style="width: 18%;"></td>';
            reportRows += '<td contenteditable="true" style="width: 50%;"></td>';
            reportRows += '<td contenteditable="true" style="width: 20%; text-align: right;">â‚±</td>';
            reportRows += '</tr>';
        }
    }
    
    const pageBreakClass = addPageBreak ? 'page-break' : '';
    const dateRequested = firstRow.dateRequested !== '-' ? firstRow.dateRequested : '';
    const dateReleased = firstRow.dateReleased !== '-' ? firstRow.dateReleased : '';
    
    let html = '<div class="form-container ' + pageBreakClass + '">';
    html += '<div class="header">';
    html += '<div class="header-flex">';
    html += '<div class="logo">ARKCREST</div>';
    html += '<div>';
    html += '<div class="dept-title">' + department + ' DEPARTMENT</div>';
    html += '<div class="form-title">BUDGET REQUEST FORM</div>';
    html += '</div></div>';
    html += '<div class="control-number">Control Number: ' + controlNumber + '</div>';
    html += '</div>';
    
    html += '<table><tr>';
    html += '<td style="width: 18%; font-weight: bold;">Name:</td>';
    html += '<td style="width: 32%;" contenteditable="true" class="editable">' + firstRow.requestorName + '</td>';
    html += '<td style="width: 22%; font-weight: bold;">Date Requested:</td>';
    html += '<td style="width: 28%;" contenteditable="true" class="editable">' + dateRequested + '</td>';
    html += '</tr><tr>';
    html += '<td style="font-weight: bold;">Amount Requested:</td>';
    html += '<td contenteditable="true" class="editable">â‚± ' + requestedAmount.toFixed(2) + '</td>';
    html += '<td style="font-weight: bold;">Target Date Released:</td>';
    html += '<td contenteditable="true" class="editable">' + dateReleased + '</td>';
    html += '</tr><tr>';
    html += '<td style="font-weight: bold;">Particular:</td>';
    html += '<td contenteditable="true" class="editable">' + firstRow.category + '</td>';
    html += '<td style="font-weight: bold;">Remarks:</td>';
    html += '<td contenteditable="true" class="editable"></td>';
    html += '</tr></table>';
    
    html += '<div class="note">';
    html += '<strong>Note:</strong> (a) For amount less than â‚± 1,000.00 disbursement will be processed with in the day<br>';
    html += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(b) Amount of â‚±1,000.00 or more than will be disbursed at least one week after the submission.';
    html += '</div>';
    
    html += '<div class="section-title">LIQUIDATION REPORT</div>';
    
    html += '<table><thead><tr>';
    html += '<th style="width: 12%;">DATE</th>';
    html += '<th style="width: 18%;">RECEIPT/INVOICE NO.</th>';
    html += '<th style="width: 50%;">PARTICULARS</th>';
    html += '<th style="width: 20%;">AMOUNT</th>';
    html += '</tr></thead><tbody>';
    html += reportRows;
    html += '</tbody></table>';
    
    html += '<table>';
    html += '<tr class="summary-row">';
    html += '<td style="width: 50%;"><strong>TOTAL EXPENSES: â‚±</strong> <span contenteditable="true">' + totalExpenses.toFixed(2) + '</span></td>';
    html += '<td style="width: 50%;"><strong>LESS CASH ADVANCE: â‚±</strong> <span contenteditable="true"></span></td>';
    html += '</tr><tr class="summary-row">';
    html += '<td colspan="2"><strong>AMOUNT TO BE RETURNED: â‚±</strong> <span contenteditable="true">' + amountReturned.toFixed(2) + '</span></td>';
    html += '</tr></table>';
    
    html += '<p style="font-size: 10px; margin: 8px 0;">This is to certify that the foregoing expenses were disbursed in conformity with the above stated purpose(s).</p>';
    
    html += '<table class="signature-table"><tr>';
    html += '<td style="width: 33%;"><strong>Checked & Approved by:</strong><br><br><br><strong>EDWIN MOJICA</strong><br>Date: _______________</td>';
    html += '<td style="width: 33%;"><strong>Released by:</strong><br><br><br><br>Date: _______________</td>';
    html += '<td style="width: 33%;"><strong>Received by:</strong><br><br><br><br>Date: _______________</td>';
    html += '</tr></table>';
    html += '</div>';
    
    return html;
}


