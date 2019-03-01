<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="lib/bootstrap-4.0.0_lite/css/bootstrap.min.css" type="text/css">
  <title>Paginacion Get</title>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-danger navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fa d-inline fa-lg fa-list"></i> 
        <b>DELETE REGISTRO / DATABASE</b>
      </a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar2SupportedContent" aria-controls="navbar2SupportedContent"
        aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
    </div>
  </nav>
  <div class="section py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <h4>Lista de Libros</h4>
          <small>Presione en la "X" para eliminar el registro</small>
          <ul class="list-group" id='booklist'>
            
          </ul>
          <div class="alert" role="alert" id="message_rsta" style="display: none;"></div>
        </div>
      </div>
    </div>
  </div>
  <script src="js/jquery-3.2.1.js"></script>
  <script src="lib/bootstrap-4.0.0_lite/js/popper.min.js"></script>
  <script src="lib/bootstrap-4.0.0_lite/js/bootstrap.min.js"></script>
  <script>
  	$(document).ready(function() {
      viewBookList();
    });
    function viewBookList(){
      // Limpiamos la lista antes de mostrar nuevo contenido
      $("#booklist").html("");
      // Solicitamos al servidor mostrarnos la lista de libros registrados
      $.post("query_sql.php",{
        action: "getBookList"
      },function(res){
        for( i in res ){
          $("#booklist").append(
            '<li class="list-group-item d-flex justify-content-between align-items-center text-info" id="book_'+res[i].id_libro+'">'
              +res[i].titulo.toUpperCase()
              +'<span class="badge badge-danger badge-pill" onclick="bookDelete('+res[i].id_libro+')" style="cursor:pointer;"><i class="fa fa-remove"></i></span>'
            +'</li>'
          );
        }
      },"json");
    }
    function selectedBook(id){
      // Resaltar libro seleccionado
      $(".list-group-item").removeClass('active');
      $("#book_"+id).addClass('active');
      // Solicitamos al servidor mostrarnos los datos del libro seleccionado
      $.post("query_sql.php",{
        action: "getDataBook"
        ,id_libro: id
      },function(res){
        var info = res[0]; //[0]: Primer registro encontrado
        console.log(info);
        // Escribimos datos del libro seleccionado en el formulario automaticamente
        $("#id_libro").val(info.id_libro);
        $("#codigo").val(info.codigo);
        $("#titulo").val(info.titulo);
        $("#autor").val(info.autor);
        $("#editorial").val(info.editorial);
        $("#ejemplares").val(info.ejemplares);
        $("#fech_registro").val(info.fech_registro);
      },"json");
    }

    // ********************** ACTION DELETE ****************************//
    function bookDelete(id){
      var proceder = confirm("¿Esta seguro que quiere eliminar el registro?");
      if(!proceder) //Si cancela la operación, se cancelara el proceso
        return;
      // Enviamos solicitud de eliminador al servidor
      $.post("query_sql.php",{
        action: "delete_book" //acción a ejecutar en QUERY_SQL
        ,id_libro: id //Important. Un identificador para eliminar registro especifico
      },function(state_res){
        console.log(state_res);
        
        if(state_res>0){ //Si es mayor a cero, hubo registro exitoso
          $("#message_rsta").attr("class","alert alert-primary"); //Color verde
          $("#message_rsta").html("Se elimino libro con exito [ID: "+id+"]").show();
          // Action for Delete Row Item List HTML 
          // $("#book_"+id).remove();
          $("#book_"+id).fadeOut(1000, function() { $(this).remove(); });
        }else{
          $("#message_rsta").attr("class","alert alert-danger"); //Color rojo
          $("#message_rsta").html("Error al Registrar").show();
        }
        setTimeout(function(){
          $("#message_rsta").hide();
        },3000);
      });
    }
    // ********************** FIN - ACTION DELETE ****************************//
  </script>
</body>
</html>