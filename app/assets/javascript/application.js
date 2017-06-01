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

