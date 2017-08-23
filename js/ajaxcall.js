$(document).ready(function(){
    $('#select_tecnicos').change(function(){
        var tecnico = document.getElementById('select_tecnicos').value;
        //alert('Selected value is : ' + tecnico);

        
        $.ajax({
            type: 'POST',
            url: '/plugins/uns/front/ticketlocalization.form.php',
            
            data: {var_tecnico: tecnico},
            success: function(result)
            {
               console.log('Enviado satisfactoriamente');}
               ,
               error:function(exception){alert('Exeption:'+exception);}
               
            
        }); 
     
    });
  });