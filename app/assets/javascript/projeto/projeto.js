var deleteProjeto;
var novaSprintOpenModal;
var criarSprint;

window.onload = function(){

    $(document).delegate(".delete", "click", deleteProjeto);
    $(document).delegate(".novaSprint", "click", novaSprintOpenModal);
    $(document).delegate(".criar", "click", criarSprint);
    $('#novaSprint').on('hidden.bs.modal', function () {
        $(".erros").html("");
        $(".erros").addClass("hide");
    })
};

deleteProjeto = function(){
    var url = "/scrumapp/projeto/delete";
    var id = $(this).attr("id");
    bootbox.confirm("Deseja remover esse time?", function(result){
        if(result){
            $.ajax({
                url: url,
                type: "GET",
                data: {id: id},
                success: function(data){
                    if(data == "true"){
                        window.location="/scrumapp/projeto/index";
                    }else{
                        bootbox.alert(data);
                    }
                }
            });
        }
    });
};

novaSprintOpenModal = function(){
    $("#novaSprint").modal();
};

criarSprint = function(){
    var projetoId = $("#projetoId").val();
    var sprintName = $("#nome").val();
    var sprintDescricao = $("#descricao").val();
    if(sprintName!="" && sprintDescricao!=""){
        $.ajax({
            url: "/scrumapp/sprint/save",
            type: "post",
            data: {nome: sprintName, descricao: sprintDescricao, projetoId: projetoId},
            success: function(data){
                if(data['result'] != 'true'){
                    $(".erros").html(data['mensagem']);
                    $(".erros").fadeIn('fast', function(){ $(this).removeClass("hide"); });
                }else{
                    bootbox.alert(data['mensagem']);
                    $("#novaSprint").modal('hide');
                }
            }
        });
    }else{
        $(".erros").html("Você esqueceu de preencher algum campo, favor insira os dados corretamente!");
        $(".erros").fadeIn('fast', function(){ $(this).removeClass("hide"); });
    }
};