<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Add Item</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%; 
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <form action="{{ route('items.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Item Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Item Description</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="type">Item Type</label>
            <select id="type" name="type" required>
                <option value="1">1- Clothing</option>
                <option value="2">2- Food</option>
                <option value="3">3- Electronic</option>
                <option value="4">4- Hardware</option>
            </select>
        </div>

        <button type="submit">Add Item</button>
    </form>

</body>
</html>
