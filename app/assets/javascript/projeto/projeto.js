var deleteProjeto;

window.onload = function(){

    $(document).delegate(".delete", "click", deleteProjeto);

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