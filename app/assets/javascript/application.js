var requisicao = function(element){
  var controller = $(element).attr("data-controller");
  var action = $(element).attr("data-action");
  var url = "/scrumapp"+"/"+controller+"/"+action;
  $.ajax({
      url: url,
      type:"post",
      success: function(data){
          $("#conteudo").html(data);
      }
  });
};

var attStatusSprint = function(sprintId, status){
    var status = status.toUpperCase();
    var id = sprintId;
    var projetoId = $("#projetoId").val();

    $.ajax({
        url:"/scrumapp/sprint/atualizaStatusSprint",
        type:"post",
        data: {id: id, status: status},
        success: function(data){
            var dados = JSON.parse(data);
            if(dados['result'] == 'true' || dados['result'] == true){
                window.location="/scrumapp/projeto/visualizar?id="+projetoId;
            }else{
                bootbox.alert("Não foi possível alterar essa sprint, favor atualize a página, caso o erro persista contate o suporte!", function () {
                    window.location="/scrumapp/projeto/visualizar?id="+projetoId;
                });
            }
        }
    });
};

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    ev.target.appendChild(document.getElementById(data));
    attStatusSprint(data, ev.target.id);
}