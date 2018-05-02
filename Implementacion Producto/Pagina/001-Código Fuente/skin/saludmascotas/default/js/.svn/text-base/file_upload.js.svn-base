(function(){
	
//	jQuery(document).ready(function(){
//		jQuery(".addFile").fancybox({
//			'width': 423,
//			'height':287,
//	        //'autoScale':false,
//	        'transitionIn':'none',
//			'transitionOut':'none',
//			'type':'iframe'
//		});
//	});
	window.photo_file_upload = function(){
		
	}
	photo_file_upload.prototype = {
		container_id: null,
		base_url: null,
		init:function(params){
			try{
			this.container_id = params.container_id;
			this.base_url = params.base_url;
			this.id_mascota = params.id_mascota;
			this.jqcontainer = jQuery('#'+this.container_id);
			this.jqimagecontainer = this.jqcontainer.find('ul');
			this.jqimagecontainer.find('li').each(this.initialize_li.bind(this));
			var url_add = this.base_url + '/add/' + this.container_id;
			if(this.id_mascota!='' && this.id_mascota!=0){
				url_add += '/' + this.id_mascota;
			}
			this.jqcontainer.find('.add')
				.attr('href', url_add)
				.fancybox({
					'width': 423,
					'height':287,
			        //'autoScale':false,
			        'transitionIn':'none',
					'transitionOut':'none',
					'type':'iframe'
				})
			;
			this.initialize_gallery();
			}
			catch(e){
				window.console.log(e);
			}
		},
		initialize_li: function(idx, obj){
			var _this = obj;
			var jqthis = jQuery(_this);
			jqthis.find('.delete').click(this.handler_delete_click.bind(this));
		},
		handler_delete_click: function(e){
			var that = this;
			var _this = e.target;
			var jqthis = jQuery(_this);
			var url = this.base_url + '/delete/' + jqthis.parents('li:first').attr('data-imgid');
			jQuery.ajax({
				url: url,
				type:'GET',
				dataType: 'json',
				success: function(data, textStatus, jqxhr){
					if(data.error_id){
						switch(data.error_id){
							case 2:
							case 3:
								jqthis.parents('li').remove();
							break;
						}
						alert(data.message);
					}
					else jqthis.parents('li').remove();
				}
			})
			return false; 
		},
		add_image: function(){
			try{
			for(var i=0;i<arguments.length;i++){
				var params = arguments[i];
				var id = params.id;
				var thumb_src = params.thumb_src;
				var src = params.src;
				
				var jqli = jQuery('<li></li>').attr('data-imgid',id);
				var jqimg = jQuery('<img />');
				jqimg.attr('src', thumb_src);
				var jqa = jQuery('<a></a>');
				jqa.attr('href', src);
				var jqdelete = jQuery('<a></a>');
				jqdelete.addClass('delete').attr('href', '#').text('Eliminar');
				jqimg.appendTo(jqa);
				jqa.appendTo(jqli);
				jqdelete.appendTo(jqli);
				jqli.appendTo(this.jqimagecontainer);
				jqli.each(this.initialize_li.bind(this));
			}
			if(arguments.length){
				this.initialize_gallery();
			}
			}
			catch(e){
				window.console.log(e);
			}
		},
		initialize_gallery: function(){
			this.jqimagecontainer.find("a[rel=listado_imagenes]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Imagen ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
				}
			});
		}
	}
})();
