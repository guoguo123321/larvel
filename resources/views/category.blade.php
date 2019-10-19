@extends('master')
@section('title','首页')

@section('content')
<div class="weui_cells_title">选择书籍类别</div>
<div class="weui_cells weui_cells_split">
    <div class="weui_cell weui_cell_select">
        <div class="weui_cell_bd weui_cell_primary">
            <select class="weui_select" name="category">
                @foreach($categorys as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="weui_cells weui_cell_access">
            <a class="weui_cell " href="javascript:;">
                <div class="weui_cell_bd weui_cell_primary">
                    <p></p>
                </div>
                <div class="weui_cell_ft"></div>
            </a>
</div>

@endsection


@section('my-js')
<script type="text/javascript">
    $('.bk_content').html(document.title);
    
        //进来的时候就要获取选择的下标
        _getcategory();
        //选择的时候选择id
       $('.weui_select').change(function(){
           _getcategory();
       })
       function _getcategory(){
           var parent_id=$('.weui_select :selected').val();
           $.ajax({
            type: "GET",
            url: 'service/category/parent_id/'+ parent_id,
            dataType: 'json',
            cache: false,
//            data: {},
            success: function(data) {
               if(data == null) {
                 $('.bk_toptips').show();
                 $('.bk_toptips span').html('服务端错误');
                 setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                 return;
               }
               if(data.status != 0) {
                 $('.bk_toptips').show();
                 $('.bk_toptips span').html(data.message);
                 setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                 return;
               }
//               else if(data.status == 0){
//                    $('.bk_toptips').show();
//                    $('.bk_toptips span').html('登录成功');
//                    setTimeout(function() {$('.bk_toptips').hide();}, 2000);
////                    location.href="/category";
                     $('.weui_cell_access').html('');
                for(var i=0;i<data.categorys.length;i++){
                     var text='/category/product/category_id/parent_id/'+data.categorys[i].id;//获取id 传过去拿到product表得数据
                    var node='<a class="weui_cell " href="'+text+'">'+
                                '<div class="weui_cell_bd weui_cell_primary">'+
                                    '<p>'+data.categorys[i].name+'</p>'+
                                '</div>'+
                               ' <div class="weui_cell_ft">></div>'+
                            '</a>';
                    $('.weui_cell_access').append(node);
                }
                    console.log(data);
//                }
               
             },
             error: function(xhr, status, error) {
               console.log(xhr);
               console.log(status);
               console.log(error);
             }
           });
       }
    
</script>
@endsection