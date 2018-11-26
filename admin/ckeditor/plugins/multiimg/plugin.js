
/**
 * Copyright (c) 2014-2018, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * Basic sample plugin inserting abbreviation elements into the CKEditor editing area.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/ckeditor4/docs/#!/guide/plugin_sdk_sample_1
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add( 'multiimg', {

	// Register the icons.
	icons: 'multiimg',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog window.
		editor.addCommand( 'multiimg', new CKEDITOR.dialogCommand( 'multiimgDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'Multiimg', {

			// The text part of the button (if available) and the tooltip.
			label: '批量上传',

			// The command to execute on click.
			command: 'multiimg',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'insert,11'
		});

		// Register our dialog file -- this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'multiimgDialog', this.path + 'dialogs/multiimg.js' );
	}
});