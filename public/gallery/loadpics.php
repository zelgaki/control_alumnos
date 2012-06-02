<? 
include("../config/config.php");  
$albumid=(int)$_GET['albumid'];
?>
<script type="text/javascript" >
	 $(document).ready(function(){	
				 // fancybox  with Double click			
				  $('.albumImage').dblclick(function(){
						$("a[rel=glr]").fancybox({  'showCloseButton': true,'centerOnScroll' : true, 'overlayOpacity' : 0.8,'padding' : 0 });
						$(this).find('a').trigger('click');
				  })
				  // images hover
				  $('.picHolder,.SEdemo').hover(
						function() {
							$(this).find('.picTitle').fadeTo(200, 1);
						},function() {
							$(this).find('.picTitle').fadeTo(200, 0);
						}
					)	
				  // jScrollPane  Overflow
				  $('.albumpics').jScrollPane({ autoReinitialise: true });
				  $('.album.load').live('click',function(e){
						$('.album').removeClass('selected');
						var albumid=$(this).attr('id');
						$(this).addClass('selected');
						loadalbum(albumid);
				  })	
				  // Sortable
				    $( "#sortable" ).sortable({
					    opacity: 0.6,revert: true,cursor: "move", zIndex:9000,
					    update : function () {
						var order = $('#sortable').sortable('serialize');
						$.ajax({
							url: "gallery/position.php",
							data: order,
							success: function(data){	
								 if(data.check==0){showError('Error');return false;}
								 if(data.check==1){showSuccess('Sortable Images Success',1000);return false;}
							},
							cache: false,type: "POST",dataType: 'json'
						});
					 }
				    });
					
					// Cover Album Change
				  $('.picPreview').droppable({
					  hoverClass: 'picPreview-hover',
					  activeClass: 'picPreview-hover',
					   drop: function( event, ui ) { 
						   $('#image-albumPreview').attr('src',ui.draggable.find('img').attr('src'));
						   	var id=ui.draggable.imgdata(0);
							 document.Save_album.thumbPreview.value=id;
					   }
				});	
				  
				 // Form Save
				$(".save_").click(function() { 	  	
						 loading('Saving',0);
						 var form_id=$(this).parents('form').attr('id');
						 var datavalue=$('#'+form_id).serialize();		
						$.ajax({
							url: "gallery/save.php",
							data: datavalue,
							success: function(data){	
								  if(data.check==0){  showError('Error : Sorry '); return false; }
								  if(data.check==1){  											  
										$('#albumsLoad').fadeOut(500,function(){
																	showSuccess('Success',5000); 
																	unloading();	
										  }).load('gallery/loadAlbum.php').fadeIn();
								  }
							},
							cache: false,
							type: "POST",
							dataType: 'json'
						});
				});
				
	   // Delete album  
	   $(".albumDelete").live('click',function() { 
				 var name = $(this).attr("name");
				 var datavalue ='id='+ $(this).attr("rel");   
				 albumDelete(datavalue,name);
	  });
	  function albumDelete(datavalue,name){
			  $.confirm({
			  'title': '_DELETE DIALOG BOX','message': "<strong>YOU WANT TO DELETE </strong><br /><font color=red>' "+ name +" ' </font> ",'buttons': {'Yes': {'class': 'special',
			  'action': function(){
								loading('Deleting',1);
								$.ajax({
									url: "gallery/delete.php",
									data: datavalue,
									success: function(data){	
										  if (data.check == "0"){showError('Error Delete',5000);}	   
										  if (data.check == "1"){
											  $('#albumsLoad').fadeOut(500,function(){
															  
															  $("#uploadAlbum").removeAttr('href',''); 	
															  $("#uploadDisableBut").show();
															  $('#uploadAlbum').removeClass(' special add  ').addClass(' disable secure ');
															  $('#imageLoad').fadeOut(500,function(){ $(this).html('');}).fadeIn();				
															  $('.screen-msg').show();
															  setTimeout("unloading();",900); 
															  setTimeout("showSuccess('Success',5000);",1000);  							
																					
													}).load('gallery/loadAlbum.php').fadeIn();			
										  return false;
										  }
									},
									cache: false,
									type: "GET",
									dataType: 'json'
								});
				}},'No'	: {'class'	: ''}}});
	  }
				 // Drag & Drop  Delete images 
				$('.deletezone').droppable({
					hoverClass: 'deletezoneover',
					activeClass: 'deletezonedragging',
					drop:function(event,ui){	
			
					   var datavalue='id='+ ui.draggable.imgdata(0)+'&albumid='+ ui.draggable.imgdata(2); 
					   var name =ui.draggable.imgdata(1); 
			
					$.confirm({
					'title': 'DELETE DIALOG BOX','message': "<strong>YOU WANT TO DELETE </strong><br /><font color=red>' "+ name +" ' </font> ",'buttons': {'Yes': {'class': 'special',
					'action': function(data){
								loading('Deleting',1);
								$.ajax({
									url: "gallery/delete.php",
									data: datavalue,
									success: function(data){	
										  if (data.check == "0"){showError('Error Delete',5000);}	   
										  if (data.check == "1"){
												ui.helper.fadeOut(function(){ ui.helper.remove(); });
											  $('#albumsLoad').fadeOut(function(){		  
													  if(data.thumb){
														  $('#image-albumPreview').attr('src','<?='images/icon/empty_album.jpg'?>');
													  }	
												  }).load('gallery/loadAlbum.php').fadeIn(function(){ 			
															  setTimeout("unloading();",900); 
															  setTimeout("showSuccess('Success',5000);",1000);  
												  });			
										  return false;
										  }
									},
									cache: false,
									type: "GET",
									dataType: 'json'
								});
						}},'No'	: {'class'	: ''}}});
					},
					tolerance:'pointer'
				});
				
				// Link On/Off Edit Album 
				$('#editAlbum.editOn').live('click',function(){							   
					$('.album_edit').fadeIn(400);
					$('.boxtitle').css({'margin-left':'207px'});
					$('.boxtitle .texttip').hide();
						$(this).html('close edit').attr('title','Click here to Close edit  ').removeClass('editOn').addClass('editOff');
						imgRow();
				})
				$('#editAlbum.editOff').live('click',function(){													   
						$('.album_edit').fadeOut(400,function(){
						$('.boxtitle .texttip').show();
								 $('.boxtitle').css({'margin-left':'0'});
								 imgRow();
						});
						$(this).html('edit album').attr('title','Click here to edit  Album ').removeClass('editOff').addClass('editOn');
				})
				  
	  }); 

