/**
 * @copyright	Copyright (C) 2012 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Carousel CK
 * @license		GNU/GPL
 * */

//function addfromfolderck() {
//
//}

// pour gestion editeur d'images
function jInsertEditorText(text, editor) {
	var newEl = new Element('span').set('html', text);
	var valeur = newEl.getChildren()[0].getAttribute('src');
	$(editor).value = valeur;
	addthumbnail(valeur, editor);
}

function addthumbnail(imgsrc, editor) {
	var slideimg = $(editor).getParent().getElement('img');
	var testurl = 'http';
	if (imgsrc.toLowerCase().indexOf(testurl.toLowerCase()) != -1) {
		slideimg.src = imgsrc;
	} else {
		slideimg.src = JURI + imgsrc;
	}

	slideimg.setProperty('width', '64px');
	slideimg.setProperty('height', '64px');
}

function addslideck(imgname, imgcaption, imgthumb, imglink, imgtarget, imgvideo, slideselect, imgalignment, articleid, imgtime, articlename, imgtitle) {
	if (!articleid)
		articleid = '';
	if (!articlename)
		articlename = '';
	if (!imgtime)
		imgtime = '';
	if (!imgname)
		imgname = '';
	if (!imgthumb)
		imgthumb = '../modules/mod_carouselck/elements/ckslidesmanager/unknown.png';
	if (!imgcaption)
		imgcaption = '';
	imgcaption = imgcaption.replace(/\|dq\|/g, "&quot;");
	if (!imgtitle) imgtitle = '';
	imgtitle = imgtitle.replace(/\|dq\|/g,"&quot;");
	if (!imglink)
		imglink = '';
	if (!imgvideo)
		imgvideo = '';
	if (!imgtarget || imgtarget == 'default') {
		imgtarget = '';
		imgtargetoption = '<option value="default" selected="selected">' + Joomla.JText._('MOD_CAROUSELCK_DEFAULT', 'default') + '</option><option value="_parent">' + Joomla.JText._('MOD_CAROUSELCK_SAMEWINDOW', 'same window') + '</option><option value="_blank">' + Joomla.JText._('MOD_CAROUSELCK_NEWWINDOW', 'new window') + '</option><option value="lightbox">' + Joomla.JText._('MOD_CAROUSELCK_LIGHTBOX', 'in a Lightbox') + '</option>';
	} else {
		if (imgtarget == '_parent') {
			imgtargetoption = '<option value="default">' + Joomla.JText._('MOD_CAROUSELCK_DEFAULT', 'default') + '</option><option value="_parent" selected="selected">' + Joomla.JText._('MOD_CAROUSELCK_SAMEWINDOW', 'same window') + '</option><option value="_blank">' + Joomla.JText._('MOD_CAROUSELCK_NEWWINDOW', 'new window') + '</option><option value="lightbox">' + Joomla.JText._('MOD_CAROUSELCK_LIGHTBOX', 'in a Lightbox') + '</option>';
		} else if (imgtarget == 'lightbox') {
			imgtargetoption = '<option value="default">' + Joomla.JText._('MOD_CAROUSELCK_DEFAULT', 'default') + '</option><option value="_parent">' + Joomla.JText._('MOD_CAROUSELCK_SAMEWINDOW', 'same window') + '</option><option value="_blank">' + Joomla.JText._('MOD_CAROUSELCK_NEWWINDOW', 'new window') + '</option><option value="lightbox" selected="selected">' + Joomla.JText._('MOD_CAROUSELCK_LIGHTBOX', 'in a Lightbox') + '</option>';
		} else {
			imgtargetoption = '<option value="default">' + Joomla.JText._('MOD_CAROUSELCK_DEFAULT', 'default') + '</option><option value="_parent">' + Joomla.JText._('MOD_CAROUSELCK_SAMEWINDOW', 'same window') + '</option><option value="_blank" selected="selected">' + Joomla.JText._('MOD_CAROUSELCK_NEWWINDOW', 'new window') + '</option><option value="lightbox">' + Joomla.JText._('MOD_CAROUSELCK_LIGHTBOX', 'in a Lightbox') + '</option>';
		}
	}

	index = checkIndex(0);
	var ckslide = new Element('li', {
		'class': 'ckslide',
		'id': 'ckslide' + index
	});
	ckslide.set('html', '<div class="ckslidehandle"><div class="ckslidenumber">' + index + '</div></div><div class="ckslidecontainer">'
			+ '<input name="ckslidedelete' + index + '" class="ckslidedelete" type="button" value="' + Joomla.JText._('MOD_CAROUSELCK_REMOVE2', '') + '" onclick="javascript:removeslide(this.getParent().getParent());" />'
			+ '<div class="cksliderow"><div class="ckslideimgcontainer"><img src="' + imgthumb + '" width="64" height="64"/></div>'

			+ '<input name="ckslideimgname' + index + '" id="ckslideimgname' + index + '" class="ckslideimgname hasTip" title="Image::This is the main image for the slide, it will also be used to create the thumbnail" type="text" value="' + imgname + '" onchange="javascript:addthumbnail(this.value, this);" />'

			+ '<a class="modal ckselectimg" href="' + JURI + 'administrator/index.php?option=com_media&view=images&tmpl=component&e_name=ckslideimgname' + index + '" rel="{handler: \'iframe\', size: {x: 570, y: 400}}" >' + Joomla.JText._('MOD_CAROUSELCK_SELECTIMAGE', 'select image') + '</a></div>'
//			+ '<div class="cksliderow2"><span class="ckslidelabel">' + Joomla.JText._('MOD_CAROUSELCK_USETOSHOW', 'Display') + '</span><select class="ckslideselect">' + slideselectoption + '</select>'
//			+ '<span><img src="../modules/mod_slideshowck/elements/images/hourglass.png" style="float: none; padding-top: 5px;" align="top" class="hasTip" title="' + Joomla.JText._('MOD_CAROUSELCK_SLIDETIME', 'enter a specific time value for this slide, else it will be the default time') + '"/><input name="ckslideimgtime' + index + '" class="ckslideimgtime" type="text" value="' + imgtime + '" onchange="javascript:storesetwarning();" style="width:25px;" /></span><span>ms</span>'
//			+ '</div>'
			+ '<div class="cksliderow"><span class="ckslidelabel">'+Joomla.JText._('MOD_PLAYLISTCK_TITLE', 'Title')+'</span><input name="ckslidetitletext'+index+'" class="ckslidetitletext" type="text" value="'+imgtitle+'" onchange="javascript:storesetwarning();" /></div>'
			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('MOD_CAROUSELCK_CAPTION', 'Caption') + '</span><input name="ckslidecaptiontext' + index + '" class="ckslidecaptiontext" type="text" value="' + imgcaption + '" onchange="javascript:storesetwarning();" /></div>'

			+ '<div class="cksliderow"><div id="ckslideaccordion' + index + '">'
//			+ '<span class="ckslideaccordeonbutton">' + Joomla.JText._('MOD_CAROUSELCK_IMAGEOPTIONS', 'Image options') + '</span>'
			+ '<span class="ckslideaccordeonbutton">' + Joomla.JText._('MOD_CAROUSELCK_LINKOPTIONS', 'Link options') + '</span>'
			+ '<span class="ckslideaccordeonbutton">' + Joomla.JText._('MOD_CAROUSELCK_VIDEOOPTIONS', 'Video options') + '</span>'
			+ '<span class="ckslideaccordeonbutton">' + Joomla.JText._('MOD_CAROUSELCK_ARTICLEOPTIONS', 'Article options') + '</span>'
			+ '<div style="clear:both;"></div>'
//			+ '<div class="ckslideaccordeoncontent">'
//			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('MOD_CAROUSELCK_ALIGNEMENT_LABEL', 'Image alignment') + '</span><select name="ckslidedataalignmenttext' + index + '" class="ckslidedataalignmenttext" >' + imgdataalignmentoption + '</select></div>'
//			+ '</div>'
			+ '<div class="ckslideaccordeoncontent">'
			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('MOD_CAROUSELCK_LINK', 'Link url') + '</span><input name="ckslidelinktext' + index + '" class="ckslidelinktext" type="text" value="' + imglink + '" onchange="javascript:storesetwarning();" /></div>'
			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('MOD_CAROUSELCK_TARGET', 'Target') + '</span><select name="ckslidetargettext' + index + '" class="ckslidetargettext" >' + imgtargetoption + '</select></div>'
			+ '</div>'
			+ '<div class="ckslideaccordeoncontent">'
			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('MOD_CAROUSELCK_VIDEOURL', 'Video url') + '</span><input name="ckslidevideotext' + index + '" class="ckslidevideotext" type="text" value="' + imgvideo + '" onchange="javascript:storesetwarning();" /></div>'
			+ '</div>'
			+ '<div class="ckslideaccordeoncontent">'
			+ '<div class="cksliderow" id="cksliderowarticle' + index + '"><span class="ckslidelabel">' + Joomla.JText._('MOD_CAROUSELCK_ARTICLE_ID', 'Article ID') + '</span><input name="ckslidearticleid' + index + '" class="ckslidearticleid" id="ckslidearticleid' + index + '" style="width:20px" type="text" value="' + articleid + '" disabled="disabled" onchange="javascript:storesetwarning();" /><input name="ckslidearticlename' + index + '" class="ckslidearticlename" id="ckslidearticlename' + index + '" type="text" value="' + articlename + '" disabled="disabled" /><a id="ckslidearticlebuttonSelect" class="modal btn" href="index.php?option=com_content&amp;layout=modal&amp;view=articles&amp;tmpl=component&amp;function=jSelectArticle_ckslidearticleid' + index + '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}" style="display:inline-block;margin:0 5px 0 5px;">' + Joomla.JText._('MOD_CAROUSELCK_SELECT', 'Select') + '</a><a href="javascript:void(0)" onclick="document.getElementById(\'ckslidearticleid' + index + '\').value=\'\';document.getElementById(\'ckslidearticlename' + index + '\').value=\'\';">' + Joomla.JText._('MOD_CAROUSELCK_CLEAR', 'Clear') + '</a></div>'
			+ '</div>'
			+ '</div></div>'
			+ '</div><div style="clear:both;"></div>');

	document.id('ckslideslist').adopt(ckslide);

	script = document.createElement("script");
	script.setAttribute('type', 'text/javascript');
	script.text = "function jSelectArticle_ckslidearticleid" + index + "(id, title, catid, object) {"
			+ "document.getElementById('ckslidearticleid" + index + "').value = id;"
			+ "document.getElementById('ckslidearticlename" + index + "').value = title;"
			+ "SqueezeBox.close();"
			+ "}";

	document.body.appendChild(script);

	storeslideck();
	makesortables();
	SqueezeBox.initialize({});
	SqueezeBox.assign(ckslide.getElement('a.modal'), {
		parse: 'rel'
	});
	new Fx.Accordion($('accordion' + index), '#ckslideaccordion' + index + ' .ckslideaccordeonbutton', '#ckslideaccordion' + index + ' .ckslideaccordeoncontent',
			{
				onActive: function(toggler, content) {
					toggler.addClass('open');
				},
				onBackground: function(toggler, content) {
					toggler.removeClass('open');
				}
			});
}


