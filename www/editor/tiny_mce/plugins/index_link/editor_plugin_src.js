/**
 * $Id: editor_plugin_src.js 162 2009-03-30 16:16:52Z makriria $
 *
 * @author Andre
 * @copyright SysClass
 */
(function() {
	
/* Import plugin specific language pack */
tinymce.PluginManager.requireLangPack('index_link');

tinymce.create('tinymce.plugins.IndexLinkPlugin', {

init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mceIndexLink', function() {
				tinyMCE.execInstanceCommand(ed, 'mceInsertContent', false, '<a href="index.php?index_magester">'+ 'index_link.linkdescription' +'</a>');
			});

			// Register example button
			ed.addButton('index_link', {
				title : 'index_link.desc',
				cmd : 'mceIndexLink',
				image : url + '/img/link.png'
			});
		
		},

	getInfo : function() {
			return {
				longname : 'Index Link plugin',
				author : 'makriria',
				authorurl : 'andre@ult.com.br',
				infourl : 'andre@ult.com.br',
				version : "1.0"
			};
		}
});
tinyMCE.addPlugin("index_link", TinyMCE_IndexLinkPlugin);
})();
