
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items List</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>
<body>


<div class="container mt-5">

    <h2>Items List</h2>
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Add New Item</button>
    <table class="table table-bordered" id="items-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="items-tbody">
            <!-- Items will be dynamically populated here -->
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-form">
                    <div class="form-group">
                        <label for="add-name">Name</label>
                        <input type="text" class="form-control" id="add-name" required>
                    </div>
                    <div class="form-group">
                        <label for="add-description">Description</label>
                        <input type="text" class="form-control" id="add-description" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-form">
                    <input type="hidden" id="edit-item-id">
                    <div class="form-group">
                        <label for="edit-name">Name</label>
                        <input type="text" class="form-control" id="edit-name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <input type="text" class="form-control" id="edit-description" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <button type="button" id="confirm-delete" class="btn btn-danger">Yes, Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
// Fetch items and populate table
const token = localStorage.getItem('token');

if (!token) {
    window.location.href = '/login'; // Redirect to login if no token found
} else {
    // Fetch the items from the API
    fetchItems();

    // Fetch items function
    function fetchItems() {
        fetch('http://127.0.0.1:8000/items', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('items-tbody');
            tbody.innerHTML = ''; // Clear the table body
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.description}</td>
                    <td>
                        <button class="btn btn-info" onclick="editItem(${item.id})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching items:', error);
        });
    }

    // Add new item function
    document.getElementById('add-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const name = document.getElementById('add-name').value;
        const description = document.getElementById('add-description').value;

        fetch('http://127.0.0.1:8000/items', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, description })
        })
        .then(response => response.json())
        .then(() => {
            $('#addModal').modal('hide');
            fetchItems(); // Refresh the list
        })
        .catch(error => {
            console.error('Error adding item:', error);
        });
    });

    // Edit item function
    function editItem(id) {
        // Fetch item details to populate the edit form
        fetch(`http://127.0.0.1:8000/items/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-item-id').value = data.id;
            document.getElementById('edit-name').value = data.name;
            document.getElementById('edit-description').value = data.description;
            $('#editModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching item:', error);
        });
    }

    // Edit item function (PATCH request)
function editItem(id) {
    // Fetch item details to populate the edit form
    fetch(`http://127.0.0.1:8000/items/${id}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Fill in the edit form with current item details
        document.getElementById('edit-item-id').value = data.id;
        document.getElementById('edit-name').value = data.name;
        document.getElementById('edit-description').value = data.description;
        $('#editModal').modal('show'); // Show modal
    })
    .catch(error => {
        console.error('Error fetching item:', error);
    });
}

    // Save changes (PATCH request to update item)
    document.getElementById('edit-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const itemId = document.getElementById('edit-item-id').value;
        const name = document.getElementById('edit-name').value;
        const description = document.getElementById('edit-description').value;

        // Send PATCH request to update part of the item
        fetch(`http://127.0.0.1:8000/items/${itemId}/changeitemtype/`, {
            method: 'PATCH', // Use PATCH for partial update
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, description }) // Only send the fields that are being updated
        })
        .then(response => response.json())
        .then(() => {
            $('#editModal').modal('hide'); // Hide modal after updating
            fetchItems(); // Refresh the list of items
        })
        .catch(error => {
            console.error('Error updating item:', error);
        });
    });


    // Save changes (Update item)
    document.getElementById('edit-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const itemId = document.getElementById('edit-item-id').value;
        const name = document.getElementById('edit-name').value;
        const description = document.getElementById('edit-description').value;

        fetch(`http://127.0.0.1:8000/items/${itemId}`, {
            method: 'PUT', // Use PATCH if needed
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, description })
        })
        .then(response => response.json())
        .then(() => {
            $('#editModal').modal('hide');
            fetchItems(); // Refresh the list
        })
        .catch(error => {
            console.error('Error updating item:', error);
        });
    });

    // Delete item function
    function deleteItem(id) {
        // Show delete confirmation modal
        $('#deleteModal').modal('show');
        document.getElementById('confirm-delete').onclick = function () {
            fetch(`http://127.0.0.1:8000/items/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                $('#deleteModal').modal('hide');
                fetchItems(); // Refresh the list
            })
            .catch(error => {
                console.error('Error deleting item:', error);
            });
        };
    }
}
</script>

</body>
</html>
