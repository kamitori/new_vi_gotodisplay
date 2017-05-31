/**

 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.

 * For licensing, see LICENSE.md or http://ckeditor.com/license

 */

CKEDITOR.editorConfig = function( config ) {

	// Define changes to default configuration here. For example:

	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// config.toolbar = 'Basic';

    config.allowedContent = true;
	config.removeDialogTabs = 'link:upload;image:Upload';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.skin = 'bootstrapck';
	config.filebrowserImageUploadUrl = '/assets/admin/js/plugin/kcfinder/upload.php?type=images';
    config.filebrowserImageBrowseUrl = '/assets/admin/js/plugin/kcfinder/browse.php?type=images';

	// config.minimumChangeMilliseconds = 100; // 100 milliseconds (default value)

	// config.toolbar =
	// [
	// 	{ name: 'document', items : [ 'Source','-','Preview' ] },
	// 	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	// 	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll' ] },
	// 	{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton'] },

	// 	'/',

	// 	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	// 	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	// 	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	// 	{ name: 'links', items : [ 'Link','Unlink' ] },
	// 	{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar','PageBreak'] },

	// 	'/',

	// 	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	// 	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	// 	{ name: 'tools', items : [ 'Maximize', '-','About' ] }
	// ];

	config.extraPlugins = 'osem_googlemaps';
};
