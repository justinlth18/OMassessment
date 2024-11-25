<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items List</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

    <button id="logout-btn">Log Out</button>

    <h1>Items</h1>
    <a href="{{ route('items.create') }}"><button>Add New Item</button></a>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Type</th>
                <th>Edit Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    @switch($item->type)
                        @case(1) Clothing @break
                        @case(2) Food @break
                        @case(3) Electronic @break
                        @case(4) Hardware @break
                        @default Unknown @break
                    @endswitch
                </td>
                <td>
                    <!-- Dropdown to change item type -->
                    <form action="{{ url('/items/'.$item->id.'/changeitemtype') }}" method="POST" id="change-type-form-{{ $item->id }}">
                        @csrf
                        @method('PATCH')
                        <select name="type" class="type-selector" data-item-id="{{ $item->id }}">
                            <option value="1" {{ $item->type == 1 ? 'selected' : '' }}>Clothing</option>
                            <option value="2" {{ $item->type == 2 ? 'selected' : '' }}>Food</option>
                            <option value="3" {{ $item->type == 3 ? 'selected' : '' }}>Electronic</option>
                            <option value="4" {{ $item->type == 4 ? 'selected' : '' }}>Hardware</option>
                        </select>
                    </form>
                </td>
                <td style="text-align: center; white-space: nowrap;">
                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-edit">Edit</a>
                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-item-btn">Delete</button>
                    </form>
                </td>                
            </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        window.onload = function() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/';
            } else {
                document.getElementById('logout-btn').style.display = 'block';
            }
        };

        $('#logout-btn').on('click', function() {
            $.ajax({
                url: '/logins/logout',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    localStorage.removeItem('token');
                    
                    window.location.href = '/';
                },
                error: function(xhr) {
                    alert('Error logging out!');
                }
            });
        });

        $(document).on('change', '.type-selector', function() {
            var itemId = $(this).data('item-id');
            var selectedType = $(this).val();

            $.ajax({
                url: '/items/' + itemId + '/changeitemtype',
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: selectedType
                },
                success: function(response) {
                    alert(response.message);
                    var typeText = '';
                    switch (response.item.type) {
                        case 1: typeText = 'Clothing'; break;
                        case 2: typeText = 'Food'; break;
                        case 3: typeText = 'Electronic'; break;
                        case 4: typeText = 'Hardware'; break;
                    }
                    $('#item-type-' + itemId).text(typeText); 
                },
                error: function(xhr) {
                    alert('Error updating item type!');
                }
            });
        });

        document.querySelectorAll('.delete-item-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const form = this.closest('form'); 

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to delete this item?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Your item has been deleted.',
                        timer: 3000,
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Cancelled',
                        text: 'Your item is safe.',
                        timer: 3000,

                    });
                }
            });
        });
    });
    </script>

</body>
</html>
