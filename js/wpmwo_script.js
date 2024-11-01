function wpmwo_get_online_user_ajax() {
     // e.preventDefault();
        jQuery.ajax(
          {
          type: 'POST',
          url:wpmwo_ajax.wpmwo_ajaxurl,
          data: {"action": "wpmwo_member_ajax_search" },        
        success: function(data)
            { 
              jQuery('#wpmwo_show_result').html(data);
            }
          });
         // return false;
      }