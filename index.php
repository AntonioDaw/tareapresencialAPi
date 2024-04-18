<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Prubebas</title>
    <link rel="stylesheet" href="assets/estilo.css" type="text/css">
</head>
<body>

<div  class="container">
    <h1>Api de pruebas</h1>
    <div class="divbody">
        <h3>Auth - login</h3>
        <code>
           POST  /auth
           <br>
           {
               <br>
               "usuario" :"",  -> REQUERIDO
               <br>
               "password": "" -> REQUERIDO
               <br>
            }
        
        </code>
    </div>      
    <div class="divbody">   
        <h3>Tareas</h3>
        <code>
           GET  /tareas?page=$numeroPagina
           <br>
           GET  /tareas?id=$idTareas
        </code>

        <code>
           POST  /tareas
           <br> 
           {
            <br> 
               "titulo" : "",              
               <br> 
               "cat_id" : "",                  
               <br> 
               "descripcion":"",                 
               <br> 
               "lugar" :"",             
               <br>  
               "prioridad" : "",        
               <br>        
               "imagen" : "",       
               <br>       
               "hora" : "",      
               <br>         
               "token" : ""                       
               <br>       
           }

        </code>
        <code>
           PUT  /tareas
           <br> 
           {
            <br> 
               "titulo" : "",               
               <br> 
               "cat_id" : "",                  
               <br> 
               "descripcion":"",                 
               <br> 
               "lugar" :"",             
               <br>  
               "prioridad" : "",        
               <br>        
               "imagen" : "",       
               <br>       
               "hora" : "",      
               <br>         
               "token" : ""                         
               <br>       
               "id" : ""   
               <br>
           }

        </code>
        <code>
           DELETE  /tareas
           <br> 
           {   
               <br>    
               "token" : "",                      
               <br>       
               "id" : ""   
               <br>
           }

        </code>
    </div>


</div>
    
</body>
</html>