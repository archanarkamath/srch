(function($) {
  $(document).ready(function() {
     isChecked = $('#edit-addresscopy').prop('checked')?true:false;
        if(isChecked)
        {
          $('#permanentAddress').hide();
        }
        else
        {
          $('#permanentAddress').show();
        }
        
        
      $("#edit-addresscopy").click(function(){
        isChecked = $('#edit-addresscopy').prop('checked')?true:false;
        if(isChecked)
        {
          $('#permanentAddress').hide();
        }
        else
        {
          $('#permanentAddress').show();
        }
        
      });   
  });
})(jQuery);

