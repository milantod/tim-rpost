(function() {
    tinymce.PluginManager.add('mpt_tc_button', function( editor, url ) {
        editor.addButton( 'mpt_tc_button', {
            title: 'Recent posts',
            icon: 'icon dashicons-migrate',
            onclick: function() {
                var posts = prompt("Number of posts", "1");
                var text = prompt("List Heading", "Some recent posts");

                if (text != null && text != ''){
                    if (posts != null && posts != '')
                        editor.execCommand('mceInsertContent', false, '[recent-posts posts_per_page="'+posts+'"]'+text+'[/recent-posts]');
                    else
                        editor.execCommand('mceInsertContent', false, '[recent-posts]'+text+'[/recent-posts]');
                }
                else{
                    if (posts != null && posts != '')
                        editor.execCommand('mceInsertContent', false, '[recent-posts posts_per_page="'+posts+'"]');
                    else
                        editor.execCommand('mceInsertContent', false, '[recent-posts]');
                }
            }
        });
    });
})();

