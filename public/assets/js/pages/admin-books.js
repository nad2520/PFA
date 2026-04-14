/**
 * Admin Books CRUD Module
 * ===================================================
 * BOOK CRUD: Client-side operations for book management
 * Handles form submissions, validation, and AJAX calls
 * Uses IIFE pattern to isolate scope (no global namespace pollution)
 */

(function () {
    'use strict';

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Initialize book management UI and event listeners
    // ═══════════════════════════════════════════════════════════════════
    window.initBooksManagement = function () {
        // BOOK CRUD: Set up add book form submission handler
        const addBookForm = document.getElementById('addBookForm');
        if (addBookForm) {
            addBookForm.addEventListener('submit', function (e) {
                e.preventDefault();
                handleAddBook();
            });
        }

        // BOOK CRUD: Set up edit book form submission handler
        const editBookForm = document.getElementById('editBookForm');
        if (editBookForm) {
            editBookForm.addEventListener('submit', function (e) {
                e.preventDefault();
                handleUpdateBook();
            });
        }

        // BOOK CRUD: Attach delete handlers to delete buttons
        document.addEventListener('click', function (e) {
            if (e.target.matches('[data-action="delete-book"]')) {
                handleDeleteBook(e.target);
            }
            if (e.target.matches('[data-action="edit-book"]')) {
                handleEditBook(e.target);
            }
        });

        // BOOK CRUD: Load and display all books on page load
        loadAllBooks();
    };

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: CREATE - Handle add book form submission
    // ═══════════════════════════════════════════════════════════════════
    function handleAddBook() {
        // BOOK CRUD: Get form element and validate it exists
        const form = document.getElementById('addBookForm');
        if (!form) return;

        // BOOK CRUD: Extract form data using FormData API (automatic encoding)
        const formData = new FormData(form);
        formData.append('action', 'add_book');

        // BOOK CRUD: Show loading state
        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';

        // BOOK CRUD: Send AJAX POST request to add_book controller
        fetch('./controller/add_book.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                // BOOK CRUD: Handle redirect from controller
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.text();
            })
            .catch(error => {
                // BOOK CRUD: Show error message to user
                console.error('Error adding book:', error);
                showNotification('Error adding book', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: READ - Fetch and display all books
    // ═══════════════════════════════════════════════════════════════════
    function loadAllBooks() {
        // BOOK CRUD: AJAX request to get_books.php with getall action
        fetch('./controller/get_books.php?action=getall&format=json')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // BOOK CRUD: Render books to admin table/list
                    renderBooksTable(data.data);
                } else {
                    console.error('Error loading books:', data.error);
                }
            })
            .catch(error => {
                console.error('Error fetching books:', error);
                showNotification('Error loading books', 'error');
            });
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: READ - Render books in admin table
    // ═══════════════════════════════════════════════════════════════════
    function renderBooksTable(books) {
        // BOOK CRUD: Find table body element where books will be inserted
        const tableBody = document.getElementById('booksTableBody');
        if (!tableBody) return;

        // BOOK CRUD: Clear existing table content
        tableBody.innerHTML = '';

        // BOOK CRUD: Loop through each book and create table rows
        books.forEach(book => {
            const row = document.createElement('tr');
            row.classList.add('table-row');
            row.setAttribute('data-book-id', book.id);

            // BOOK CRUD: Construct HTML row with book data
            row.innerHTML = `
                <td class="table-cell">
                    <div class="book-title">
                        <span class="book-emoji">${book.cover || '📖'}</span>
                        <span>${escapeHtml(book.title)}</span>
                    </div>
                </td>
                <td class="table-cell">${escapeHtml(book.author)}</td>
                <td class="table-cell"><span class="genre-badge">${escapeHtml(book.genre)}</span></td>
                <td class="table-cell"> 
                    <span class="audience-badge ${book.audience === '+18 Only' ? 'badge-adult' : 'badge-all'}">
                        ${escapeHtml(book.audience)}
                    </span>
                </td>
                <td class="table-cell">
                    <span class="coin-value">💰 ${book.coinCost}</span>
                </td>
                <td class="table-cell">
                    <span class="trending-badge ${book.trending ? 'badge-trending' : 'badge-normal'}">
                        ${book.trending ? '🔥 Trending' : 'Normal'}
                    </span>
                </td>
                <td class="table-cell">
                    <div class="action-buttons">
                        <button class="btn-small btn-edit" data-action="edit-book" data-id="${book.id}" title="Edit">
                            ✏️ Edit
                        </button>
                        <button class="btn-small btn-delete" data-action="delete-book" data-id="${book.id}" title="Delete">
                            🗑️ Delete
                        </button>
                    </div>
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: READ - Load single book for editing
    // ═══════════════════════════════════════════════════════════════════
    function handleEditBook(button) {
        // BOOK CRUD: Extract book ID from button data attribute
        const bookId = button.getAttribute('data-id');
        if (!bookId) return;

        // BOOK CRUD: AJAX request to get_books.php to fetch book data
        fetch(`./controller/get_books.php?action=getbyid&id=${bookId}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // BOOK CRUD: Populate edit form with book data
                    populateEditForm(data.data);
                } else {
                    showNotification('Error loading book', 'error');
                }
            })
            .catch(error => {
                console.error('Error fetching book:', error);
                showNotification('Error loading book', 'error');
            });
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Populate edit form with book data
    // ═══════════════════════════════════════════════════════════════════
    function populateEditForm(book) {
        // BOOK CRUD: Get edit form element
        const form = document.getElementById('editBookForm');
        if (!form) return;

        // BOOK CRUD: Fill form fields with book data using name attributes
        form.elements['id'].value = book.id || '';
        form.elements['title'].value = book.title || '';
        form.elements['author'].value = book.author || '';
        form.elements['genre'].value = book.genre || '';
        form.elements['cover'].value = book.cover || '📖';
        form.elements['coinCost'].value = book.coinCost || 100;
        form.elements['xpReward'].value = book.xpReward || 150;
        form.elements['coinReward'].value = book.coinReward || 40;
        form.elements['audience'].value = book.audience || 'All';
        form.elements['trending'].checked = book.trending == 1;
        form.elements['description'].value = book.description || '';

        // BOOK CRUD: Show edit form (scroll into view or display modal)
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: UPDATE - Handle update book form submission
    // ═══════════════════════════════════════════════════════════════════
    function handleUpdateBook() {
        // BOOK CRUD: Get form element and validate it exists
        const form = document.getElementById('editBookForm');
        if (!form) return;

        // BOOK CRUD: Validate book ID is present
        const bookId = form.elements['id'].value;
        if (!bookId) {
            showNotification('Book ID is required', 'error');
            return;
        }

        // BOOK CRUD: Extract form data using FormData API
        const formData = new FormData(form);
        formData.append('action', 'update_book');

        // BOOK CRUD: Show loading state
        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';

        // BOOK CRUD: Send AJAX POST request to update_book controller
        fetch('./controller/update_book.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                // BOOK CRUD: Handle redirect from controller
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.text();
            })
            .catch(error => {
                // BOOK CRUD: Show error message and restore button
                console.error('Error updating book:', error);
                showNotification('Error updating book', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: DELETE - Handle book deletion with confirmation
    // ═══════════════════════════════════════════════════════════════════
    function handleDeleteBook(button) {
        // BOOK CRUD: Extract book ID from button data attribute
        const bookId = button.getAttribute('data-id');
        if (!bookId) return;

        // BOOK CRUD: Get book title from table row for confirmation message
        const row = button.closest('tr');
        const bookTitle = row ? row.querySelector('.book-title span:nth-child(2)').textContent : 'this book';

        // BOOK CRUD: Show confirmation dialog before deleting
        if (!confirm(`Are you sure you want to delete "${bookTitle}"? This action cannot be undone.`)) {
            return;
        }

        // BOOK CRUD: Show loading state on delete button
        button.disabled = true;
        button.textContent = 'Deleting...';

        // BOOK CRUD: Send AJAX request to delete_book controller
        fetch(`./controller/delete_book.php?id=${bookId}`, {
            method: 'GET'
        })
            .then(response => {
                // BOOK CRUD: Handle redirect from controller
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.text();
            })
            .catch(error => {
                // BOOK CRUD: Show error message and restore button
                console.error('Error deleting book:', error);
                showNotification('Error deleting book', 'error');
                button.disabled = false;
                button.textContent = '🗑️ Delete';
            });
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Filter books by genre
    // ═══════════════════════════════════════════════════════════════════
    window.filterByGenre = function (genre) {
        // BOOK CRUD: If no genre selected, load all books
        if (!genre || genre === 'All') {
            loadAllBooks();
            return;
        }

        // BOOK CRUD: AJAX request to get_books.php with genre filter
        fetch(`./controller/get_books.php?action=genre&value=${encodeURIComponent(genre)}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // BOOK CRUD: Render filtered books
                    renderBooksTable(data.data);
                    showNotification(`Showing ${data.count} books in ${genre}`, 'info');
                } else {
                    showNotification('Error filtering books', 'error');
                }
            })
            .catch(error => {
                console.error('Error filtering books:', error);
                showNotification('Error filtering books', 'error');
            });
    };

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Filter books by audience
    // ═══════════════════════════════════════════════════════════════════
    window.filterByAudience = function (audience) {
        // BOOK CRUD: If no audience selected, load all books
        if (!audience || audience === 'All') {
            loadAllBooks();
            return;
        }

        // BOOK CRUD: AJAX request to get_books.php with audience filter
        fetch(`./controller/get_books.php?action=audience&value=${encodeURIComponent(audience)}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // BOOK CRUD: Render filtered books
                    renderBooksTable(data.data);
                    showNotification(`Showing ${data.count} books for ${audience}`, 'info');
                } else {
                    showNotification('Error filtering books', 'error');
                }
            })
            .catch(error => {
                console.error('Error filtering books:', error);
                showNotification('Error filtering books', 'error');
            });
    };

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Get trending books
    // ═══════════════════════════════════════════════════════════════════
    window.getTrendingBooks = function (limit = 10) {
        // BOOK CRUD: AJAX request to get_books.php to fetch trending books
        fetch(`./controller/get_books.php?action=trending&limit=${limit}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderBooksTable(data.data);
                    showNotification(`Showing ${data.count} trending books`, 'info');
                } else {
                    showNotification('Error loading trending books', 'error');
                }
            })
            .catch(error => {
                console.error('Error fetching trending books:', error);
                showNotification('Error loading trending books', 'error');
            });
    };

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Search books by title or author
    // ═══════════════════════════════════════════════════════════════════
    window.searchBooks = function (query) {
        if (!query || query.trim() === '') {
            loadAllBooks();
            return;
        }

        // BOOK CRUD: AJAX request to get_books.php with search query
        fetch(`./controller/get_books.php?action=search&q=${encodeURIComponent(query)}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderBooksTable(data.data);
                    showNotification(`Found ${data.count} books matching "${query}"`, 'info');
                } else {
                    showNotification('Error searching books', 'error');
                }
            })
            .catch(error => {
                console.error('Error searching books:', error);
                showNotification('Error searching books', 'error');
            });
    };

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Utility function to show notifications
    // ═══════════════════════════════════════════════════════════════════
    function showNotification(message, type = 'info') {
        // Use existing notification system or create simple alert
        console.log(`[${type.toUpperCase()}] ${message}`);
        // Could be replaced with more sophisticated notification UI
    }

    // ═══════════════════════════════════════════════════════════════════
    // BOOK CRUD: Escape HTML special characters to prevent XSS
    // ═══════════════════════════════════════════════════════════════════
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

})();