</script>   
                        <div class="albumImagePreview">
                              <div class="album_edit" style="display:none">
							  <?
							  
                              $fecth_album = q_array(" SELECT * FROM ".$prefix_table."albums  WHERE id='$albumid' ");
                               if($fecth_album['thumb']){
                                   $thumbPreview = q_array(" SELECT * FROM ".$prefix_table."pics  WHERE id=".$fecth_album['thumb']);
                                   $thumb['thumbnail']=$config['imageDir'].'/s/'.$thumbPreview['filename'];
                               }else{
                                   $thumb['thumbnail']='images/icon/empty_album.jpg';
                               }
                              ?>
                               <form name="Save_album"  id="Save_album" action="">
                              <h1>Edit  Album</h1>
                              
                              <div class="picPreview"><img id="image-albumPreview" title="Drop Image Here"  src="<?=$thumb['thumbnail']?>" alt="Image Preview"  /></div>
                              <div class="clear"></div>
                              <div class="hr"></div>
                              
    							  <input type="hidden" name="id_edit" id="id_edit"  value="<?=$fecth_album[id]?>" />
                                  <input type="hidden" name="thumbPreview" id="thumbPreview"  />
                                 <div class="tip">
                                <input type="text" name="name" id="name"  class="validate[required] "  title="Album name" style="width:146px" value="<?=$fecth_album[name]?>" maxlength="35" />
                                </div>
                                  <div class="hr"></div>
                                  <ul class="uibutton-group">
                                    <a class="uibutton  normal save_" >save</a>
                                    <a class="uibutton  albumDelete  special " rel="<?=$fecth_album[id]?>" name="<?=$fecth_album[name]?>" >Delete </a>
                                  </ul>
                                  <div class="hr"></div>
                                  </form>
                                  <div class="deletezone small" > Drop Images To Delete</div>
                                  <div class="hr"></div>    
                         
                                  <div class="clear"></div>
                              </div>
                              
                              <div class="boxtitle" ><span class="texttip">double click to viwe large images // </span>
                              <a id="editAlbum"  class="editOn" title="Click here to edit  Album">edit album</a>
                              </div>		

							   <?
                              $result = q(" SELECT * FROM ".$prefix_table."pics  WHERE albumid=".$albumid." ORDER BY key_position ASC ");
                              if(!mysql_num_rows($result)){
							 ?>
                             
                                <div class="screen-msg"  style="line-height:470px;">
                                    <span class="ico gray upload2"></span> 
                                    Please click upload Pic to Album.
                                </div>
                                <div class="clear"></div>
                             
							 <? }else{?>
                              <div class="albumpics">
                                    <ul id="sortable"  >
                                    
										<? while($row = mysql_fetch_assoc($result)){?>
                                        <li class="albumImage" id="posi_<?=$row[id]?>" >
                                            <div class="picHolder">
                                                <span class="image_highlight"></span>
                                                  <a href="<?=$config['imageDir'].'/m/'.$row['filename']?>" rel='glr'></a>	
                                                  <img src="<?=$config['imageDir'].'/s/'.$row['filename']?>"  title="Drag This Photo" />
                                            <div class="picTitle"><?=$row['title']?></div>
                                            <ul class="dataImg"><!--// This data images with your call to php or SQL -->
                                                    <li><?=$row[id]?></li><!--// This id images-->
                                                    <li><?=$row[title]?></li><!--// This name images-->
                                                    <li><?=$row[albumid]?></li><!--// This album id images-->
                                            </ul>   
                                            </div>
                                        </li>
									  <? }?>
                                      
                                    </ul>
                              </div> 
                              <? } ?>
                             <br class="clear" />
                        </div>
                        <br class="clear" />