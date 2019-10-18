@extends('master')
@section('title',$product->name)

@section('content')
<link rel="stylesheet" href="/css/swipe.css"/>
<!--<div class="page bk_content" style="top: 0;">-->
    <div class="addWrap">
      <div class="swipe" id="mySwipe">
        <div class="swipe-wrap">
          @foreach($pdt_images as $pdt_image)
          <div>
            <a href="javascript:;"><img class="img-responsive" src="{{$pdt_image->image_path}}" /></a>
          </div>
          @endforeach
        </div>
      </div>
      <ul id="position">
        @foreach($pdt_images as $index => $pdt_image)
          <li class={{$index == 0 ? 'cur' : ''}}></li>
        @endforeach
      </ul>
    </div>

  <div class="weui_cells_title">书籍</div>
  <div class="weui_cells weui_cells_split">
      <a class="weui_cell " href="javascript:;">
              <div class="weui_cell_bd weui_cell_primary">
                  <div>
                      <span class="bk_title">{{$product->name}}</span>
                      <span class="bk_title" style="float: right;color: #CD4A4A">￥{{$product->price}}</span>
                  </div>
                  <p class="bk_summary">{{$product->summary}}</p>
              </div>
          <div class="weui_cell_ft"></div>
      </a>
  </div>

  <div class="weui_cells_title">书籍详情</div>
  <div class="weui_cells">
      <div class="weui_cell">
          <p>{!!$pdt_content->content!!}</p>
      </div>
  </div>
<!--</div>-->

<div class="bk_fix_bottom">
  <div class="bk_half_area">
    <button class="weui_btn weui_btn_primary" onclick="_addCart();">加入购物车</button>
  </div>
  <div class="bk_half_area">
    <button class="weui_btn weui_btn_default" onclick="_toCart()">查看购物车(<span id="cart_num" class="m3_price"></span>)</button>
  </div>
</div>
@endsection


@section('my-js')
<script src="/js/swipe.min.js" charset="utf-8"></script>
<script type="text/javascript">
    $('.bk_content').html(document.title);
    
  var bullets = document.getElementById('position').getElementsByTagName('li');
  Swipe(document.getElementById('mySwipe'), {
    auto: 2000,
    continuous: true,
    disableScroll: false,
    callback: function(pos) {
      var i = bullets.length;
      while (i--) {
        bullets[i].className = '';
      }
      bullets[pos].className = 'cur';
    }
  });
</script>
@endsection