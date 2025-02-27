<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Item List</title>
</head>
<body>
    <h1>Items</h1>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <a href="{{route('items.create')}}">Add Item</a>
    <ul>
        @foreach($items as $item)
            <li>
                {{$item->name}}
                <a href="{{route('items.edit', $item)}}">Edit</a>
                <form action="{{route('items.destroy', $item)}}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
</body>
</html>