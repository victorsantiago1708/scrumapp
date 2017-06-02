/**
 * Created by Victor on 01/06/2017.
 */
var deleteTime;

window.onload = function(){

    $(document).delegate(".delete", "click", deleteTime);

};

deleteTime = function(){
    var url = "/scrumapp/time/delete";
    var id = $(this).attr("id");
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