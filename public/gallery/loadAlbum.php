<script type="text/javascript" >
$(function() {		  	
	// move images  to  news album
	$('.album').droppable({
		hoverClass: 'over',
		activeClass: 'dragging',
		drop:function(event,ui){
			
			 if($(this).hasClass('selected')) return false;
						 loading('Moving',0);
						 var album = $(this).attr('id');		
						 var datavalue='newalbumid='+album+'&lastalbumid='+ ui.draggable.imgdata(2)+'&picid='+ ui.draggable.imgdata(0); 
						$.ajax({
							url: "gallery/move.php",
							data: datavalue,
							success: function(data){	
								if(data.check==0){showError('Error');return false;}
										ui.helper.fadeOut(function(){ui.helper.remove();});				
										$('#albumsLoad').fadeOut().load('gallery/loadAlbum.php').fadeIn(function(){ 
																	$('#albumsLoad #albumsList').find("#"+ui.draggable.imgdata(2)).addClass('selected');
																	unloading(); 																							
											});		
							},
							cache: false,type: "POST",dataType: 'json'
						});
			
			ui.helper.fadeOut(400);
			setTimeout("unloading()",1500); 		

		},
		tolerance:'pointer'
	});
	// mouseenter Over album with  CSS3
	$(".preview").delegate('img', 'mouseenter', function() {
		  if ($(this).hasClass('stackphotos')) {
		  var $parent = $(this).parent();
		  $parent.find('img#photo1').addClass('rotate1');
		  $parent.find('img#photo2').addClass('rotate2');
		  $parent.find('img#photo3').addClass('rotate3');
		  }
	  }).delegate('img', 'mouseleave', function() {
		  $('img#photo1').removeClass('rotate1');
		  $('img#photo2').removeClass('rotate2');
		  $('img#photo3').removeClass('rotate3');
	});
	// jScrollPane  Overflow
	$('#albumsList').jScrollPane({ autoReinitialise: true });
	$('.album.load').live('click',function(e){
		  $('.album').removeClass('selected');
		  var albumid=$(this).attr('id');
		  $(this).addClass('selected');
		  loadalbum(albumid);
	});
	function loadalbum(albumid){
			loading('Loading');
			$('.screen-msg').hide();
			$('#imageLoad').load("gallery/loadpics.php?albumid="+albumid,function(){
			  imgRow();
			  
						  $("#uploadAlbum").attr('href','modalupload.php?albumid='+albumid); 	
						  $("#uploadDisableBut").hide();
						  $('#uploadAlbum').removeClass('disable secure ').addClass('special add  ');

			  unloading();												   
			});
		}
				  
 }); 
</script>   
 <div id="albumsList" >
 <?
 include("../config/config.php");
		$albumsResult = q("SELECT  al.*, pic.filename  AS thumbnail FROM 01_albums  AS al LEFT JOIN 01_pics  AS pic ON (al.thumb=pic.id) ORDER BY dt  DESC ");
		while($arr=mysql_fetch_assoc($albumsResult)){
			  if($arr['thumbnail']){
				  $arr['thumbnail']=$config['imageDir'].'/s/'.$arr['thumbnail'];
			  }else{
				  $arr['thumbnail']='images/icon/empty_album_icon_small.jpg';
			  }
			?>
                 <div class="album load" id="<?=$arr[id]?>">
                      <div class="preview">
					  <?
                         $thumbPreview = q(" SELECT * FROM ".$prefix_table."pics  WHERE albumid='".$arr[id]."' and id<>'".$arr[thumb]."' ORDER BY key_position  DESC limit 0,2");
                         $num=mysql_num_rows( $thumbPreview);
                         if($num){
                             if($num==2){
                                 $i=1;
                                  while($thumb_arr=mysql_fetch_assoc($thumbPreview)){
                                 $thumb=$config['imageDir'].'/s/'.$thumb_arr['filename'];
                                 ?>
                                 <img width="130" id="photo<?=$i?>" class="stackphotos" src="<?=$thumb?>" alt="Thumbnail" />
                                 <? $i++; } }elseif($num==1){
                                 $thumb_arr=mysql_fetch_assoc($thumbPreview);
                                 $thumb=$config['imageDir'].'/s/'.$thumb_arr['filename'];
                                 ?>
                                    <img width="130" id="p1" class="stackphotos" src="<?=$thumb?>" alt="Thumbnail" />
                             <? }?>
                                   <img width="130" id="photo3" class="stackphotos" src="<?=$arr['thumbnail']?>" alt="Thumbnail" />
                            <? }else{?>
                                   <img width="130" id="p1" class="stackphotos" src="<?=$arr['thumbnail']?>" alt="Thumbnail" />
                            <? }?>
                      <div style="clear:both"></div>
                      </div>

                  <div class="title"><?=htmlspecialchars($arr['name'])?></div>
                            <div class="stats">Images: <span class="picCount"><?=(int)$arr['cnt']?></span></div>
                            <div class="clear"></div>
                   </div>
<? }?>
</div><!-- End albumsList -->