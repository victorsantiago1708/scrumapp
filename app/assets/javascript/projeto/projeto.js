var deleteProjeto;
var novaSprintOpenModal;
var criarSprint;
var visualizarSprint;
var deleteSprint;

window.onload = function(){

    $(document).delegate(".delete", "click", deleteProjeto);
    $(document).delegate(".novaSprint", "click", novaSprintOpenModal);
    $(document).delegate(".criar", "click", criarSprint);
    $(document).delegate(".visualizarSprint", "click", visualizarSprint);
    $(document).delegate(".deleteSprint", "click", deleteSprint);
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
    var sprintsession = $("sprint-disponivel");
    var responsaveis = [];
    $("input[name='responsavel[]'").each(function(){
        if($(this).is(':checked')){
            responsaveis.push($(this).val());
        }
    });
    var id = $("#sprintId").val();

    if(sprintName!="" && sprintDescricao!=""){
        $.ajax({
            url: "/scrumapp/sprint/save",
            type: "post",
            data: {nome: sprintName, descricao: sprintDescricao, projetoId: projetoId, id: id, responsavel: responsaveis},
            success: function(data){
                console.log(data);
                var dados = JSON.parse(data);
                if(dados['result'] == true || dados['result'] == 'true'){
                    bootbox.alert(dados['mensagem'], function () {
                        $("#novaSprint").modal('hide');
                        window.location="/scrumapp/projeto/visualizar?id="+projetoId;
                    });

                }else{
                    $(".erros").html(dados['mensagem']);
                    $(".erros").fadeIn('fast', function(){ $(this).removeClass("hide"); });
                }
            }
        });
    }else{
        $(".erros").html("Você esqueceu de preencher algum campo, favor insira os dados corretamente!");
        $(".erros").fadeIn('fast', function(){ $(this).removeClass("hide"); });
    }
};

visualizarSprint = function () {
    $("#nome").val($(this).attr("data-nome"));
    $("#descricao").val($(this).attr("data-descricao"));
    $("#sprintId").val($(this).attr("data-id"));
    var responsaveis = JSON.parse($(this).attr("data-responsavel"));
    $("input[name='responsavel[]'").each(function(){
        console.log($(this).val());
        console.log(responsaveis);
        if(responsaveis.indexOf($(this).val()) > -1){
            $(this).attr("checked", true);
        }else{
            $(this).removeAttr("checked");
        }
    });
    novaSprintOpenModal();
};


deleteSprint = function(){
    var url = "/scrumapp/sprint/delete";
    var id = $(this).attr("id");
    var projetoId = $("#projetoId").val();
    bootbox.confirm("Deseja remover essa Sprint?", function(result){
        if(result){
            $.ajax({
                url: url,
                type: "GET",
                data: {id: id},
                success: function(data){
                    if(data == "true"){
                        window.location="/scrumapp/projeto/visualizar?id="+projetoId;
                    }else{
                        bootbox.alert(data);
                    }
                }
            });
        }
    });
};