function checkIndex(i) {
	while ($('ckslide' + i))
		i++;
	return i;
}


function removeslide(slide) {
	if (confirm(Joomla.JText._('MOD_CAROUSELCK_REMOVE', 'Remove this slide') + ' ?')) {
		slide.destroy();
		storeslideck();
	}
}

function storesetwarning() {
// $('ckstoreslide').setStyle('background-color', 'red');
}

function storeremovewarning() {
// $('ckstoreslide').setStyle('background-color', 'white');
}

function storeslideck() {
	var i = 0;
	var slides = new Array();
	document.id('ckslideslist').getElements('.ckslide').each(function(el) {
		slide = new Object();
		slide['imgname'] = el.getElement('.ckslideimgname').value;
		slide['imgcaption'] = el.getElement('.ckslidecaptiontext').value;
		slide['imgcaption'] = slide['imgcaption'].replace(/"/g, "|dq|");
		slide['imgtitle'] = el.getElement('.ckslidetitletext').value;
		slide['imgtitle'] = slide['imgtitle'].replace(/"/g,"|dq|");
		slide['imgthumb'] = el.getElement('img').src;
		slide['imglink'] = el.getElement('.ckslidelinktext').value;
		slide['imglink'] = slide['imglink'].replace(/"/g, "|dq|");
		slide['imgtarget'] = el.getElement('.ckslidetargettext').value;
//		slide['imgalignment'] = el.getElement('.ckslidedataalignmenttext').value;
		slide['imgvideo'] = el.getElement('.ckslidevideotext').value;
//		slide['slideselect'] = el.getElement('.ckslideselect').value;
		slide['slidearticleid'] = el.getElement('.ckslidearticleid').value;
		slide['slidearticlename'] = el.getElement('.ckslidearticlename').value;
//		slide['imgtime'] = el.getElement('.ckslideimgtime').value;
		slides[i] = slide;
		i++;
	});

	slides = JSON.encode(slides);
	slides = slides.replace(/"/g, "|qq|");
	document.id('ckslides').value = slides;
	storeremovewarning();

}

function callslides() {
	// alert(document.id('ckslides').value);
	var slides = JSON.decode(document.id('ckslides').value.replace(/\|qq\|/g, "\""));
	if (slides) {
		slides.each(function(slide) {
			addslideck(slide['imgname'],
					slide['imgcaption'],
					slide['imgthumb'],
					slide['imglink'],
					slide['imgtarget'],
					slide['imgvideo'],
					slide['slideselect'],
					slide['imgalignment'],
					slide['slidearticleid'],
					slide['imgtime'],
					slide['slidearticlename'],
					slide['imgtitle']
					);
		});
		storeremovewarning();
	}
}


function makesortables() {
	var sb = new Sortables('ckslideslist', {
		/* set options */
		clone: true,
		revert: true,
		handle: '.ckslidehandle',
		/* initialization stuff here */
		initialize: function() {

		},
		/* once an item is selected */
		onStart: function(el) {
			el.setStyle('background', '#add8e6');
		},
		/* when a drag is complete */
		onComplete: function(el) {
			el.setStyle('background', '#fff');
			storesetwarning();
		},
		onSort: function(el, clone) {

		}
	});
}

window.addEvent('domready', function() {
	callslides();

	var script = document.createElement("script");
	script.setAttribute('type', 'text/javascript');
	script.text = "Joomla.submitbutton = function(task){"
			+ "storeslideck();"
			+ "if (task == 'module.cancel' || document.formvalidator.isValid(document.id('module-form'))) {	Joomla.submitform(task, document.getElementById('module-form'));"
			+ "if (self != top) {"
			+ "window.top.setTimeout('window.parent.SqueezeBox.close()', 1000);"
			+ "}"
			+ "} else {"
			+ "alert('Formulaire invalide');"
			+ "}}";
	document.body.appendChild(script);
});
