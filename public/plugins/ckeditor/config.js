/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
 
CKEDITOR.editorConfig = function(config){
	config.entities_latin = false;
	config.allowedContent = true;
	config.enterMode = CKEDITOR.ENTER_P;
	config.shiftEnterMode = CKEDITOR.ENTER_BR;
	config.protectedSource.push(/<i[^>]*><\/i>/g);

  	config.pasteFromWordPromptCleanup = true;
   	config.pasteFromWordRemoveFontStyles = true;
   	config.forcePasteAsPlainText = true;
   	config.ignoreEmptyParagraph = true;
   	config.removeFormatAttributes = true;

	config.filebrowserBrowseUrl = '/public/admin/plugins/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = '/public/admin/plugins/ckfinder/ckfinder.html?type=Images';
	config.filebrowserFlashBrowseUrl = '/public/admin/plugins/ckfinder/ckfinder.html?type=Flash';
	config.filebrowserUploadUrl = '/public/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = '/public/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = '/public/admin/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};