<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Item</title>
</head>
<body>
    <h1>Edit Item</h1>
    <form action="{{route('items.update', $item)}}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{$item->name}}" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required>{{$item->description}}</textarea>
        <br>
        <button type="submit">Update Item</button>
    </form>
    <a href="{{route('items.index')}}">Back to List</a>
</body>
</html>