<!DOCTYPE html>
<html>
<head>
<title>List</title>
    <link rel="stylesheet" href="{{url('css/style.css')}}">
</head>
<body>
    <div class="table-wrapper" >
        <table class="fl-table">
            <tr>
                <th style="background-color: #58236a ; color:white">Title</th>
                <th style="background-color: #58236a ; color:white">Country</th>
                </tr>
        @foreach($object->channel->item as $element)
                <tr>
                <td>{{$element->title}}</td>
                <td>{{$element->country}}</td>
                </tr>
        @endforeach
        </table>
    </div>
</body>
</html>
