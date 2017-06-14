/**
 * Created by Victor on 01/06/2017.
 */
var deleteTime;
var buscaProjetos;
var openModal;

window.onload = function(){

    $(document).delegate(".delete", "click", deleteTime);
    $(document).delegate(".attribProjeto", "click", openModal);

};

deleteTime = function(){
    var url = "/scrumapp/time/delete";
    var id = $(this).attr("data-id");
    bootbox.confirm("Deseja remover esse time?", function(result){
        if(result){
            $.ajax({
                url: url,
                type: "GET",
                data: {id: id},
                success: function(data){
                    console.log(data);
                    if(data == "true"){
                        window.location="/scrumapp/time/index";
                    }else{
                        bootbox.alert(data);
                    }
                }
            });
        }
    });
};

buscaProjetos = function(){
    var url = "/scrumapp/time/buscaProjetos";
    var id = $(this).attr("data-id");
    var dados = {};
    $.ajax({
        url: url,
        type: "POST",
        success: function(data){
            dados = JSON.parse(data);
            if(dados.length > 0){

            }else{
                bootbox.alert("Nenhum projeto encontrado!");
            }
        }
    });

};

openModal = function(){
    $("#projetos").modal();
}