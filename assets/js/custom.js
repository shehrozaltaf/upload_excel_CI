/**
 * Created by Shehriz on 10/3/2015.
 */
function CallAjax(URL,Data,Type,CallBack,isFormData){
    var obj={
        url:URL,
        data:Data,
        type:Type,
        error:function(){
            if(CallBack){
                CallBack("Erorr");
            }
        },
        success:function(d){
            if(CallBack){
                CallBack(d||'No - record found');
            }
        }

    };
    if(isFormData){
        obj['contentType']=false;
        obj['processData']=false;
    }
    $.ajax(obj)
}

function getColors(param,ErrorText,inputs,ErrorDiv){
    var color='';
    if(param==1){
        color="green";
    }
    else if(param==4){
        color="blue";
    }
    else if(param==2){
        color="red";
    }
    else{
        color="red";
    }
    $('#'+ErrorDiv).css('display','block');
    $('#'+ErrorDiv).html(ErrorText);
    $(inputs).css('border','1px solid '+color);
    $('#Error').css('color',color);
    setTimeout(function(){
        $('#'+ErrorDiv).html('');
        $('#'+ErrorDiv).css('display','none');
    },2000);

}