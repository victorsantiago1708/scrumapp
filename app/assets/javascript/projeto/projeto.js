// var salvaProjeto;

// window.onload = function(){
//     $(document).delegate(".saveProjeto", "click", salvaProjeto);
// };

// salvaProjeto = function(){
//     console.log("salvando...");
//     var url = "/scrumapp/projeto/save";
//     var type = $("#formProjeto").attr("method");
//     var data = $("#formProjeto").serialize();
//
//     $.ajax({
//         url: url,
//         type: type,
//         data: data,
//         success: function(data){
//             $("#conteudo").html(data);
//         }
//     });
//
//     return false;
// };