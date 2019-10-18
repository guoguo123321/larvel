@extends('master')
@section('title','书籍列表')

@section('content')
<div class="weui_cells_title">选择书籍</div>
<div class="weui_cells weui_cells_split">
    @foreach($products as $product)
    <a class="weui_cell " href="/pdtcontent/{{$product->id}}">
        <div class="weui_cell_hd"><img class="bk_preview" src="{{$product->preview}}"/></div>
            <div class="weui_cell_bd weui_cell_primary">
                <div>
                    <span class="bk_title">{{$product->name}}</span>
                    <span class="bk_title" style="float: right;color: #CD4A4A">￥{{$product->price}}</span>
                </div>
                <p class="bk_summary">{{$product->summary}}</p>
            </div>
        <div class="weui_cell_ft"></div>
    </a>
     @endforeach
</div>
@endsection


@section('my-js')
<script type="text/javascript">
    $('.bk_content').html(document.title);
</script>
@endsection